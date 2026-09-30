<template>
    <div class="relative w-full select-none" @mouseleave="hover = null">
        <svg :viewBox="`0 0 ${W} ${H}`" class="w-full h-auto">
            <g class="text-gray-200 dark:text-neutral-700" stroke="currentColor" stroke-dasharray="3 5">
                <line v-for="t in ticks" :key="t" :x1="PL" :x2="W - PR" :y1="y(t)" :y2="y(t)" />
            </g>
            <g class="fill-gray-400 dark:fill-neutral-500" font-size="11">
                <text v-for="t in ticks" :key="`t${t}`" :x="PL - 8" :y="y(t) + 4" text-anchor="end">{{ t }}</text>
                <template v-for="(l, i) in labels" :key="`x${i}`">
                    <text v-if="i % 2 === 0" :x="bx(i) + bw / 2" :y="H - 8" text-anchor="middle">{{ l }}</text>
                </template>
            </g>

            <rect
                v-for="(v, i) in values" :key="i"
                :x="bx(i)" :y="y(v)" :width="bw" :height="Math.max(0, H - PB - y(v))" rx="5"
                :fill="color"
                :opacity="hover === null ? (i === peak && v > 0 ? 1 : 0.55) : hover === i ? 1 : 0.3"
                class="transition-opacity"
            />
            <rect
                v-for="(v, i) in values" :key="`h${i}`"
                :x="PL + i * slot" :y="PT" :width="slot" :height="H - PT - PB"
                fill="transparent" @mouseenter="hover = i" @touchstart.passive="hover = i"
            />
        </svg>

        <div
            v-if="hover !== null"
            class="pointer-events-none absolute top-0 z-10 -translate-x-1/2 rounded-xl bg-white/95 dark:bg-neutral-900/95 px-3 py-2 text-xs shadow-lg ring-1 ring-black/5 dark:ring-white/10 whitespace-nowrap"
            :style="{ left: tipLeft + '%' }"
        >
            <b class="text-gray-900 dark:text-white">{{ values[hover].toLocaleString('id-ID') }}</b>
            <span class="text-gray-500 dark:text-neutral-400"> page view · {{ labels[hover] }}:00</span>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    labels: { type: Array, default: () => [] },
    values: { type: Array, default: () => [] },
    color: { type: String, default: '#818cf8' },
});

const W = 680, H = 240, PL = 40, PR = 16, PT = 16, PB = 32;
const hover = ref(null);

const slot = computed(() => (W - PL - PR) / Math.max(1, props.values.length));
const bw = computed(() => slot.value * 0.62);
const bx = (i) => PL + i * slot.value + (slot.value - bw.value) / 2;
const peak = computed(() => props.values.indexOf(Math.max(0, ...props.values)));

const nice = computed(() => {
    const raw = Math.max(1, ...props.values);
    const p = 10 ** Math.floor(Math.log10(raw));
    const f = raw / p;
    return (f <= 1 ? 1 : f <= 2 ? 2 : f <= 5 ? 5 : 10) * p;
});
const ticks = computed(() => [...new Set([0, 1, 2, 3, 4].map((i) => Math.round((nice.value * i) / 4)))]);
const y = (v) => PT + (H - PT - PB) * (1 - v / nice.value);
const tipLeft = computed(() => Math.min(85, Math.max(15, ((bx(hover.value ?? 0) + bw.value / 2) / W) * 100)));
</script>