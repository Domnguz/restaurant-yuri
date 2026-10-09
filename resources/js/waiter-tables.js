export default function waiterTables(tables, orders, baseUrl) {
    return {
        tables, orders, baseUrl, zone: 'all', status: 'all',
        init() {
            this.sync(this.$store.restaurant.snapshot);
            this.$watch('$store.restaurant.snapshot', data => this.sync(data));
        },
        sync(data) { if (data) { this.tables = data.mesas; this.orders = data.pedidos; } },
        orderFor(id) { return this.orders.find(order => order.mesa_id === id); },
        get filtered() { return this.tables.filter(table => (this.zone === 'all' || this.zone === table.zona) && (this.status === 'all' || this.status === table.estado)); },
        get available() { return this.tables.filter(table => table.estado === 'disponible').length; },
        get occupied() { return this.tables.filter(table => table.estado === 'ocupada').length; },
    };
}
