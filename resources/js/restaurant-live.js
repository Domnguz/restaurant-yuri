import { io } from 'socket.io-client';

export default function restaurantLive(config) {
    return {
        connected: false, failed: false, expired: false, snapshot: null, notice: '', error: '', busy: false,
        fetching: false, pending: false, lastSync: 0, eventIds: new Set(),
        get label() {
            if (this.expired) return 'Sesión vencida';
            if (this.failed) return 'Sin conexión · reintentando';
            return this.connected ? 'Conectado en vivo' : 'Reconectando · actualización automática';
        },
        init() {
            if (!config.enabled) return;
            const endpoint = config.endpoint || `${window.location.protocol}//${window.location.hostname}:${config.port || 3001}`;
            const socket = io(endpoint, {
                autoConnect: false, reconnectionDelay: 1000, reconnectionDelayMax: 5000,
                auth: async done => {
                    try {
                        const response = await fetch(config.tokenUrl, { headers: { Accept: 'application/json' }, cache: 'no-store' });
                        if ([401, 419].includes(response.status)) { this.expired = true; done({}); return; }
                        if (!response.ok) throw new Error('No se pudo conectar.');
                        done({ token: (await response.json()).token });
                    } catch { done({}); }
                },
            });
            window.socket = socket;
            socket.on('connect', () => { this.connected = true; this.refresh(); });
            socket.on('disconnect', reason => {
                this.connected = false;
                if (reason === 'io server disconnect' && !this.expired) socket.connect();
            });
            socket.on('connect_error', () => {
                this.connected = false;
                clearTimeout(this.reconnectTimer);
                if (!this.expired) this.reconnectTimer = setTimeout(() => socket.connect(), 5000);
            });
            socket.on('pedido_actualizado', event => {
                if (!event?.event_id || this.eventIds.has(event.event_id)) return;
                this.eventIds.add(event.event_id);
                if (this.eventIds.size > 100) this.eventIds.delete(this.eventIds.values().next().value);
                this.latestEvent = event;
                clearTimeout(this.eventTimer);
                this.eventTimer = setTimeout(() => this.refresh(), 100);
            });
            document.addEventListener('visibilitychange', () => { if (!document.hidden) this.refresh(); });
            window.addEventListener('online', () => { this.refresh(); if (!socket.connected && !this.expired) socket.connect(); });
            window.addEventListener('pageshow', event => { if (event.persisted) this.refresh(); });
            this.pollTimer = setInterval(() => {
                if (!document.hidden && (!this.connected || Date.now() - this.lastSync > 30000)) this.refresh();
            }, 8000);
            this.refresh();
            socket.connect();
        },
        async refresh() {
            if (!config.enabled || this.expired) return;
            if (this.fetching) { this.pending = true; return; }
            this.fetching = true;
            const event = this.latestEvent; this.latestEvent = null;
            try {
                const url = new URL(config.snapshotUrl, window.location.href);
                const focused = document.querySelector('[data-live-order]')?.dataset.liveOrder;
                if (focused) url.searchParams.set('pedido', focused);
                const response = await fetch(url, { headers: { Accept: 'application/json' }, cache: 'no-store', signal: AbortSignal.timeout(10000) });
                if ([401, 419].includes(response.status)) { this.expired = true; window.socket?.disconnect(); return; }
                if (!response.ok) throw new Error('No se pudieron sincronizar los pedidos.');
                const data = await response.json();
                if (!Array.isArray(data.pedidos) || !Array.isArray(data.mesas)) throw new Error('Datos incompletos.');
                this.snapshot = data; this.failed = false; this.lastSync = Date.now();
                if (event) {
                    const order = data.pedidos.find(order => order.id === event.pedido.id) || (data.pedido?.id === event.pedido.id ? data.pedido : null);
                    const table = data.mesas.find(table => table.id === event.pedido.mesa_id);
                    const message = { created: 'Nuevo pedido', items_added: 'Productos agregados', state_changed: order ? this.stateLabel(order.estado) : 'Estado actualizado', cancelled: 'Pedido cancelado', paid: 'Pedido pagado' }[event.action];
                    this.notify(`${message || 'Pedido actualizado'} · Mesa ${table?.numero || ''}`);
                }
            } catch { this.failed = true; }
            finally {
                this.fetching = false;
                if (this.pending) { this.pending = false; this.refresh(); }
            }
        },
        stateLabel(state) { return { pendiente: 'En preparación', listo: 'Listo para servir', entregado: 'Servido', pagado: 'Pagado', cancelado: 'Cancelado' }[state] || state; },
        money(value) { return 'S/ ' + Number(value || 0).toFixed(2); },
        notify(message) {
            this.notice = message; clearTimeout(this.noticeTimer);
            this.noticeTimer = setTimeout(() => this.notice = '', 6000);
        },
        async submit(form) {
            if (this.busy || this.expired) return null;
            this.busy = true; this.error = '';
            try {
                const response = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: new FormData(form) });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'No se pudo guardar. Revisa tu conexión.'));
                this.notify(data.message); await this.refresh(); return data;
            } catch (error) { this.error = error instanceof TypeError ? 'Revisa tu conexión e intenta nuevamente.' : error.message; await this.refresh(); return null; }
            finally { this.busy = false; }
        },
    };
}
