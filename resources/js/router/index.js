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

export default router;