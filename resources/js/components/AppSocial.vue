<template>
    <div class="py-6 px-4 sm:px-6 lg:px-16 bg-white dark:bg-neutral-800">
        <div class="max-w-[96rem] mx-auto grid grid-cols-1 sm:grid-cols-2 gap-8">

            <!-- Temukan Kami -->
            <div>
                <div class="mb-4">
                    <h2 class="text-lg tracking-widest font-extrabold dark:text-white">TEMUKAN KAMI</h2>
                    <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Instagram -->
                    <div v-if="instagramAccounts.length" class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <button
                            @click="showInstagram = true"
                            class="text-black dark:text-white cursor-pointer"
                            aria-label="Instagram"
                        >
                            <svg class="sm:size-7 size-5" fill="currentColor" :viewBox="socialIcons.instagram.viewBox">
                                <path :d="socialIcons.instagram.path" />
                            </svg>
                        </button>
                    </div>
                    <!-- Facebook -->
                    <div v-if="facebook" class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <a :href="facebook.url" target="_blank" rel="noopener noreferrer" class="text-black dark:text-white" aria-label="Facebook">
                            <svg class="sm:size-7 size-5" fill="currentColor" :viewBox="socialIcons.facebook.viewBox">
                                <path :d="socialIcons.facebook.path" />
                            </svg>
                        </a>
                    </div>
                    <!-- TikTok -->
                    <div v-if="tiktok" class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <a :href="tiktok.url" target="_blank" rel="noopener noreferrer" class="text-black dark:text-white" aria-label="TikTok">
                            <svg class="sm:size-7 size-5" fill="currentColor" :viewBox="socialIcons.tiktok.viewBox">
                                <path :d="socialIcons.tiktok.path" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Marketplace (masih statis) -->
            <div>
                <div class="mb-4">
                    <h2 class="text-lg tracking-widest font-extrabold dark:text-white">MARKETPLACE KAMI</h2>
                    <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="border border-gray-300 dark:border-gray-500 px-3 py-2 rounded-lg hover:border-transparent transition-colors duration-500 flex items-center gap-2">
                        <svg class="size-7" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="50" fill="#EE4D2D"/>
                            <path d="M27 35h46l-5 30H32L27 35z" fill="white"/>
                            <circle cx="37" cy="70" r="4" fill="white"/>
                            <circle cx="63" cy="70" r="4" fill="white"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Shopee</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Instagram modal -->
        <Teleport to="body">
            <div
                v-if="showInstagram"
                class="fixed inset-0 z-80 flex items-center justify-center bg-black/50"
                @click.self="showInstagram = false"
            >
                <div class="relative w-full max-w-md m-3 bg-white dark:bg-neutral-800 rounded-xl shadow-2xl overflow-hidden">
                    <div class="flex flex-col text-center items-center py-5 px-4 bg-gradient-to-r from-fuchsia-600 to-pink-600 rounded-t-xl">
                        <h3 class="font-extrabold text-white text-lg">Instagram Kami</h3>
                        <p class="mt-1 text-xs text-white">Pilih akun Instagram yang ingin dikunjungi!</p>
                    </div>
                    <button
                        @click="showInstagram = false"
                        class="absolute top-3 right-3 size-8 inline-flex justify-center items-center rounded-full bg-white/20 text-white hover:bg-white/30"
                        aria-label="Close"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                    <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                        <a
                            v-for="account in instagramAccounts"
                            :key="account.url"
                            :href="account.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-700 border border-gray-200 dark:border-neutral-500 rounded-xl hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors"
                        >
                            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-full p-2 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0,0,256,256">
                                    <g fill="#ffffff" fill-rule="nonzero"><g transform="scale(5.33333,5.33333)"><path d="M16.5,5c-6.33361,0-11.5,5.16639-11.5,11.5v15c0,6.33276 5.16621,11.5 11.5,11.5h15c6.33294,0 11.5,-5.16706 11.5,-11.5v-15c0,-6.33379-5.16724,-11.5-11.5,-11.5zM16.5,8h15c4.71124,0 8.5,3.78779 8.5,8.5v15c0,4.71106-3.78894,8.5-8.5,8.5h-15c-4.71221,0-8.5,-3.78876-8.5,-8.5v-15c0,-4.71239 3.78761,-8.5 8.5,-8.5zM34,12c-1.105,0-2,0.895-2,2c0,1.105 0.895,2 2,2c1.105,0 2,-0.895 2,-2c0,-1.105-0.895,-2-2,-2zM24,14c-5.50482,0-10,4.49518-10,10c0,5.50482 4.49518,10 10,10c5.50482,0 10,-4.49518 10,-10c0,-5.50482-4.49518,-10-10,-10zM24,17c3.88318,0 7,3.11682 7,7c0,3.88318-3.11682,7-7,7c-3.88318,0-7,-3.11682-7,-7c0,-3.88318 3.11682,-7 7,-7z"/></g></g>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ account.handle || account.label }}</p>
                                <p class="text-xs text-gray-500 dark:text-neutral-400">{{ account.label }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { catalog } from '../stores/catalog.js';
import { socialIcons } from '../data/socialIcons.js';

const showInstagram = ref(false);

const socials = computed(() => catalog.site?.socials ?? []);
const instagramAccounts = computed(() => socials.value.filter((s) => s.platform === 'instagram'));
const facebook = computed(() => socials.value.find((s) => s.platform === 'facebook'));
const tiktok = computed(() => socials.value.find((s) => s.platform === 'tiktok'));
</script>