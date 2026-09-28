import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import ProductDetail from '../pages/ProductDetail.vue';

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: HomePage },
        { path: '/product/:slug', name: 'product', component: ProductDetail },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
    scrollBehavior(to, from, saved) {
        if (to.hash) return { el: to.hash, behavior: 'smooth' };
        if (saved) return saved;
        return { top: 0 };
    },

    scrollBehavior(to, from, saved) {
    if (saved) return saved;

    if (to.hash) {
        const header = document.querySelector('header');
        const offset = (header?.offsetHeight ?? 80) + 16; // tinggi navbar + jarak 16px
        return { el: to.hash, top: offset, behavior: 'smooth' };
    }

    return { top: 0 };
},
});

