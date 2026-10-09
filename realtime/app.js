const express = require('express');
const http = require('node:http');
const crypto = require('node:crypto');
const { Server } = require('socket.io');

function matches(a, b) {
    const left = Buffer.from(String(a || ''));
    const right = Buffer.from(String(b || ''));
    return left.length === right.length && crypto.timingSafeEqual(left, right);
}

function verifyToken(token, secret) {
    if (typeof token !== 'string' || token.length > 2048) throw new Error('Sesión no válida.');
    const parts = token.split('.');
    if (parts.length !== 2 || !matches(parts[1], crypto.createHmac('sha256', secret).update(parts[0]).digest('hex'))) throw new Error('Sesión no válida.');
    const claims = JSON.parse(Buffer.from(parts[0], 'base64url').toString('utf8'));
    if (!Number.isInteger(claims.sub) || claims.sub < 1 || !['admin', 'mesero'].includes(claims.role) || !Number.isInteger(claims.exp) || claims.exp <= Date.now() / 1000 || claims.exp > Date.now() / 1000 + 310) throw new Error('Sesión vencida.');
    return claims;
}

function createRealtimeServer({ secret, origins = [] }) {
    if (!secret) throw new Error('El servidor necesita una clave compartida.');
    const app = express();
    const server = http.createServer(app);
    const io = new Server(server, {
        // Signed, short-lived credentials are required even when LAN origins are allowed.
        cors: { origin: origins.length ? origins : true, methods: ['GET', 'POST'] },
        maxHttpBufferSize: 16 * 1024,
    });
    app.disable('x-powered-by');
    app.use(express.json({ limit: '16kb' }));
    io.use((socket, next) => {
        try { socket.data.claims = verifyToken(socket.handshake.auth.token, secret); next(); }
        catch { next(new Error('Inicia sesión para conectarte en vivo.')); }
    });
    io.on('connection', socket => {
        socket.join('restaurant-staff');
        const expiry = setTimeout(() => socket.disconnect(true), socket.data.claims.exp * 1000 - Date.now());
        socket.on('disconnect', () => clearTimeout(expiry));
    });
    app.get('/', (req, res) => res.json({ ok: true, service: 'Mr. Yuri · tiempo real' }));
    app.get('/health', (req, res) => res.json({ ok: true }));
    app.post('/api/pedidos/actualizado', (req, res) => {
        if (!matches(req.get('authorization'), `Bearer ${secret}`)) return res.status(401).json({ message: 'No autorizado.' });
        const { event_id, action, pedido } = req.body || {};
        if (typeof event_id !== 'string' || !/^[a-f0-9-]{36}$/i.test(event_id) || !['created', 'items_added', 'state_changed', 'cancelled', 'paid'].includes(action)
            || !Number.isInteger(pedido?.id) || pedido.id < 1 || !Number.isInteger(pedido.mesa_id) || pedido.mesa_id < 1
            || !['pendiente', 'listo', 'entregado', 'cancelado', 'pagado'].includes(pedido.estado)) return res.status(422).json({ message: 'Evento de pedido inválido.' });
        // Signals contain no customer notes or payment data; each browser reads its authorized snapshot.
        io.to('restaurant-staff').emit('pedido_actualizado', { event_id, action, pedido: { id: pedido.id, mesa_id: pedido.mesa_id, estado: pedido.estado } });
        return res.json({ ok: true });
    });
    app.use((error, req, res, next) => res.status(error.status || 500).json({ message: 'No se pudo procesar la solicitud.' }));
    return { app, server, io };
}

module.exports = { createRealtimeServer, verifyToken };
