<template>
    <div v-if="total" class="flex items-center gap-5">
        <div class="relative size-32 shrink-0">
            <svg viewBox="0 0 42 42" class="size-full -rotate-0">
                <circle cx="21" cy="21" r="15.9155" fill="none" stroke-width="4" class="stroke-gray-100 dark:stroke-neutral-700" />
                <circle
                    v-for="s in segs" :key="s.label"
                    cx="21" cy="21" r="15.9155" fill="none" stroke-width="4"
                    :stroke="s.color" :stroke-dasharray="`${s.len} ${100 - s.len}`" :stroke-dashoffset="s.offset"
                    stroke-linecap="round"
                />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-lg font-extrabold text-gray-900 dark:text-white leading-none">{{ total.toLocaleString('id-ID') }}</span>
                <span class="text-[10px] text-gray-500 dark:text-neutral-400 mt-0.5">{{ centerLabel }}</span>
            </div>
        </div>

        <ul class="flex-1 min-w-0 space-y-1.5 text-xs">
            <li v-for="s in segs" :key="s.label" class="flex items-center justify-between gap-2">
                <span class="flex items-center gap-2 min-w-0 text-gray-700 dark:text-neutral-200">
                    <span class="size-2.5 rounded-full shrink-0" :style="{ backgroundColor: s.color }"></span>
                    <span class="truncate">{{ s.label }}</span>
                </span>
                <span class="shrink-0 text-gray-500 dark:text-neutral-400">
                    <b class="text-gray-900 dark:text-white">{{ s.value.toLocaleString('id-ID') }}</b> · {{ s.pct.toFixed(0) }}%
                </span>
            </li>
        </ul>
    </div>
    <p v-else class="py-8 text-center text-xs text-gray-400 dark:text-neutral-500">Belum ada data.</p>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    items: { type: Array, default: () => [] }, // [{ label, value }]
    centerLabel: { type: String, default: 'Pengunjung' },
});

const PALETTE = ['#818cf8', '#38bdf8', '#34d399', '#fbbf24', '#fb7185', '#a78bfa', '#f472b6', '#2dd4bf', '#94a3b8', '#fb923c'];

const total = computed(() => props.items.reduce((s, i) => s + i.value, 0));

const segs = computed(() => {
    let acc = 0;
    return props.items.map((it, i) => {
        const pct = total.value ? (it.value / total.value) * 100 : 0;
        const seg = {
            ...it,
            pct,
            color: PALETTE[i % PALETTE.length],
            offset: 25 - acc,
            len: Math.max(0, pct - (props.items.length > 1 ? 1.2 : 0)),
        };
        acc += pct;
        return seg;
    });
});
</script>