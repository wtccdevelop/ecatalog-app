<template>
    <div v-if="services.length" class="lg:col-start-2 max-w-[40rem] w-full">
        <div class="mb-4">
            <h1 class="text-lg tracking-widest font-extrabold dark:text-white">JASA KIRIM</h1>
            <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
        </div>
        <div class="flex flex-wrap gap-2">
            <div
                v-for="s in services"
                :key="s.name"
                class="w-24 sm:w-28 bg-white dark:bg-neutral-700 px-2 py-2 rounded-md flex items-center justify-center"
            >
                <img class="w-full h-10 object-contain" :src="s.logo" :alt="s.name" loading="lazy" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

const services = computed(() => catalog.home?.shipping ?? []);
</script>