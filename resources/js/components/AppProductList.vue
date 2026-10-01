<template>
    <div id="product" class="w-full px-4 sm:px-6 lg:px-8 py-3 lg:py-10 mx-auto">
        <div class="text-center mb-10 md:mb-20">
            <h1 class="font-bold text-lg sm:text-3xl mb-2 dark:text-white">Semua Produk</h1>
            <p class="text-[10px] sm:text-sm dark:text-white">Temukan berbagai produk unggulan di WTC Cell</p>
        </div>

        <p v-if="!groups.length" class="text-center text-sm text-gray-500 dark:text-neutral-400">
            Belum ada produk.
        </p>

        <div v-for="group in groups" :key="group.brand" class="mb-12">
            <!-- Brand header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="md:text-xl font-bold dark:text-white">{{ group.brand }}</h2>
                <RouterLink
                    :to="{ path: '/products', query: { brand: group.slug } }"
                    class="py-2 px-4 inline-flex items-center gap-x-2 text-xs sm:text-sm font-semibold rounded-lg border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-teal-800 dark:text-white dark:hover:bg-teal-900 transition-colors duration-500"
                >
                    Lihat Semua
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </RouterLink>
            </div>

            <!-- Product grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-4">
                <RouterLink
                    v-for="product in visibleProducts(group)"
                    :key="product.id"
                    :to="`/product/${product.slug}`"
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-900 cursor-pointer hover:shadow-md transition-shadow relative"
                >
                    <EventDecoration />
                    <img
                        class="h-auto mx-auto dark:bg-neutral-500 rounded-t-xl"
                        :src="product.image"
                        :alt="product.name"
                        loading="lazy"
                    />
                    <div class="p-2 lg:p-5">
                        <h3 class="text-xs lg:text-lg font-bold text-gray-800 dark:text-white">
                            {{ product.name }}
                        </h3>
                        <p class="mt-1 text-[10px] lg:text-base text-gray-500 dark:text-gray-400">
                            Mulai dari:<br />
                            <span class="text-green-500 font-semibold">{{ formatRupiah(product.price) }}</span>
                        </p>
                        <ul class="mt-2 text-[8px] lg:text-xs text-gray-600 dark:text-gray-300">
                            <li>
                                Atau cicilan:<br />
                                <span class="font-semibold">{{ formatRupiah(monthly24(product.price)) }}/bln</span>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2 border-t border-gray-100 dark:border-neutral-700 flex items-center justify-between">
                            <span class="inline-flex items-center gap-x-1 text-[10px] lg:text-xs font-semibold text-blue-600 dark:text-blue-400">
                                Lihat Detail
                                <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <span class="text-[9px] lg:text-[11px] font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded">
                                Tanya WA 💬
                            </span>
                        </div>
                    </div>
                </RouterLink>
            </div>

            <!-- Load more -->
            <div v-if="group.products.length > shown(group)" class="text-center mt-6">
                <button
                    @click="loadMore(group)"
                    class="py-2 px-4 inline-flex items-center gap-x-2 text-xs sm:text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-teal-800 dark:text-white dark:hover:bg-teal-900 transition-colors duration-500"
                >
                    Tampilkan Lebih Banyak {{ group.brand }}
                    ({{ shown(group) }} / {{ group.products.length }} Items)
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive } from 'vue';
import { catalog } from '../stores/catalog.js';
import { formatRupiah, monthly24 } from '../lib/format.js';
import EventDecoration from './EventDecoration.vue';

const INITIAL_COUNT = 4;
const LOAD_MORE_COUNT = 4;

const groups = computed(() => catalog.home?.products_by_brand ?? []);

// jumlah tampil per brand
const perPage = reactive({});

const shown = (group) => perPage[group.brand] ?? INITIAL_COUNT;
const visibleProducts = (group) => group.products.slice(0, shown(group));

function loadMore(group) {
    perPage[group.brand] = Math.min(shown(group) + LOAD_MORE_COUNT, group.products.length);
}
</script>