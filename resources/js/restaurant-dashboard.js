export default function restaurantDashboard(initial) {
    return {
        initial,
        get summary() { return this.$store.restaurant.snapshot?.resumen || this.initial; },
        get weekTotal() { return this.summary.ventasSemana.reduce((sum, day) => sum + day.ventas, 0); },
        get weekMax() { return Math.max(1, ...this.summary.ventasSemana.map(day => day.ventas)); },
        money(value) { return this.$store.restaurant.money(value); },
    };
}
