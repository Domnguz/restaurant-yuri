const express = require('express');
const http = require('http');
const cors = require('cors');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);

app.use(cors());
app.use(express.json());

const io = new Server(server, {
    cors: {
        origin: '*',
        methods: ['GET', 'POST'],
    },
});


/*
|--------------------------------------------------------------------------
| Conexiones Socket.IO
|--------------------------------------------------------------------------
*/

io.on('connection', (socket) => {

    console.log('🟢 Usuario conectado:', socket.id);

    socket.on('disconnect', () => {
        console.log('🔴 Usuario desconectado:', socket.id);
    });

});


/*
|--------------------------------------------------------------------------
| Ruta de prueba
|--------------------------------------------------------------------------
*/

app.get('/', (req, res) => {

    res.send(
        'Servidor en tiempo real de Mr. Yuri funcionando ✅'
    );

});


/*
|--------------------------------------------------------------------------
| Laravel avisa que un pedido cambió
|--------------------------------------------------------------------------
*/

app.post('/api/pedidos/actualizado', (req, res) => {

    const datos = req.body;

    console.log('');
    console.log('🍽️ Pedido recibido desde Laravel');
    console.log(datos);

    /*
    |--------------------------------------------------------------------------
    | Enviar a TODOS los navegadores conectados
    |--------------------------------------------------------------------------
    */

    io.emit('pedido_actualizado', datos);

    res.json({
        ok: true,
        message: 'Pedido enviado en tiempo real'
    });

});


/*
|--------------------------------------------------------------------------
| Encender servidor
|--------------------------------------------------------------------------
*/

const PORT = process.env.PORT || 3001;

server.listen(PORT, '0.0.0.0', () => {

    console.log('');
    console.log('======================================');
    console.log('🍽️ MR. YURI - SERVIDOR EN TIEMPO REAL');
    console.log('======================================');

    console.log(
        `🚀 Servidor ejecutándose en puerto ${PORT}`
    );

    console.log(
        '🔌 Socket.IO esperando conexiones'
    );

    console.log('');

});