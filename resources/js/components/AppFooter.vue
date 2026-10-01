<template>
    <footer class="inset-shadow-sm inset-shadow-black/30 bg-white dark:bg-neutral-800">
        <div class="w-full py-10 px-4 sm:px-6 lg:px-8 mx-auto max-w-[96rem] text-center">
            <a class="text-xl font-semibold dark:text-white" href="/" aria-label="Brand">
                <span class="inline-flex items-center gap-x-2 text-xs sm:text-lg font-normal dark:text-white">
                    <img
                        :src="'/assets/images/redesain_logo.jpg'"
                        alt="WTC Cell"
                        class="h-10 object-contain"
                    >
                </span>
            </a>

            <div class="mt-1">
                <p class="text-gray-500 text-xs sm:text-sm font-semibold">#ceksekalipastibeli</p>
                <p class="text-gray-500 text-xs sm:text-sm">
                    © {{ year }} <span class="font-semibold">WTC Cell.</span> All right reserved
                </p>
            </div>

            <div class="mt-1 flex items-center justify-center gap-2">
                <a class="text-gray-500 text-xs sm:text-sm hover:underline" href="/policies#snk">Syarat &amp; Ketentuan</a>
                <a class="text-gray-500 text-xs sm:text-sm hover:underline" href="/policies#refund">Pengembalian Dana</a>
            </div>

            <div class="mt-3 space-x-2">
                <a
                    v-for="s in socials"
                    :key="s.url"
                    :href="s.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    :aria-label="s.label || s.platform"
                    class="size-8 inline-flex justify-center items-center rounded-full text-gray-500 hover:bg-gray-50 dark:hover:bg-neutral-700"
                >
                    <svg class="shrink-0 size-3.5" fill="currentColor" :viewBox="socialIcons[s.platform].viewBox">
                        <path :d="socialIcons[s.platform].path" />
                    </svg>
                </a>
            </div>
            <p class="text-xs sm:text-m font-normal dark:text-white">
                <!-- Kami mencatat statistik kunjungan secara anonim untuk meningkatkan layanan. Kami tidak memakai cookie dan tidak menyimpan alamat IP asli Anda. -->
            </p>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';
import { socialIcons } from '../data/socialIcons.js';

const year = new Date().getFullYear();

// hanya platform yang punya ikon
const socials = computed(() => (catalog.site?.socials ?? []).filter((s) => socialIcons[s.platform]));
</script>