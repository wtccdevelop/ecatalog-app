<template>
    <div class="overflow-x-auto lg:overflow-x-visible dark:bg-neutral-700 bg-white whitespace-nowrap lg:whitespace-normal px-3 py-1 lg:py-4 lg:px-3 [&::-webkit-scrollbar]:hidden">
        <div class="bg-blue-900 text-white text-xs p-4 rounded-lg w-full mb-4">
            <h2>Temukan Produk Kami Berdasarkan Budgetmu.</h2>
        </div>
        <div class="lg:grid lg:grid-cols-8 sm:gap-x-6 sm:justify-items-center">
            <RouterLink
                v-for="cat in priceCategories"
                :key="cat.id"
                :to="{ path: '/products', query: { price: cat.id } }"
                class="inline-flex flex-col px-4 py-2 sm:px-6 sm:py-2 mr-3 sm:mr-0 items-center rounded-lg shadow-sm bg-blue-900 text-center cursor-pointer transition-transform duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md text-white dark:text-gray-200 dark:bg-neutral-800"
            >
                <span class="text-[10px] sm:text-base font-poppins">{{ cat.name }}</span>
            </RouterLink>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

const priceCategories = computed(() => catalog.home?.price_categories ?? []);
</script>