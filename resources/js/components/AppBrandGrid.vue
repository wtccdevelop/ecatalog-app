<template>
    <section class="relative px-3 py-6 sm:px-4 overflow-hidden bg-white dark:bg-neutral-700">
        <!-- blob cahaya lembut -->
        <div class="pointer-events-none absolute -top-16 -left-10 size-56 rounded-full bg-sky-200/50 blur-3xl dark:bg-sky-500/10"></div>
        <div class="pointer-events-none absolute -bottom-20 right-0 size-64 rounded-full bg-violet-200/50 blur-3xl dark:bg-violet-500/10"></div>

        <div class="relative">
            <!-- Header -->
            <div class="mb-5 flex items-end justify-between gap-3">
                <div>
                    <span class="inline-block rounded-full bg-sky-100 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-sky-600 dark:bg-sky-500/15 dark:text-sky-300">
                        Brand
                    </span>
                    <h2 class="mt-2 text-base font-extrabold text-gray-900 sm:text-2xl dark:text-white">
                        Pilih brand kesayanganmu
                    </h2>
                    <div class="mt-2 h-1 w-14 rounded-full bg-linear-to-r from-sky-400 to-violet-400"></div>
                </div>
                <RouterLink
                    to="/products"
                    class="text-xs font-semibold text-sky-600 hover:underline dark:text-sky-300"
                >
                    Lihat semua →
                </RouterLink>
            </div>

            <!-- Grid -->
            <div
                class="flex gap-3 overflow-x-auto snap-x snap-mandatory pb-2 lg:grid lg:grid-cols-8 lg:gap-4 lg:overflow-visible [&::-webkit-scrollbar]:hidden"
            >
                <RouterLink
                    v-for="brand in brands"
                    :key="brand.id"
                    :to="{ path: '/products', query: { brand: brand.slug } }"
                    class="group relative shrink-0 snap-start w-24 sm:w-32 lg:w-auto"
                >
                    <!-- glow di belakang kartu -->
                    <div class="absolute inset-0 rounded-3xl bg-linear-to-br from-sky-300 to-violet-300 opacity-0 blur-xl transition-opacity duration-500 group-hover:opacity-60"></div>

                    <div
                        class="relative rounded-3xl bg-white/70 p-2.5 ring-1 ring-black/5 backdrop-blur-md shadow-sm transition-all duration-300 group-hover:-translate-y-1.5 group-hover:shadow-xl dark:bg-neutral-800/70 dark:ring-white/10"
                    >
                        <div class="aspect-square overflow-hidden rounded-2xl bg-gray-50 ring-1 ring-black/5 dark:bg-neutral-900 dark:ring-white/5">
                            <img
                                :src="brand.logo"
                                :alt="brand.name"
                                loading="lazy"
                                class="size-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                        </div>
                        <p class="mt-2 truncate text-center text-[10px] font-bold tracking-wide text-gray-700 sm:text-xs dark:text-neutral-200">
                            {{ brand.name }}
                        </p>
                    </div>
                </RouterLink>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

const brands = computed(() => catalog.home?.brands ?? []);
</script>