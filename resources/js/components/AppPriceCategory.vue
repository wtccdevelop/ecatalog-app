<template>
    <section class="px-3 pb-6 pt-2 sm:px-4 bg-white dark:bg-neutral-700">
        <!-- Header -->
        <div class="mb-5">
            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300">
                Budget
            </span>
            <h2 class="mt-2 text-base font-extrabold text-gray-900 sm:text-2xl dark:text-white">
                Cari sesuai budgetmu
            </h2>
            <div class="mt-2 h-1 w-14 rounded-full bg-linear-to-r from-emerald-400 to-sky-400"></div>
        </div>

        <div
            class="flex gap-3 overflow-x-auto snap-x snap-mandatory pb-2 lg:grid lg:grid-cols-8 lg:overflow-visible [&::-webkit-scrollbar]:hidden"
        >
            <RouterLink
                v-for="(cat, i) in categories"
                :key="cat.id"
                :to="{ path: '/products', query: { price: cat.id } }"
                class="group relative shrink-0 snap-start w-32 sm:w-40 lg:w-auto overflow-hidden rounded-3xl p-3.5 ring-1 ring-black/5 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl dark:ring-white/10"
                :class="tone(i).bg"
            >
                <!-- lingkaran dekoratif -->
                <div class="absolute -right-5 -top-5 size-16 rounded-full opacity-60 blur-sm transition-transform duration-500 group-hover:scale-150" :class="tone(i).orb"></div>

                <div class="relative">
                    <!-- <div class="flex size-9 items-center justify-center rounded-2xl bg-white/80 shadow-sm dark:bg-neutral-900/60" :class="tone(i).text">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="icon(cat)" />
                        </svg>
                    </div> -->
                    <p class="mt-3 text-xs font-extrabold text-gray-800 sm:text-sm dark:text-white">{{ cat.name }}</p>
                    <p class="mt-0.5 text-[10px] text-gray-500 dark:text-neutral-300">{{ range(cat) }}</p>
                </div>
            </RouterLink>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

const categories = computed(() => catalog.home?.price_categories ?? []);

// palet pastel bergantian
const TONES = [
    { bg: 'bg-sky-50 dark:bg-sky-500/10',         orb: 'bg-sky-300 dark:bg-sky-500/40',         text: 'text-sky-600 dark:text-sky-300' },
    // { bg: 'bg-emerald-50 dark:bg-emerald-500/10', orb: 'bg-emerald-300 dark:bg-emerald-500/40', text: 'text-emerald-600 dark:text-emerald-300' },
    // { bg: 'bg-violet-50 dark:bg-violet-500/10',   orb: 'bg-violet-300 dark:bg-violet-500/40',   text: 'text-violet-600 dark:text-violet-300' },
    // { bg: 'bg-amber-50 dark:bg-amber-500/10',     orb: 'bg-amber-300 dark:bg-amber-500/40',     text: 'text-amber-600 dark:text-amber-300' },
    // { bg: 'bg-rose-50 dark:bg-rose-500/10',       orb: 'bg-rose-300 dark:bg-rose-500/40',       text: 'text-rose-600 dark:text-rose-300' },
];
const tone = (i) => TONES[i % TONES.length];

// 1.000.000 -> "1 jt"
const jt = (n) => `${(n / 1_000_000).toLocaleString('id-ID', { maximumFractionDigits: 1 })} jt`;

function range(c) {
    if (c.min_price == null && c.max_price == null) return 'Pilihan spesial';
    if (c.max_price == null) return `Mulai ${jt(c.min_price)}`;
    if (c.min_price == null) return `Hingga ${jt(c.max_price)}`;
    return `${jt(c.min_price)} – ${jt(c.max_price + 1)}`;
}

// ikon: kategori non-harga (Flagship dll) pakai bintang, sisanya koin
const ICON_COIN = 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
const ICON_STAR = 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z';
const icon = (c) => (c.min_price == null && c.max_price == null ? ICON_STAR : ICON_COIN);
</script>