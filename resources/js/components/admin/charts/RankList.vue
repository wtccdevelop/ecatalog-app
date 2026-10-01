<template>
    <div v-if="items.length">
        <ul class="space-y-3">
            <li v-for="(it, i) in paged" :key="it.label + i">
                <div class="flex items-center justify-between gap-3 text-xs mb-1">
                    <span class="truncate font-medium text-gray-700 dark:text-neutral-200">{{ offset + i + 1 }}. {{ it.label }}</span>
                    <span class="shrink-0 text-gray-500 dark:text-neutral-400">
                        <b class="text-gray-900 dark:text-white">{{ it.value.toLocaleString('id-ID') }}</b><template v-if="it.sub"> · {{ it.sub }}</template>
                    </span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 dark:bg-neutral-700 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500" :class="bar" :style="{ width: (it.value / max) * 100 + '%' }"></div>
                </div>
            </li>
        </ul>

        <MiniPager v-model:page="page" :total="items.length" :per-page="perPage" />
    </div>
    <p v-else class="py-8 text-center text-xs text-gray-400 dark:text-neutral-500">Belum ada data.</p>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import MiniPager from './MiniPager.vue';

const props = defineProps({
    items: { type: Array, default: () => [] }, // [{ label, value, sub? }]
    bar: { type: String, default: 'bg-indigo-300 dark:bg-indigo-400' },
    perPage: { type: Number, default: 10 },
});

const page = ref(1);

const max = computed(() => Math.max(1, ...props.items.map((i) => i.value)));
const offset = computed(() => (page.value - 1) * props.perPage);
const paged = computed(() => props.items.slice(offset.value, offset.value + props.perPage));

// data berubah (ganti rentang tanggal, dsb.) -> kembali ke halaman 1
watch(() => props.items, () => { page.value = 1; });
</script>