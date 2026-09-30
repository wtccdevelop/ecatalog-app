<template>
    <div class="overflow-x-auto">
        <div class="min-w-[520px]">
            <div class="grid gap-1" style="grid-template-columns: 2rem repeat(24, minmax(0, 1fr))">
                <div></div>
                <div v-for="h in 24" :key="h" class="text-[9px] text-center text-gray-400 dark:text-neutral-500">
                    {{ (h - 1) % 3 === 0 ? String(h - 1).padStart(2, '0') : '' }}
                </div>

                <template v-for="(row, d) in matrix" :key="d">
                    <div class="flex items-center text-[10px] text-gray-500 dark:text-neutral-400">{{ days[d] }}</div>
                    <div
                        v-for="(v, h) in row" :key="h"
                        class="aspect-square rounded-[4px] transition-transform hover:scale-125"
                        :style="cell(v)"
                        :title="`${days[d]} ${String(h).padStart(2, '0')}:00 — ${v} page view`"
                    ></div>
                </template>
            </div>

            <div class="mt-3 flex items-center justify-end gap-2 text-[10px] text-gray-400 dark:text-neutral-500">
                Sepi
                <span class="h-2 w-24 rounded-full" style="background: linear-gradient(to right, rgba(129,140,248,0.15), rgba(99,102,241,1))"></span>
                Ramai
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    matrix: { type: Array, default: () => [] }, // 7 x 24, Senin = 0
});

const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
const max = computed(() => Math.max(1, ...props.matrix.flat()));

const cell = (v) => ({
    backgroundColor: v ? `rgba(99,102,241,${0.15 + (0.85 * v) / max.value})` : 'rgba(148,163,184,0.15)',
});
</script>