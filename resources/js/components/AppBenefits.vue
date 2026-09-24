<template>
    <div class="py-10 px-4 sm:px-6 lg:px-16 bg-gray-100 dark:bg-neutral-700">
        <div class="max-w-[96rem] mx-auto">
            <div class="text-center mb-8">
                <h2 class="font-bold text-lg sm:text-2xl dark:text-white">Kenapa Belanja di WTC Cell?</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                    v-for="benefit in benefits"
                    :key="benefit.title"
                    class="flex flex-col items-center text-center p-4 sm:p-6 rounded-xl shadow-sm"
                    :class="benefit.color"
                >
                    <img :src="benefit.icon" :alt="benefit.title" class="w-10 h-10 sm:w-14 sm:h-14 object-contain mb-3" loading="lazy" />
                    <h3 class="text-xs sm:text-base font-bold text-gray-800 dark:text-white mb-1">{{ benefit.title }}</h3>
                    <p class="text-[10px] sm:text-sm text-gray-600 dark:text-gray-300">{{ benefit.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { benefits } from '../data/benefits.js';
</script>
