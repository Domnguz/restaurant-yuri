import Alpine from 'alpinejs';
import restaurantLive from './restaurant-live';
import waiterTables from './waiter-tables';
import orderDetail from './order-detail';
import restaurantDashboard from './restaurant-dashboard';
import orderBoard from './order-board';
import waiterOrder from './waiter-order';

window.Alpine = Alpine;
Alpine.data('orderBoard', orderBoard);
Alpine.data('waiterOrder', waiterOrder);
Alpine.data('waiterTables', waiterTables);
Alpine.data('orderDetail', orderDetail);
Alpine.data('restaurantDashboard', restaurantDashboard);

Alpine.store('restaurant', restaurantLive({
    enabled: document.body.dataset.realtime === 'true',
    endpoint: document.body.dataset.realtimeUrl || import.meta.env.VITE_REALTIME_URL,
    port: Number(document.body.dataset.realtimePort),
    snapshotUrl: document.body.dataset.snapshotUrl,
    tokenUrl: document.body.dataset.tokenUrl,
}));
Alpine.start();
