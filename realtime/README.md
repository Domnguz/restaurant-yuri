# Pedidos en vivo · Mr. Yuri

El mesero registra productos, componentes del mixto e indicaciones. El administrador recibe los pedidos, prepara y sirve los platos, cambia el estado, cancela y registra pagos. Esas acciones se sincronizan mediante Node y Socket.IO en pedidos, mesas, detalle y resumen del día. El mesero conserva su selección y sus notas mientras llegan actualizaciones.

## Iniciar en esta computadora

Desde la carpeta principal del proyecto:

```powershell
npm install
npm --prefix realtime ci
npm run build
npm run realtime
```

Mantén Node abierto mientras atienden. Laravel puede servirse desde XAMPP. Si ya había un servidor Node ejecutándose con una versión anterior, detenlo con Ctrl+C en su terminal y vuelve a ejecutar `npm run realtime`. Para desarrollo con reinicio al guardar: `npm --prefix realtime run dev`.

Node lee el `.env` de Laravel automáticamente, aunque se ejecute desde otra carpeta. Laravel publica por `REALTIME_URL` (por defecto `http://127.0.0.1:3001`). El navegador usa `REALTIME_PUBLIC_URL` o el mismo nombre/IP con el que se abrió Laravel, en `REALTIME_PORT` (3001 por defecto). Node escucha en todas las interfaces.

## Computadora del administrador y celulares en la red del restaurante

Abre Laravel desde los celulares usando la IP de la computadora del restaurante. La computadora y los celulares deben estar en la misma red y poder acceder al puerto de Laravel y al puerto indicado por `REALTIME_PORT`. Esta instalación usa actualmente el puerto **3002**. Si usas otro puerto o un dominio separado, configura estas variables en el `.env` principal:

```dotenv
REALTIME_PORT=3001
REALTIME_URL=http://127.0.0.1:3001
REALTIME_PUBLIC_URL=http://192.168.1.10:3001
# Opcional: restringir los orígenes a los de Laravel, incluyendo su puerto si lo tiene.
REALTIME_ALLOWED_ORIGINS=http://192.168.1.10,http://localhost
```

Sustituye la IP por la de tu computadora. Después de cambiar variables, ejecuta `php artisan config:clear` y reinicia Node. Para HTTPS, usa también una dirección HTTPS para Node mediante un proxy que permita WebSocket; no mezcles HTTPS en Laravel con HTTP en Node.

## Recuperación y permisos

- Las escrituras se validan y guardan en Laravel; Node publica después de confirmar la transacción. Tanto modificaciones como estados, pagos y cancelaciones emiten eventos.
- Node acepta conexiones de personal con una credencial firmada que dura cinco minutos. La credencial se renueva al reconectar. La publicación desde Laravel requiere una clave privada. Usa `REALTIME_SECRET` o, si no está definido, `APP_KEY`. Esa clave nunca se envía al navegador.
- Los eventos solo avisan de cambios. Cada pantalla obtiene los pedidos desde Laravel con su sesión y permisos. El mesero no recibe información de pagos y no puede cancelar, cobrar ni cambiar estados.
- Al conectar, reconectar, volver a una pestaña o recuperar internet, se consultan los pedidos guardados. Hay una comprobación periódica de 30 segundos con conexión y una consulta cada ocho segundos si Node está desconectado. La señal de conexión se ve en la parte superior.
- Si Node falla, el pedido sigue guardado. La actualización automática permite continuar mientras se restablece Node. Si Laravel también está inaccesible, las pantallas conservan los últimos datos e indican que están reintentando.
- Un pedido cancelado o pagado no se vuelve a abrir desde un formulario antiguo. Agregar productos a un pedido listo o servido vuelve a dejarlo en preparación. Los cobros se validan con el total actual bajo bloqueo de la mesa y el pedido.

## Comprobar

```powershell
php artisan test --compact
npm --prefix realtime test
npm run build
```

Las pruebas de Node conectan un administrador y un mesero reales por Socket.IO, verifican todos los eventos y rechazan conexiones y publicaciones no autorizadas. Las pruebas Laravel usan una base de datos aislada.

La reconciliación al reconectar cubre los eventos que Socket.IO podría perder durante una desconexión, según sus [garantías de entrega](https://socket.io/docs/v4/delivery-guarantees/). La autenticación usa [middleware de Socket.IO](https://socket.io/docs/v4/middlewares/).
