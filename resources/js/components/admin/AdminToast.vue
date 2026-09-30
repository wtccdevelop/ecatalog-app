<template>
    <div class="fixed top-4 right-4 z-90 space-y-2">
        <div
            v-for="t in toasts"
            :key="t.id"
            class="px-4 py-2.5 rounded-lg shadow-lg text-sm font-semibold text-white"
            :class="t.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'"
        >
            {{ t.text }}
        </div>
    </div>
</template>

<script setup>
import { toasts } from '../../stores/toast.js';
</script>