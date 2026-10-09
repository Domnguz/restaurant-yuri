const { test } = require('node:test');
const assert = require('node:assert/strict');
const { once } = require('node:events');
const crypto = require('node:crypto');
const { io: client } = require('socket.io-client');
const { createRealtimeServer, verifyToken } = require('../app');

const secret = 'test-only-shared-key-for-realtime';
function token(role, exp = Math.floor(Date.now() / 1000) + 300) {
    const payload = Buffer.from(JSON.stringify({ sub: 1, role, exp })).toString('base64url');
    return `${payload}.${crypto.createHmac('sha256', secret).update(payload).digest('hex')}`;
}
async function start(t) {
    const service = createRealtimeServer({ secret });
    service.server.listen(0, '127.0.0.1'); await once(service.server, 'listening');
    t.after(() => new Promise(resolve => service.io.close(resolve)));
    return `http://127.0.0.1:${service.server.address().port}`;
}
function socket(t, url, auth) {
    const connection = client(url, { auth, transports: ['websocket'], reconnection: false });
    t.after(() => connection.disconnect());
    return connection;
}
const event = action => ({ event_id: crypto.randomUUID(), action, pedido: { id: 7, mesa_id: 2, estado: action === 'cancelled' ? 'cancelado' : action === 'paid' ? 'pagado' : 'pendiente' } });
async function publish(url, body, authorization = `Bearer ${secret}`) {
    return fetch(url + '/api/pedidos/actualizado', { method: 'POST', headers: { 'content-type': 'application/json', authorization }, body: JSON.stringify(body) });
}

test('rejects missing, modified, expired and non-staff credentials', async t => {
    const url = await start(t);
    for (const credential of [undefined, token('cliente'), token('admin', Math.floor(Date.now() / 1000) - 1), token('admin') + 'x']) {
        const connection = socket(t, url, { token: credential });
        await once(connection, 'connect_error'); assert.equal(connection.connected, false);
    }
    assert.throws(() => verifyToken(token('admin', Math.floor(Date.now() / 1000) + 900), secret));
});

test('broadcasts every order action to admin and waiter without private data', async t => {
    const url = await start(t);
    const admin = socket(t, url, { token: token('admin') });
    const waiter = socket(t, url, { token: token('mesero') });
    await Promise.all([once(admin, 'connect'), once(waiter, 'connect')]);
    for (const action of ['created', 'items_added', 'state_changed', 'cancelled', 'paid']) {
        const body = { ...event(action), confidential: 'should not be broadcast' };
        const received = Promise.all([once(admin, 'pedido_actualizado'), once(waiter, 'pedido_actualizado')]);
        assert.equal((await publish(url, body)).status, 200);
        for (const [payload] of await received) {
            assert.deepEqual(payload, { event_id: body.event_id, action, pedido: body.pedido });
        }
    }
});

test('rejects forged publications, malformed events and invalid JSON', async t => {
    const url = await start(t);
    const waiter = socket(t, url, { token: token('mesero') }); await once(waiter, 'connect');
    let delivered = 0; waiter.on('pedido_actualizado', () => delivered++);
    assert.equal((await publish(url, event('created'), '')).status, 401);
    assert.equal((await publish(url, event('created'), `Bearer ${token('mesero')}`)).status, 401);
    assert.equal((await publish(url, { ...event('created'), action: 'delete' })).status, 422);
    assert.equal((await publish(url, { ...event('created'), pedido: { id: -1 } })).status, 422);
    const malformed = await fetch(url + '/api/pedidos/actualizado', { method: 'POST', headers: { 'content-type': 'application/json' }, body: '{' });
    assert.equal(malformed.status, 400);
    // Clients cannot create publications through Socket.IO either.
    waiter.emit('pedido_actualizado', event('created'));
    await new Promise(resolve => setTimeout(resolve, 50)); assert.equal(delivered, 0);
    assert.equal((await fetch(url + '/health')).status, 200);
});

test('disconnects an authenticated client when its signed token expires', async t => {
    const url = await start(t);
    const waiter = socket(t, url, { token: token('mesero', Math.floor(Date.now() / 1000) + 2) });
    await once(waiter, 'connect');
    const [reason] = await once(waiter, 'disconnect');
    assert.equal(reason, 'io server disconnect'); assert.equal(waiter.connected, false);
});
