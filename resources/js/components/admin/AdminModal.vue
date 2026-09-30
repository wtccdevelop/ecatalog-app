<template>
    <Teleport to="body">
        <div
            v-if="modelValue"
            class="fixed inset-0 z-80 flex items-center justify-center bg-black/50 p-3"
            @click.self="close"
        >
            <div class="w-full max-w-lg max-h-[92vh] overflow-y-auto bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ title }}</h3>
                    <button type="button" @click="close" class="size-8 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-white" aria-label="Tutup">✕</button>
                </div>
                <div class="p-5">
                    <slot />
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
defineProps({
    modelValue: { type: Boolean, default: false },
    title: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);

const close = () => emit('update:modelValue', false);
</script>