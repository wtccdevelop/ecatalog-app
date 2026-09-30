<template>
    <section class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm ring-1 ring-black/5 dark:ring-white/5 p-5">
        <header class="flex items-start justify-between gap-3 mb-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ title }}</h2>
                <p v-if="subtitle" class="mt-0.5 text-[11px] text-gray-500 dark:text-neutral-400">{{ subtitle }}</p>
            </div>

            <div v-if="type" class="flex items-center gap-1 shrink-0">
                <button
                    v-for="f in formats" :key="f.value" type="button"
                    @click="download?.(type, f.value)"
                    class="inline-flex items-center gap-1 rounded-lg bg-gray-50 px-2.5 py-1 text-[11px] font-semibold text-gray-600 ring-1 ring-gray-200 hover:bg-indigo-50 hover:text-indigo-600 hover:ring-indigo-200 dark:bg-neutral-700 dark:text-neutral-200 dark:ring-neutral-600 dark:hover:bg-neutral-600 transition-colors"
                    :title="`Unduh ${f.label}`"
                >
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    {{ f.label }}
                </button>
            </div>
        </header>

        <slot />
    </section>
</template>

<script setup>
import { inject } from 'vue';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    type: { type: String, default: '' }, // kunci dataset untuk unduhan
});

const download = inject('downloadStat', null);
const formats = [
    { value: 'csv', label: 'CSV' },
    { value: 'xls', label: 'Excel' },
];
</script>