<template>
    <div class="sm:px-6 lg:px-16 mx-auto pb-10">
        <div class="text-center dark:text-white">
            <h1 class="font-bold text-lg md:text-3xl">TEMUKAN TOKO KAMI</h1>
            <p class="text-[10px] sm:text-sm">Kunjungi toko terdekat kami dan dapatkan pengalaman belanja terbaik</p>
        </div>

        <div class="px-3 py-3 sm:py-8 lg:px-10 lg:py-10">
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                <a
                    v-for="loc in storeAddresses"
                    :key="loc.title"
                    :href="loc.link"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group flex flex-col bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-md rounded-xl hover:shadow-lg transition"
                >
                    <div class="p-2 md:p-5">
                        <div class="flex gap-x-1 md:gap-x-4 items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23 23" fill="currentColor" class="shrink-0 size-5 md:size-8 lg:size-10 drop-shadow-xl text-red-500">
                                <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                            <div class="grow">
                                <h3 class="group-hover:text-blue-600 md:text-base text-[10px] font-semibold text-gray-800 dark:text-white">{{ loc.title }}</h3>
                                <p class="text-[8px] md:text-sm text-gray-500 dark:text-gray-400">{{ loc.address }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { storeAddresses } from '../data/stores.js';
</script>