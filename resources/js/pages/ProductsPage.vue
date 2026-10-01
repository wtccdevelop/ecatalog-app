<template>
    <div class="bg-gray-100 dark:bg-neutral-700 min-h-[70vh]">
        <!-- Header -->
        <div class="bg-gradient-to-b from-blue-50 to-gray-100 dark:from-neutral-800 dark:to-neutral-700">
            <div class="max-w-[96rem] mx-auto px-4 sm:px-6 lg:px-16 pt-8 pb-6">
                <nav class="mb-3 flex items-center gap-1.5 text-xs text-gray-500 dark:text-neutral-400">
                    <RouterLink to="/" class="hover:text-blue-600">Beranda</RouterLink>
                    <span>/</span>
                    <span class="font-semibold text-gray-800 dark:text-white">Semua Produk</span>
                </nav>
                <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">Semua Produk</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-neutral-400">
                    Temukan smartphone terbaik sesuai brand dan budgetmu.
                </p>
            </div>
        </div>

        <div class="max-w-[96rem] mx-auto px-4 sm:px-6 lg:px-16 pb-12">
            <div class="grid lg:grid-cols-[17rem_1fr] gap-6 items-start">
                <!-- Sidebar (desktop) -->
                <aside class="hidden lg:block sticky top-24 bg-white dark:bg-neutral-800 rounded-2xl shadow-sm ring-1 ring-black/5 dark:ring-white/5 p-5">
                    <ProductFilters
                        :brands="brands"
                        :categories="categories"
                        :brand="brand"
                        :price="price"
                        @select="setQuery"
                        @reset="setQuery({ brand: '', price: '' })"
                    />
                </aside>

                <!-- Konten -->
                <main class="min-w-0">
                    <!-- Toolbar -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-4">
                        <div class="relative flex-1 min-w-[12rem]">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
                            </svg>
                            <input
                                v-model="keyword"
                                type="text"
                                placeholder="Cari di daftar produk..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-full border-0 bg-white text-sm shadow-sm ring-1 ring-black/5 focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:text-white dark:ring-white/10"
                            />
                        </div>

                        <select
                            :value="sort"
                            @change="setQuery({ sort: $event.target.value })"
                            class="py-2.5 pl-4 pr-9 rounded-full border-0 bg-white text-sm shadow-sm ring-1 ring-black/5 focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:text-white dark:ring-white/10"
                        >
                            <option value="default">Urutan: Rekomendasi</option>
                            <option value="price_asc">Harga: Terendah</option>
                            <option value="price_desc">Harga: Tertinggi</option>
                            <option value="name">Nama: A–Z</option>
                        </select>

                        <button
                            type="button"
                            @click="showFilter = true"
                            class="lg:hidden inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-sm"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                            </svg>
                            Filter
                            <span v-if="activeCount" class="ml-0.5 size-5 inline-flex items-center justify-center rounded-full bg-white text-blue-600 text-[10px] font-extrabold">{{ activeCount }}</span>
                        </button>
                    </div>

                    <!-- Filter aktif + jumlah hasil -->
                    <div class="flex flex-wrap items-center gap-2 mb-4 text-xs">
                        <span class="text-gray-500 dark:text-neutral-400">
                            <b class="text-gray-900 dark:text-white">{{ meta.total.toLocaleString('id-ID') }}</b> produk ditemukan
                        </span>

                        <button v-if="activeBrand" type="button" @click="setQuery({ brand: '' })" :class="tag">
                            {{ activeBrand.name }} <span aria-hidden="true">✕</span>
                        </button>
                        <button v-if="activeCategory" type="button" @click="setQuery({ price: '' })" :class="tag">
                            {{ activeCategory.name }} <span aria-hidden="true">✕</span>
                        </button>
                        <button v-if="route.query.q" type="button" @click="setQuery({ q: '' })" :class="tag">
                            “{{ route.query.q }}” <span aria-hidden="true">✕</span>
                        </button>
                    </div>

                    <!-- Skeleton -->
                    <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                        <div v-for="n in 8" :key="n" class="bg-white dark:bg-neutral-800 rounded-2xl ring-1 ring-black/5 dark:ring-white/5 p-3 animate-pulse">
                            <div class="aspect-square rounded-xl bg-gray-100 dark:bg-neutral-700"></div>
                            <div class="mt-3 h-3 w-3/4 rounded bg-gray-100 dark:bg-neutral-700"></div>
                            <div class="mt-2 h-3 w-1/2 rounded bg-gray-100 dark:bg-neutral-700"></div>
                        </div>
                    </div>

                    <!-- Error -->
                    <div v-else-if="failed" class="bg-white dark:bg-neutral-800 rounded-2xl ring-1 ring-black/5 dark:ring-white/5 py-16 text-center">
                        <p class="text-sm text-gray-600 dark:text-neutral-300 mb-3">Gagal memuat produk.</p>
                        <button type="button" @click="load" class="px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Coba lagi</button>
                    </div>

                    <!-- Kosong -->
                    <div v-else-if="!products.length" class="bg-white dark:bg-neutral-800 rounded-2xl ring-1 ring-black/5 dark:ring-white/5 py-16 px-4 text-center">
                        <div class="mx-auto size-14 rounded-full bg-blue-50 dark:bg-neutral-700 flex items-center justify-center mb-3">
                            <svg class="size-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
                            </svg>
                        </div>
                        <h2 class="font-bold text-gray-900 dark:text-white">Produk tidak ditemukan</h2>
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">Coba ubah atau hapus filter yang dipilih.</p>
                        <button
                            type="button"
                            @click="resetAll"
                            class="mt-4 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold hover:bg-blue-100 dark:bg-neutral-700 dark:text-blue-300"
                        >
                            Reset
                        </button>
                    </div>

                    <!-- Grid produk -->
                    <div v-else class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                        <RouterLink
                            v-for="p in products"
                            :key="p.id"
                            :to="`/product/${p.slug}`"
                            class="group bg-white dark:bg-neutral-800 rounded-2xl ring-1 ring-black/5 dark:ring-white/5 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all duration-300 relative"
                        >
                            <EventDecoration />
                            <div class="bg-gray-50 dark:bg-neutral-700/60 p-3">
                                <img
                                    :src="p.image"
                                    :alt="p.name"
                                    loading="lazy"
                                    class="w-full aspect-square object-contain group-hover:scale-105 transition-transform duration-500"
                                />
                            </div>
                            <div class="p-3 lg:p-4">
                                <span class="inline-block px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 dark:bg-neutral-700 dark:text-blue-300 text-[9px] lg:text-[10px] font-bold uppercase tracking-wide">
                                    {{ p.brand }}
                                </span>
                                <h3 class="mt-1.5 text-xs lg:text-sm font-bold text-gray-800 dark:text-white line-clamp-2 min-h-[2.4em]">
                                    {{ p.name }}
                                </h3>
                                <p class="mt-1.5 text-[10px] lg:text-xs text-gray-500 dark:text-neutral-400">Mulai dari</p>
                                <p class="text-sm lg:text-base font-extrabold text-green-600 dark:text-green-400">{{ formatRupiah(p.price) }}</p>
                                <p class="mt-1 text-[10px] lg:text-xs text-gray-500 dark:text-neutral-400">
                                    Atau <span class="font-semibold text-gray-700 dark:text-neutral-200">{{ formatRupiah(monthly24(p.price)) }}/bln</span>
                                </p>
                            </div>
                        </RouterLink>
                    </div>

                    <!-- Pagination -->
                    <nav v-if="!loading && meta.last_page > 1" class="mt-8 flex items-center justify-center gap-1.5" aria-label="Halaman">
                        <button type="button" :disabled="page <= 1" @click="goPage(page - 1)" :class="pageBtn(false)" aria-label="Sebelumnya">‹</button>
                        <button
                            v-for="n in pages"
                            :key="n"
                            type="button"
                            @click="goPage(n)"
                            :class="pageBtn(n === page)"
                        >
                            {{ n }}
                        </button>
                        <button type="button" :disabled="page >= meta.last_page" @click="goPage(page + 1)" :class="pageBtn(false)" aria-label="Berikutnya">›</button>
                    </nav>
                </main>
            </div>
        </div>

        <!-- Drawer filter (mobile) -->
        <Teleport to="body">
            <div v-if="showFilter" class="fixed inset-0 z-80 flex items-end bg-black/50 lg:hidden" @click.self="showFilter = false">
                <div class="w-full max-h-[85vh] overflow-y-auto bg-white dark:bg-neutral-800 rounded-t-3xl shadow-2xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-900 dark:text-white">Filter Produk</h2>
                        <button type="button" @click="showFilter = false" class="size-8 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-white" aria-label="Tutup">✕</button>
                    </div>
                    <ProductFilters
                        :brands="brands"
                        :categories="categories"
                        :brand="brand"
                        :price="price"
                        @select="setQuery"
                        @reset="setQuery({ brand: '', price: '' })"
                    />
                    <button
                        type="button"
                        @click="showFilter = false"
                        class="mt-5 w-full py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700"
                    >
                        Lihat {{ meta.total.toLocaleString('id-ID') }} produk
                    </button>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from '../lib/api.js';
import { formatRupiah, monthly24 } from '../lib/format.js';
import { catalog, loadHome } from '../stores/catalog.js';
import ProductFilters from '../components/ProductFilters.vue';
import EventDecoration from '../components/EventDecoration.vue';

const tag = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 dark:bg-neutral-800 dark:text-blue-300 ring-1 ring-blue-100 dark:ring-neutral-600 font-semibold hover:bg-blue-100 transition-colors';
const pageBtn = (active) => [
    'min-w-9 h-9 px-3 rounded-full text-sm font-semibold transition-colors disabled:opacity-40 disabled:cursor-not-allowed',
    active
        ? 'bg-blue-600 text-white shadow-sm'
        : 'bg-white text-gray-700 ring-1 ring-black/5 hover:bg-blue-50 dark:bg-neutral-800 dark:text-white dark:ring-white/10 dark:hover:bg-neutral-600',
];

const route = useRoute();
const router = useRouter();

const products = ref([]);
const meta = ref({ current_page: 1, last_page: 1, total: 0 });
const loading = ref(true);
const failed = ref(false);
const showFilter = ref(false);

const brand = computed(() => String(route.query.brand ?? ''));
const price = computed(() => String(route.query.price ?? ''));
const sort = computed(() => String(route.query.sort ?? 'default'));
const page = computed(() => Math.max(1, Number(route.query.page) || 1));

const brands = computed(() => catalog.home?.brands ?? []);
const categories = computed(() => catalog.home?.price_categories ?? []);
const activeBrand = computed(() => brands.value.find((b) => b.slug === brand.value));
const activeCategory = computed(() => categories.value.find((c) => String(c.id) === price.value));
const activeCount = computed(() => (brand.value ? 1 : 0) + (price.value ? 1 : 0));

const pages = computed(() => {
    const last = meta.value.last_page;
    const start = Math.max(1, Math.min(page.value - 2, last - 4));
    const end = Math.min(last, start + 4);
    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

// ---------- URL <-> filter ----------
function setQuery(patch) {
    const next = { ...route.query, ...patch };
    if (!('page' in patch)) delete next.page;

    for (const k of Object.keys(next)) {
        if (next[k] === '' || next[k] == null || next[k] === 'default') delete next[k];
    }
    router.push({ path: '/products', query: next });
}

const goPage = (n) => setQuery({ page: n > 1 ? String(n) : '' });
const resetAll = () => { keyword.value = ''; router.push({ path: '/products' }); };

// pencarian: tunggu pengguna berhenti mengetik
const keyword = ref(String(route.query.q ?? ''));
let kwTimer = null;

watch(keyword, (v) => {
    clearTimeout(kwTimer);
    kwTimer = setTimeout(() => {
        if (v.trim() !== String(route.query.q ?? '')) setQuery({ q: v.trim() });
    }, 400);
});
watch(() => route.query.q, (v) => {
    if (route.name === 'products' && String(v ?? '') !== keyword.value.trim()) keyword.value = String(v ?? '');
});

// ---------- Ambil data ----------
let requestId = 0;

async function load() {
    if (route.name !== 'products') return;

    const id = ++requestId;
    loading.value = true;
    failed.value = false;

    const params = new URLSearchParams();
    for (const k of ['brand', 'price', 'q', 'sort', 'page']) {
        if (route.query[k]) params.set(k, route.query[k]);
    }

    try {
        const d = await api(`/products?${params}`);
        if (id !== requestId) return;
        products.value = d.data;
        meta.value = d.meta;
    } catch (e) {
        if (id !== requestId) return;
        failed.value = true;
        products.value = [];
    } finally {
        if (id === requestId) loading.value = false;
    }
}

watch(() => route.query, load, { immediate: true });

onMounted(() => {
    document.title = 'WTC Cell - Semua Produk';
    loadHome().catch(() => {}); // daftar brand & kategori harga untuk filter
});

onUnmounted(() => clearTimeout(kwTimer));
</script>