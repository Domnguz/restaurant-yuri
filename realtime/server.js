const { createRealtimeServer } = require('./app');
const path = require('node:path');
require('dotenv').config({ path: path.resolve(__dirname, '../.env'), quiet: true });

const secret = process.env.REALTIME_SECRET || process.env.APP_KEY;
if (!secret) throw new Error('Configura APP_KEY o REALTIME_SECRET en el archivo .env de Laravel.');
const origins = (process.env.REALTIME_ALLOWED_ORIGINS || '').split(',').map(value => value.trim()).filter(Boolean);
const { server, io } = createRealtimeServer({ secret, origins });
const port = Number(process.env.REALTIME_PORT || process.env.PORT || 3001);
server.listen(port, '0.0.0.0', () => console.log(`Mr. Yuri · tiempo real disponible en puerto ${port}`));
server.on('error', error => { console.error(`No se pudo iniciar Node: ${error.message}`); process.exitCode = 1; });
for (const signal of ['SIGINT', 'SIGTERM']) process.on(signal, () => io.close(() => process.exit(0)));