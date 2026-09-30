<template>
    <div class="space-y-5">
        <!-- Filter -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex rounded-xl bg-white dark:bg-neutral-800 p-1 shadow-sm ring-1 ring-black/5 dark:ring-white/5 text-xs">
                <button
                    v-for="p in presets" :key="p"
                    type="button" @click="setPreset(p)"
                    class="px-3 py-1.5 rounded-lg font-semibold transition-colors"
                    :class="preset === p ? 'bg-indigo-500 text-white' : 'text-gray-600 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700'"
                >
                    {{ p }} hari
                </button>
            </div>

            <div class="flex items-center gap-2 text-xs">
                <input v-model="range.from" type="date" :max="range.to" @change="custom" :class="dateInput" />
                <span class="text-gray-400">–</span>
                <input v-model="range.to" type="date" :min="range.from" @change="custom" :class="dateInput" />
            </div>

            <button
                type="button" @click="load()"
                class="ml-auto px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-neutral-800 dark:text-indigo-300 dark:hover:bg-neutral-700"
            >
                ↻ Muat ulang
            </button>
        </div>

        <p v-if="loading" class="text-sm text-gray-500 dark:text-neutral-400">Memuat...</p>
        <p v-else-if="!data" class="text-sm text-red-600">Gagal memuat statistik.</p>

        <template v-else>
            <!-- Kartu ringkasan -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <StatCard label="Pengunjung Unik" :value="num(data.summary.visitors)" hint="1 IP = 1 pengunjung / hari" tone="indigo" />
                <StatCard label="Pengunjung Baru" :value="num(data.summary.new_visitors)" hint="Pertama kali berkunjung" tone="emerald" />
                <StatCard label="Pengunjung Kembali" :value="num(data.summary.returning_visitors)" hint="Pernah berkunjung sebelumnya" tone="violet" />
                <StatCard label="Online Sekarang" :value="num(data.summary.online_now)" hint="5 menit terakhir" tone="rose" />
                <StatCard label="Page View" :value="num(data.summary.page_views)" :hint="`${data.summary.avg_pages} halaman / pengunjung`" tone="sky" />
                <StatCard label="Klik WhatsApp" :value="num(data.summary.wa_clicks)" hint="Calon pembeli menghubungi" tone="emerald" />
                <StatCard label="Lihat Cicilan" :value="num(data.summary.installment_views)" hint="Membuka simulasi cicilan" tone="amber" />
                <StatCard label="Pencarian" :value="num(data.summary.searches)" hint="Kata kunci yang diketik" tone="sky" />
            </div>

            <!-- Tren harian -->
            <ChartCard title="Tren Harian" :subtitle="`${fmtDay(data.range.from)} – ${fmtDay(data.range.to)}`" type="daily">
                <AreaChart :labels="dailyLabels" :series="dailySeries" />
            </ChartCard>

            <!-- Jam ramai -->
            <div class="grid lg:grid-cols-2 gap-5">
                <ChartCard title="Jam Kunjungan Ramai" subtitle="Page view per jam (WIB)" type="hours">
                    <ColumnChart :labels="hourLabels" :values="data.hours" />
                </ChartCard>
                <ChartCard title="Peta Panas Hari × Jam" subtitle="Semakin pekat, semakin ramai">
                    <Heatmap :matrix="data.heatmap" />
                </ChartCard>
            </div>

            <!-- Perangkat & sumber -->
            <div class="grid lg:grid-cols-2 gap-5">
                <ChartCard title="Tipe Perangkat" type="device_types">
                    <DonutChart :items="toItems(data.device_types)" />
                </ChartCard>
                <ChartCard title="Sumber Trafik" subtitle="Dari mana pengunjung datang" type="sources">
                    <DonutChart :items="toItems(data.sources)" />
                </ChartCard>
            </div>

            <div class="grid lg:grid-cols-3 gap-5">
                <ChartCard title="Merek HP Pengunjung" type="brands">
                    <RankList :items="toItems(data.brands)" bar="bg-violet-300 dark:bg-violet-400" />
                </ChartCard>
                <ChartCard title="Sistem Operasi" type="os">
                    <DonutChart :items="toItems(data.os)" />
                </ChartCard>
                <ChartCard title="Browser" type="browsers">
                    <DonutChart :items="toItems(data.browsers)" />
                </ChartCard>
            </div>

            <!-- Halaman, produk, kata kunci -->
            <div class="grid lg:grid-cols-3 gap-5">
                <ChartCard title="Halaman Terpopuler" type="pages">
                    <RankList :items="pageItems" bar="bg-sky-300 dark:bg-sky-400" />
                </ChartCard>
                <ChartCard title="Produk Terpopuler" subtitle="Dilihat & klik WhatsApp" type="products">
                    <RankList :items="productItems" bar="bg-emerald-300 dark:bg-emerald-400" />
                </ChartCard>
                <ChartCard title="Kata Kunci Pencarian" type="keywords">
                    <RankList :items="keywordItems" bar="bg-amber-300 dark:bg-amber-400" />
                </ChartCard>
            </div>

            <!-- Pengunjung terbaru -->
            <ChartCard title="Pengunjung Terbaru" subtitle="50 kunjungan terakhir (tanpa IP)" type="visitors">
                <div class="overflow-x-auto -mx-1">
                    <table class="w-full text-xs text-left">
                        <thead class="text-[11px] uppercase text-gray-400 dark:text-neutral-500">
                            <tr>
                                <th class="px-2 py-2">Terakhir</th>
                                <th class="px-2 py-2">Perangkat</th>
                                <th class="px-2 py-2">OS</th>
                                <th class="px-2 py-2">Browser</th>
                                <th class="px-2 py-2">Sumber</th>
                                <th class="px-2 py-2 text-right">Halaman</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 text-gray-700 dark:text-neutral-200">
                            <tr v-if="!data.recent.length">
                                <td colspan="6" class="px-2 py-6 text-center text-gray-400">Belum ada data.</td>
                            </tr>
                            <tr v-for="(r, i) in data.recent" :key="i">
                                <td class="px-2 py-2 whitespace-nowrap">{{ r.last_visit_at?.slice(0, 16) }}</td>
                                <td class="px-2 py-2">
                                    <span class="mr-1.5 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300">{{ r.device_type }}</span>
                                    {{ [r.device_brand, r.device_model].filter(Boolean).join(' ') || '-' }}
                                </td>
                                <td class="px-2 py-2">{{ r.os || '-' }}</td>
                                <td class="px-2 py-2">{{ r.browser || '-' }}</td>
                                <td class="px-2 py-2">{{ r.referrer_source }}</td>
                                <td class="px-2 py-2 text-right font-semibold">{{ r.page_views_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </ChartCard>
        </template>
    </div>
</template>

<script setup>
import { ref, reactive, computed, provide, onMounted, onUnmounted } from 'vue';
import { api } from '../../lib/api.js';
import { toast } from '../../stores/toast.js';
import StatCard from '../../components/admin/charts/StatCard.vue';
import ChartCard from '../../components/admin/charts/ChartCard.vue';
import AreaChart from '../../components/admin/charts/AreaChart.vue';
import ColumnChart from '../../components/admin/charts/ColumnChart.vue';
import DonutChart from '../../components/admin/charts/DonutChart.vue';
import RankList from '../../components/admin/charts/RankList.vue';
import Heatmap from '../../components/admin/charts/Heatmap.vue';

const dateInput = 'px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-indigo-400 focus:ring-indigo-400';
const presets = [7, 30, 90];

const data = ref(null);
const loading = ref(true);
const preset = ref(30);
const range = reactive({ from: '', to: '' });

const ymd = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
const num = (n) => Number(n).toLocaleString('id-ID');
const fmtDay = (s) => new Date(`${s}T00:00:00`).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });

const dailyLabels = computed(() => data.value.daily.map((d) => fmtDay(d.date)));
const dailySeries = computed(() => [
    { name: 'Pengunjung', color: '#818cf8', data: data.value.daily.map((d) => d.visitors) },
    { name: 'Page View', color: '#38bdf8', data: data.value.daily.map((d) => d.page_views) },
    { name: 'Klik WhatsApp', color: '#34d399', data: data.value.daily.map((d) => d.wa_clicks) },
]);
const hourLabels = Array.from({ length: 24 }, (_, h) => String(h).padStart(2, '0'));

const toItems = (list) => list.map((i) => ({ label: i.label, value: i.total }));
const pageItems = computed(() => data.value.pages.map((p) => ({ label: p.path, value: p.views, sub: `${p.visitors} pengunjung` })));
const productItems = computed(() => data.value.products.map((p) => ({ label: p.name, value: p.views, sub: `${p.wa_clicks} klik WA` })));
const keywordItems = computed(() => data.value.keywords.map((k) => ({ label: k.keyword, value: k.total })));

function setPreset(days) {
    preset.value = days;
    const to = new Date();
    const from = new Date();
    from.setDate(to.getDate() - (days - 1));
    range.from = ymd(from);
    range.to = ymd(to);
    load();
}

function custom() {
    if (!range.from || !range.to) return;
    preset.value = 0;
    load();
}

async function load(silent = false) {
    if (!silent) loading.value = true;
    try {
        data.value = await api(`/admin/statistics?from=${range.from}&to=${range.to}`);
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else if (!silent) {
            data.value = null;
            toast.error(e.data?.message ?? 'Gagal memuat statistik.');
        }
    } finally {
        loading.value = false;
    }
}

// unduh lewat fetch agar sesi habis tetap diarahkan ke login
async function download(type, format) {
    try {
        const res = await fetch(`/api/admin/statistics/export/${type}?format=${format}&from=${range.from}&to=${range.to}`, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });
        if (res.status === 401) {
            window.location.href = '/login';
            return;
        }
        if (!res.ok) throw new Error();

        const url = URL.createObjectURL(await res.blob());
        const a = document.createElement('a');
        a.href = url;
        a.download = `statistik-${type}-${range.from}_${range.to}.${format}`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    } catch (e) {
        toast.error('Gagal mengunduh data.');
    }
}

provide('downloadStat', download);

let poll = null;

onMounted(() => {
    setPreset(30);
    poll = setInterval(() => load(true), 60000); // segarkan tiap 1 menit
});

onUnmounted(() => clearInterval(poll));
</script>