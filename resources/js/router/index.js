import { createRouter, createWebHistory } from 'vue-router';
import OrderList from '../components/OrderList.vue';
import OrderDetails from '../components/OrderDetails.vue';

const routes = [
    { path: '/', name: 'OrderList', component: OrderList },
    { path: '/orders/:id', name: 'OrderDetails', component: OrderDetails },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;