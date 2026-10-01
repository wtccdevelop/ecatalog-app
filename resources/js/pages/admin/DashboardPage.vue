<template>
    <div class="space-y-5">
        <p v-if="loading" class="text-sm text-gray-500 dark:text-neutral-400">Memuat...</p>
        <p v-else-if="!data" class="text-sm text-red-600">Gagal memuat dashboard.</p>

        <template v-else>
            <!-- Aksi cepat -->
            <div class="flex flex-wrap gap-2">
                <RouterLink v-for="a in actions" :key="a.label" :to="a.to" :target="a.blank ? '_blank' : null" :class="a.primary ? btnPrimary : btnGhost">
                    {{ a.label }}
                </RouterLink>
            </div>

            <!-- Kartu: hari ini vs kemarin -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div v-for="c in cards" :key="c.label" :class="card + ' p-4'">
                    <div class="flex items-center gap-2">
                        <span class="size-2 rounded-full" :class="c.dot"></span>
                        <p class="text-xs text-gray-500 dark:text-neutral-400">{{ c.label }}</p>
                    </div>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900 dark:text-white tabular-nums">{{ num(c.value) }}</p>
                    <p v-if="c.delta !== undefined" class="mt-0.5 text-[11px] font-semibold" :class="c.delta.cls">
                        {{ c.delta.text }} <span class="font-normal text-gray-400 dark:text-neutral-500">dari kemarin</span>
                    </p>
                    <p v-else class="mt-0.5 text-[11px] text-gray-400 dark:text-neutral-500">5 menit terakhir</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-5">
                <!-- Perlu perhatian -->
                <section :class="card + ' p-5 lg:col-span-2'">
                    <header class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Perlu Perhatian</h2>
                        <span v-if="data.attention.length" class="text-[11px] text-gray-500 dark:text-neutral-400">{{ data.attention.length }} item</span>
                    </header>

                    <div v-if="!data.attention.length" class="py-8 text-center">
                        <p class="text-2xl">✅</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">Semua beres</p>
                        <p class="text-xs text-gray-500 dark:text-neutral-400">Tidak ada yang perlu ditindaklanjuti.</p>
                    </div>

                    <ul v-else class="divide-y divide-gray-100 dark:divide-neutral-700">
                        <li v-for="(it, i) in data.attention" :key="i">
                            <RouterLink :to="it.to" class="flex items-start gap-3 py-2.5 -mx-2 px-2 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                                <span class="mt-1.5 size-2 rounded-full shrink-0" :class="levelDot[it.level]"></span>
                                <span class="flex-1 text-sm text-gray-700 dark:text-neutral-200">{{ it.text }}</span>
                                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 whitespace-nowrap">Buka →</span>
                            </RouterLink>
                        </li>
                    </ul>
                </section>

                <!-- Ringkasan konten -->
                <section :class="card + ' p-5'">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Konten Katalog</h2>
                    <ul class="space-y-2">
                        <li v-for="c in content" :key="c.label">
                            <RouterLink :to="c.to" class="flex items-center justify-between text-sm px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-700">
                                <span class="text-gray-600 dark:text-neutral-300">{{ c.label }}</span>
                                <b class="text-gray-900 dark:text-white tabular-nums">{{ c.value }}</b>
                            </RouterLink>
                        </li>
                    </ul>
                </section>
            </div>

            <div class="grid lg:grid-cols-2 gap-5">
                <section :class="card + ' p-5'">
                    <header class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Produk Terpopuler</h2>
                            <p class="text-[11px] text-gray-500 dark:text-neutral-400">7 hari terakhir</p>
                        </div>
                        <RouterLink to="/admin/statistics" class="text-xs font-semibold text-blue-600 dark:text-blue-400">Selengkapnya →</RouterLink>
                    </header>
                    <RankList :items="productItems" bar="bg-emerald-300 dark:bg-emerald-400" />
                </section>

                <section :class="card + ' p-5'">
                    <header class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Kata Kunci Pencarian</h2>
                            <p class="text-[11px] text-gray-500 dark:text-neutral-400">7 hari terakhir. Cek apakah HP yang dicari sudah ada di katalog.</p>
                        </div>
                        <RouterLink to="/admin/statistics" class="text-xs font-semibold text-blue-600 dark:text-blue-400">Selengkapnya →</RouterLink>
                    </header>
                    <RankList :items="keywordItems" bar="bg-amber-300 dark:bg-amber-400" />
                </section>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { api } from '../../lib/api.js';
import RankList from '../../components/admin/charts/RankList.vue';

const card = 'bg-white dark:bg-neutral-800 rounded-2xl shadow-sm ring-1 ring-black/5 dark:ring-white/5';
const btnPrimary = 'px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700';
const btnGhost = 'px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-700';

const levelDot = { danger: 'bg-rose-500', warning: 'bg-amber-400', info: 'bg-sky-400' };

const actions = [
    { label: '+ Tambah Produk', to: '/admin/products/create', primary: true },
    { label: '+ Banner', to: '/admin/banners' },
    { label: 'Statistik', to: '/admin/statistics' },
    { label: 'Lihat Website ↗', to: '/', blank: true },
];

const data = ref(null);
const loading = ref(true);

const num = (n) => Number(n).toLocaleString('id-ID');

function delta(now, prev) {
    const d = now - prev;
    if (d === 0) return { text: '± 0', cls: 'text-gray-400' };
    if (prev === 0) return { text: `▲ +${num(d)}`, cls: 'text-emerald-600 dark:text-emerald-400' };
    const pct = Math.round((Math.abs(d) / prev) * 100);
    return d > 0
        ? { text: `▲ ${pct}%`, cls: 'text-emerald-600 dark:text-emerald-400' }
        : { text: `▼ ${pct}%`, cls: 'text-rose-600 dark:text-rose-400' };
}

const cards = computed(() => {
    const t = data.value.today, y = data.value.yesterday;
    return [
        { label: 'Pengunjung Hari Ini', value: t.visitors, delta: delta(t.visitors, y.visitors), dot: 'bg-indigo-400' },
        { label: 'Page View', value: t.page_views, delta: delta(t.page_views, y.page_views), dot: 'bg-sky-400' },
        { label: 'Klik WhatsApp', value: t.wa_clicks, delta: delta(t.wa_clicks, y.wa_clicks), dot: 'bg-emerald-400' },
        { label: 'Lihat Cicilan', value: t.installments, delta: delta(t.installments, y.installments), dot: 'bg-amber-400' },
        { label: 'Online Sekarang', value: data.value.online_now, dot: 'bg-rose-400' },
    ];
});

const content = computed(() => {
    const c = data.value.counts;
    return [
        { label: 'Brand', value: c.brands, to: '/admin/brands' },
        { label: 'Produk', value: c.products, to: '/admin/products' },
        { label: 'Banner', value: c.banners, to: '/admin/banners' },
        { label: 'Toko', value: c.stores, to: '/admin/stores' },
    ];
});

const productItems = computed(() =>
    data.value.top_products.map((p) => ({ label: p.name, value: p.views, sub: `${p.wa_clicks} klik WA` }))
);
const keywordItems = computed(() =>
    data.value.top_keywords.map((k) => ({ label: k.keyword, value: k.total }))
);

onMounted(async () => {
    try {
        data.value = await api('/admin/dashboard');
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
    } finally {
        loading.value = false;
    }
});
</script>