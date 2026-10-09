export default function orderDetail(order, method, received) {
    return {
        order, method, received,
        init() {
            this.sync(this.$store.restaurant.snapshot);
            this.$watch('$store.restaurant.snapshot', data => this.sync(data));
        },
        sync(data) { if (data?.pedido?.id === this.order.id) this.order = data.pedido; },
        get closed() { return ['pagado', 'cancelado'].includes(this.order.estado); },
        get difference() { return Number(this.received || 0) - this.order.total; },
        get valid() { return this.method !== 'efectivo' || (this.received !== '' && this.difference >= 0); },
        money(value) { return this.$store.restaurant.money(value); },
        label(state) { return this.$store.restaurant.stateLabel(state); },
        async submit(form) {
            const result = await this.$store.restaurant.submit(form);
            if (result?.pedido) this.order = result.pedido;
        },
        cancel(form) { if (confirm('¿Cancelar este pedido y liberar la mesa?')) this.submit(form); },
    };
}
