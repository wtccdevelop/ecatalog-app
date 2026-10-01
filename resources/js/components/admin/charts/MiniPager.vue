<template>
    <div v-if="pages > 1" class="mt-4 flex items-center justify-between gap-2 text-[11px] text-gray-500 dark:text-neutral-400">
        <span>{{ from }}–{{ to }} dari {{ total.toLocaleString('id-ID') }}</span>

        <div class="flex items-center gap-1">
            <button type="button" :disabled="page <= 1" @click="$emit('update:page', page - 1)" :class="btn" aria-label="Sebelumnya">‹</button>
            <span class="px-1.5 tabular-nums">{{ page }} / {{ pages }}</span>
            <button type="button" :disabled="page >= pages" @click="$emit('update:page', page + 1)" :class="btn" aria-label="Berikutnya">›</button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    page: { type: Number, required: true },
    total: { type: Number, required: true },
    perPage: { type: Number, default: 10 },
});
defineEmits(['update:page']);

const btn = 'size-7 rounded-lg bg-gray-50 text-gray-600 ring-1 ring-gray-200 hover:bg-indigo-50 hover:text-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed dark:bg-neutral-700 dark:text-neutral-200 dark:ring-neutral-600 dark:hover:bg-neutral-600 transition-colors';

const pages = computed(() => Math.max(1, Math.ceil(props.total / props.perPage)));
const from = computed(() => (props.total ? (props.page - 1) * props.perPage + 1 : 0));
const to = computed(() => Math.min(props.total, props.page * props.perPage));
</script>