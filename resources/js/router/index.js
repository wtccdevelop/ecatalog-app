import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import LoginPage from '../pages/LoginPage.vue';
import AdminLayout from '../pages/admin/AdminLayout.vue';
import DashboardPage from '../pages/admin/DashboardPage.vue';
import BrandsPage from '../pages/admin/BrandsPage.vue';
import { auth, fetchUser } from '../stores/auth.js';
import ProductsPage from '../pages/admin/ProductsPage.vue';
import ProductFormPage from '../pages/admin/ProductFormPage.vue';
import BannersPage from '../pages/admin/BannersPage.vue';
import StoresPage from '../pages/admin/StoresPage.vue';
import { track } from '../lib/track.js';
import StatisticsPage from '../pages/admin/StatisticsPage.vue';
import BenefitsPage from '../pages/admin/BenefitsPage.vue';
import PaymentsPage from '../pages/admin/PaymentsPage.vue';
import PriceCategoriesPage from '../pages/admin/PriceCategoriesPage.vue';
import ProductsPage from '../pages/ProductsPage.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: HomePage },
        { path: '/product/:slug', name: 'product', component: ProductDetail },
        { path: '/login', name: 'login', component: LoginPage, meta: { guestOnly: true, hideChrome: true } },
        {
            path: '/admin',
            component: AdminLayout,
            meta: { requiresAuth: true, hideChrome: true },
            children: [
                { path: '', name: 'admin', component: DashboardPage, meta: { title: 'Dashboard' } },
                { path: 'brands', name: 'admin.brands', component: BrandsPage, meta: { title: 'Brand' } },
                { path: 'products', name: 'admin.products', component: ProductsPage, meta: { title: 'Produk' } },
                { path: 'products/create', name: 'admin.products.create', component: ProductFormPage, meta: { title: 'Tambah Produk' } },
                { path: 'products/:id/edit', name: 'admin.products.edit', component: ProductFormPage, meta: { title: 'Edit Produk' } },
                { path: 'banners', name: 'admin.banners', component: BannersPage, meta: { title: 'Banner' } },
                { path: 'stores', name: 'admin.stores', component: StoresPage, meta: { title: 'Toko' } },
                { path: 'statistics', name: 'admin.statistics', component: StatisticsPage, meta: { title: 'Statistik' } },
                { path: 'benefits', name: 'admin.benefits', component: BenefitsPage, meta: { title: 'Benefit' } },
                { path: 'payments', name: 'admin.payments', component: PaymentsPage, meta: { title: 'Metode Pembayaran' } },
                { path: 'price-categories', name: 'admin.price-categories', component: PriceCategoriesPage, meta: { title: 'Kategori Harga' } },
                { path: '/products', name: 'products', component: ProductsPage },
            ],
        },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
    scrollBehavior(to, from, saved) {
        if (saved) return saved;

        if (to.hash) {
            const header = document.querySelector('header');
            const offset = (header?.offsetHeight ?? 80) + 16;
            return { el: to.hash, top: offset, behavior: 'smooth' };
        }

        return { top: 0 };
    },
});

// Guard ini hanya untuk UX. Proteksi sebenarnya ada di middleware `auth` di server.
router.beforeEach(async (to) => {
    if (!to.meta.requiresAuth && !to.meta.guestOnly) return true;

    await fetchUser();

    if (to.meta.requiresAuth && !auth.user) {
        return { name: 'login', query: { redirect: to.fullPath } };
    }
    if (to.meta.guestOnly && auth.user) {
        return { name: 'admin' };
    }

    return true;
});

// catat page view (lewati admin/login & perubahan hash/query saja)
router.afterEach((to, from) => {
    if (to.meta.hideChrome) return;
    if (from.matched.length && to.path === from.path) return;
    track('pageview', { path: to.path });
});

export default router;