<template>
    <template v-if="isActive">
        <!-- Natal -->
        <span
            v-if="hasDecoration('christmas-hat')"
            class="pointer-events-none absolute -top-2 -right-1 z-20 text-lg sm:text-2xl rotate-12"
            aria-hidden="true"
        >
            🎅
        </span>

        <span
            v-if="hasDecoration('christmas-ornament')"
            class="pointer-events-none absolute top-1 left-1 z-20 text-sm sm:text-lg"
            aria-hidden="true"
        >
            🎄
        </span>

        <!-- Lebaran -->
        <span
            v-if="hasDecoration('eid-star')"
            class="pointer-events-none absolute -top-1 -right-1 z-20 text-sm sm:text-lg"
            aria-hidden="true"
        >
            ✨
        </span>

        <span
            v-if="hasDecoration('eid-moon')"
            class="pointer-events-none absolute top-1 right-2 z-20 text-sm sm:text-lg"
            aria-hidden="true"
        >
            🌙
        </span>

        <!-- Kemerdekaan -->
        <span
            v-if="hasDecoration('independence-ribbon')"
            class="pointer-events-none absolute top-0 left-0 right-0 z-20 h-1 rounded-t-xl bg-gradient-to-r from-red-500 via-white to-red-500"
            aria-hidden="true"
        ></span>

        <!-- Tahun Baru -->
        <span
            v-if="hasDecoration('new-year-sparkle')"
            class="pointer-events-none absolute -top-1 -right-1 z-20 text-sm sm:text-lg"
            aria-hidden="true"
        >
            ✨
        </span>
    </template>
</template>

<script setup>
import { useEventTheme } from '../composables/useEventTheme.js';

const {
    isActive,
    hasDecoration,
} = useEventTheme();
</script>