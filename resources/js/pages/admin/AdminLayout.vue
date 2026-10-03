<template>
    <div class="min-h-screen bg-gray-100 dark:bg-neutral-900">
        <!-- overlay mobile -->
        <div v-if="open" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="open = false"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 text-white flex flex-col transition-transform duration-300"
            :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="h-16 flex items-center px-5 border-b border-white/10">
                <span class="font-audiowide text-lg tracking-wide">WTC</span>
                <span class="ml-1.5 text-lg">Cell</span>
                <span class="ml-2 text-[10px] bg-white/20 rounded px-1.5 py-0.5 font-semibold">ADMIN</span>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <template v-for="item in menu" :key="item.label">
                    <RouterLink
                        v-if="!item.soon"
                        :to="item.to"
                        :exact-active-class="item.exact ? 'bg-white/15 text-white' : ''"
                        :active-class="item.exact ? '' : 'bg-white/15 text-white'"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-colors"
                    >
                        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                        </svg>
                        {{ item.label }}
                    </RouterLink>

                    <div v-else class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/40 cursor-not-allowed">
                        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                        </svg>
                        {{ item.label }}
                        <span class="ml-auto text-[9px] bg-white/10 rounded px-1.5 py-0.5">Segera</span>
                    </div>
                </template>
            </nav>

            <div class="p-3 border-t border-white/10">
                <RouterLink to="/" target="_blank" class="block px-3 py-2 rounded-lg text-xs text-white/70 hover:bg-white/10">
                    Lihat website →
                </RouterLink>
            </div>
        </aside>

        <!-- Konten -->
        <div class="lg:pl-64">
            <header class="sticky top-0 z-30 h-16 flex items-center justify-between gap-3 px-4 sm:px-6 bg-white dark:bg-neutral-800 shadow-sm">
                <button
                    type="button"
                    class="lg:hidden size-9 flex items-center justify-center rounded-lg border border-gray-200 dark:border-neutral-600 dark:text-white"
                    aria-label="Menu"
                    @click="open = true"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <h1 class="font-bold text-gray-900 dark:text-white truncate">{{ route.meta.title ?? 'Admin' }}</h1>

                <div class="flex items-center gap-3 ml-auto">
                    <span class="hidden sm:block text-xs text-gray-500 dark:text-neutral-400">{{ auth.user?.email }}</span>
                    <button
                        type="button"
                        @click="doLogout"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 text-gray-800 hover:bg-gray-50 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-700"
                    >
                        Keluar
                    </button>
                </div>
            </header>

            <main class="p-4 sm:p-6">
                <RouterView />
            </main>
        </div>

        <AdminToast />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { auth, logout } from '../../stores/auth.js';
import AdminToast from '../../components/admin/AdminToast.vue';

const route = useRoute();
const router = useRouter();
const open = ref(false);

const I = {
    home:    'm2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
    tag:     'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3ZM6 6h.008v.008H6V6Z',
    phone:   'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3',
    photo:   'm2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V4.5a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v15a1.5 1.5 0 0 0 1.5 1.5Z',
    list:    'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5',
    chart:   'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
    store:   'M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72m-13.5 8.65h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .415.336.75.75.75Z',
    gift:    'M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
    card:    'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z',
    money:   'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z',
    share:   'M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z',
    cog:     'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',

};

// soon: true = belum dibuat (jadi false tiap tahap selesai)
const menu = [
    { label: 'Dashboard',        to: '/admin',                 icon: I.home,  exact: true },
    { label: 'Brand',            to: '/admin/brands',          icon: I.tag},
    { label: 'Produk',           to: '/admin/products',        icon: I.phone},
    { label: 'Banner',            to: '/admin/banners',          icon: I.photo },
    { label: 'Toko',              to: '/admin/stores',           icon: I.store },
    { label: 'Benefit',           to: '/admin/benefits',         icon: I.gift },
    { label: 'Metode Pembayaran', to: '/admin/payments',         icon: I.card },
    { label: 'Kategori Harga',    to: '/admin/price-categories', icon: I.money },
    { label: 'Sosial Media',      to: '/admin/socials',          icon: I.share },
    { label: 'Pengaturan',        to: '/admin/settings',         icon: I.cog },
    { label: 'Tema Event',       to: '/admin/event-themes',        icon: I.list },
    { label: 'Statistik',        to: '/admin/statistics',      icon: I.chart},
];

watch(() => route.path, () => { open.value = false; });

async function doLogout() {
    await logout();
    router.replace('/login');
}
</script>