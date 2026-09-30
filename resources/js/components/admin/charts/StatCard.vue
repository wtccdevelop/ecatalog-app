<template>
    <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm ring-1 ring-black/5 dark:ring-white/5 p-4">
        <div class="flex items-center gap-2">
            <span class="size-2 rounded-full" :class="dots[tone]"></span>
            <p class="text-xs text-gray-500 dark:text-neutral-400">{{ label }}</p>
        </div>
        <p class="mt-2 text-2xl font-extrabold text-gray-900 dark:text-white tabular-nums">{{ value }}</p>
        <p v-if="hint" class="mt-0.5 text-[11px] text-gray-400 dark:text-neutral-500">{{ hint }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: { type: String, required: true },
    value: { type: [String, Number], default: 0 },
    hint: { type: String, default: '' },
    tone: { type: String, default: 'indigo' },
});

const dots = {
    indigo: 'bg-indigo-400',
    sky: 'bg-sky-400',
    emerald: 'bg-emerald-400',
    amber: 'bg-amber-400',
    rose: 'bg-rose-400',
    violet: 'bg-violet-400',
};
</script>