<template>
    <ul v-if="items.length" class="space-y-3">
        <li v-for="(it, i) in items" :key="it.label + i">
            <div class="flex items-center justify-between gap-3 text-xs mb-1">
                <span class="truncate font-medium text-gray-700 dark:text-neutral-200">{{ i + 1 }}. {{ it.label }}</span>
                <span class="shrink-0 text-gray-500 dark:text-neutral-400">
                    <b class="text-gray-900 dark:text-white">{{ it.value.toLocaleString('id-ID') }}</b><template v-if="it.sub"> · {{ it.sub }}</template>
                </span>
            </div>
            <div class="h-2 rounded-full bg-gray-100 dark:bg-neutral-700 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500" :class="bar" :style="{ width: (it.value / max) * 100 + '%' }"></div>
            </div>
        </li>
    </ul>
    <p v-else class="py-8 text-center text-xs text-gray-400 dark:text-neutral-500">Belum ada data.</p>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    items: { type: Array, default: () => [] }, // [{ label, value, sub? }]
    bar: { type: String, default: 'bg-indigo-300 dark:bg-indigo-400' },
});

const max = computed(() => Math.max(1, ...props.items.map((i) => i.value)));
</script>