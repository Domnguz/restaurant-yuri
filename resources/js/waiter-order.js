export default function waiterOrder(catalog, components, endpoint, backUrl, previousTotal, tableId, registeredOrder = null) {
    return {
        catalog, components, endpoint, backUrl, previousTotal, category: null, search: '', items: [], notes: '', error: '', sending: false,
        nextKey: 1, tableId, registeredOrder, expectedOrderId: registeredOrder?.id || null, blocked: false,
        init() {
            this.sync(this.$store.restaurant.snapshot);
            this.$watch('$store.restaurant.snapshot', data => this.sync(data));
        },
        sync(data) {
            if (!data) return;
            const current = data.pedidos.find(order => order.mesa_id === this.tableId);
            if (this.expectedOrderId && (!current || current.id !== this.expectedOrderId)) {
                this.blocked = true;
                this.error = 'El administrador cerró o canceló este pedido. Vuelve a mesas antes de iniciar otra atención.';
                return;
            }
            if (current) { this.registeredOrder = current; this.expectedOrderId = current.id; this.previousTotal = current.total; }
        },
        money(value) { return 'S/ ' + Number(value).toFixed(2); },
        normalize(value) { return value.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase(); },
        matches(id) { const p = this.catalog.find(p => p.id === id); return p && (this.category === null || p.categoria_id === this.category) && this.normalize(p.nombre).includes(this.normalize(this.search.trim())); },
        get visibleCount() { return this.catalog.filter(p => this.matches(p.id)).length; },
        get total() { return this.items.reduce((sum,i) => sum + Number(i.precio), 0); },
        add(id) {
            const product = this.catalog.find(p => p.id === id); if (!product) return;
            if (product.personalizable) return;
            this.put(product, Number(product.precio));
        },
        addMixto(id, price, selected) {
            const product = this.catalog.find(p => p.id === id);
            const ids = [...new Set(selected.map(Number))].filter(id => this.components.some(c => c.id === id));
            if (!product?.personalizable || !product.precios_personalizacion.includes(Number(price))) return 'Selecciona un precio válido.';
            if (ids.length === 0) return 'Selecciona al menos un componente para el mixto.';
            this.put(product, Number(price), ids);
            return '';
        },
        put(product, price, ids = []) {
            this.items.push({ key: this.nextKey++, producto_id: product.id, nombre: product.nombre, precio: price, componente_ids: ids, componentes: ids.map(id => this.components.find(c => c.id === id)?.nombre).filter(Boolean).join(', '), observaciones: '' });
        },
        async submit() {
            if (this.sending || this.blocked || this.items.length === 0) return;
            this.sending = true; this.error = '';
            try {
                const response = await fetch(this.endpoint, { method:'POST', headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body:JSON.stringify({ items:this.items, observaciones:this.notes, pedido_id:this.expectedOrderId }) });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'No se pudo guardar el pedido.'));
                window.location.href = this.backUrl;
            } catch (error) { this.error = error instanceof TypeError ? 'Revisa tu conexión e intenta nuevamente.' : (error.message || 'No se pudo guardar el pedido. Intenta nuevamente.'); this.sending = false; }
        },
    };
}
