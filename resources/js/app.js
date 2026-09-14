import Alpine from 'alpinejs';
import { io } from 'socket.io-client';

window.Alpine = Alpine;

Alpine.start();


window.socket = io('http://localhost:3001');


window.socket.on('connect', () => {

    console.log(
        '🟢 Conectado a Socket.IO:',
        window.socket.id
    );

});


window.socket.on('disconnect', () => {

    console.log(
        '🔴 Desconectado de Socket.IO'
    );

});


window.socket.on('pedido_actualizado', (data) => {

    console.log(
        '🍽️ Pedido recibido EN VIVO:',
        data
    );

    window.dispatchEvent(
        new CustomEvent('pedido-actualizado', {
            detail: data
        })
    );

});