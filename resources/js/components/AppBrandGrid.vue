<template>
    <div class="overflow-x-auto lg:overflow-x-visible dark:bg-neutral-700 bg-white whitespace-nowrap lg:whitespace-normal py-4 px-3 [&::-webkit-scrollbar]:hidden">
        <div class="bg-blue-900 text-white text-xs p-4 rounded-lg w-full mb-4">
            <h2>Temukan Produk Kami Berdasarkan Brand Kesayanganmu.</h2>
        </div>
        <div class="lg:grid lg:grid-cols-8 md:gap-4 flex overflow-hidden md:justify-items-center">
            <div
                v-for="brand in brands"
                :key="brand.id"
                class="inline-block w-[4.5rem] md:w-[10.6rem] h-auto rounded-lg mr-4 md:mr-0 overflow-hidden text-center cursor-pointer transition-transform duration-300 ease-in-out hover:-translate-y-1 flex flex-col items-center"
            >
                <RouterLink :to="{ path: '/products', query: { brand: brand.slug } }" class="w-full">
                    <div class="w-full h-auto aspect-square overflow-hidden rounded-lg">
                        <img class="w-full h-full object-cover" :src="brand.logo" :alt="brand.name" loading="lazy" />
                    </div>
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

const brands = computed(() => catalog.home?.brands ?? []);
</script>