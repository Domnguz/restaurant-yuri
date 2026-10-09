export default function orderBoard(orders, tables, baseUrl) {
    return {
        orders, tables, baseUrl, search: '', status: 'all',
        init() {
            this.sync(this.$store.restaurant.snapshot);
            this.$watch('$store.restaurant.snapshot', data => this.sync(data));
        },
        sync(data) { if (data) { this.orders = data.pedidos; this.tables = data.mesas; } },
        label(state) { return this.$store.restaurant.stateLabel(state); },
        get filtered() {
            const normalize = value => String(value).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
            return this.orders.filter(order => (this.status === 'all' || order.estado === this.status) && normalize(`Mesa ${order.mesa_numero} ${order.id} ${order.mesero}`).includes(normalize(this.search)));
        },
        orderFor(id) { return this.orders.find(order => order.mesa_id === id); },
    };
}