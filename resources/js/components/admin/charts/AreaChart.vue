<template>
    <div>
        <div class="flex flex-wrap gap-4 mb-3 text-xs text-gray-600 dark:text-neutral-300">
            <span v-for="s in series" :key="s.name" class="inline-flex items-center gap-1.5">
                <span class="size-2.5 rounded-full" :style="{ backgroundColor: s.color }"></span>{{ s.name }}
            </span>
        </div>

        <div class="relative w-full select-none" @mouseleave="hover = null">
            <svg :viewBox="`0 0 ${W} ${H}`" class="w-full h-auto">
                <defs>
                    <linearGradient v-for="(s, i) in series" :key="s.name" :id="`${uid}-${i}`" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" :stop-color="s.color" stop-opacity="0.28" />
                        <stop offset="100%" :stop-color="s.color" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <g class="text-gray-200 dark:text-neutral-700" stroke="currentColor" stroke-dasharray="3 5">
                    <line v-for="t in ticks" :key="t" :x1="PL" :x2="W - PR" :y1="y(t)" :y2="y(t)" />
                </g>
                <g class="fill-gray-400 dark:fill-neutral-500" font-size="11">
                    <text v-for="t in ticks" :key="`t${t}`" :x="PL - 8" :y="y(t) + 4" text-anchor="end">{{ t }}</text>
                    <text v-for="i in xLabelIdx" :key="`x${i}`" :x="x(i)" :y="H - 8" text-anchor="middle">{{ labels[i] }}</text>
                </g>

                <template v-for="(s, i) in series" :key="`p${s.name}`">
                    <path :d="areaPath(s.data)" :fill="`url(#${uid}-${i})`" />
                    <path :d="linePath(s.data)" fill="none" :stroke="s.color" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </template>

                <template v-if="hover !== null">
                    <line :x1="x(hover)" :x2="x(hover)" :y1="PT" :y2="H - PB" class="stroke-gray-300 dark:stroke-neutral-600" />
                    <circle v-for="s in series" :key="`c${s.name}`" :cx="x(hover)" :cy="y(s.data[hover])" r="4.5" fill="white" :stroke="s.color" stroke-width="2.5" />
                </template>

                <rect
                    v-for="(l, i) in labels" :key="`h${i}`"
                    :x="x(i) - step / 2" :y="PT" :width="step" :height="H - PT - PB"
                    fill="transparent" @mouseenter="hover = i" @touchstart.passive="hover = i"
                />
            </svg>

            <div
                v-if="hover !== null"
                class="pointer-events-none absolute top-0 z-10 -translate-x-1/2 rounded-xl bg-white/95 dark:bg-neutral-900/95 px-3 py-2 text-xs shadow-lg ring-1 ring-black/5 dark:ring-white/10"
                :style="{ left: tipLeft + '%' }"
            >
                <p class="font-semibold text-gray-800 dark:text-white mb-1">{{ labels[hover] }}</p>
                <p v-for="s in series" :key="s.name" class="flex items-center gap-1.5 text-gray-600 dark:text-neutral-300 whitespace-nowrap">
                    <span class="size-2 rounded-full" :style="{ backgroundColor: s.color }"></span>
                    {{ s.name }}: <b class="text-gray-900 dark:text-white">{{ s.data[hover].toLocaleString('id-ID') }}</b>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    labels: { type: Array, default: () => [] },
    series: { type: Array, default: () => [] }, // [{ name, color, data: [] }]
});

const W = 680, H = 260, PL = 40, PR = 16, PT = 16, PB = 32;
const uid = 'ac' + Math.random().toString(36).slice(2, 8);
const hover = ref(null);

const n = computed(() => props.labels.length);

const nice = computed(() => {
    const raw = Math.max(1, ...props.series.flatMap((s) => s.data));
    const p = 10 ** Math.floor(Math.log10(raw));
    const f = raw / p;
    return (f <= 1 ? 1 : f <= 2 ? 2 : f <= 5 ? 5 : 10) * p;
});

const ticks = computed(() => [...new Set([0, 1, 2, 3, 4].map((i) => Math.round((nice.value * i) / 4)))]);
const step = computed(() => (n.value > 1 ? (W - PL - PR) / (n.value - 1) : W - PL - PR));
const x = (i) => (n.value > 1 ? PL + i * step.value : PL + (W - PL - PR) / 2);
const y = (v) => PT + (H - PT - PB) * (1 - v / nice.value);
const tipLeft = computed(() => Math.min(85, Math.max(15, (x(hover.value ?? 0) / W) * 100)));

const xLabelIdx = computed(() => {
    const c = Math.min(7, n.value);
    if (c <= 1) return n.value ? [0] : [];
    return [...new Set(Array.from({ length: c }, (_, k) => Math.round((k * (n.value - 1)) / (c - 1))))];
});

function linePath(d) {
    return d
        .map((v, i) => {
            const px = x(i), py = y(v);
            if (!i) return `M${px},${py}`;
            const cx = (x(i - 1) + px) / 2;
            return `C${cx},${y(d[i - 1])} ${cx},${py} ${px},${py}`;
        })
        .join(' ');
}

const areaPath = (d) => (d.length ? `${linePath(d)} L${x(d.length - 1)},${y(0)} L${x(0)},${y(0)} Z` : '');
</script>