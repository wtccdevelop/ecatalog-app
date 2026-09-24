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
                    <div class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <button
                            @click="showInstagram = true"
                            class="text-black dark:text-white cursor-pointer"
                            aria-label="Instagram"
                        >
                            <svg class="sm:size-7 size-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077" />
                            </svg>
                        </button>
                    </div>
                    <!-- Facebook -->
                    <div class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <a href="https://www.facebook.com/people/Syihab-Store-Banjarbaru/100092506301237/" target="_blank" rel="noopener noreferrer" class="text-black dark:text-white" aria-label="Facebook">
                            <svg class="sm:size-7 size-5" fill="currentColor" viewBox="0 0 50 50">
                                <path d="M32,11h5c0.552,0,1-0.448,1-1V3.263c0-0.524-0.403-0.96-0.925-0.997C35.484,2.153,32.376,2,30.141,2C24,2,20,5.68,20,12.368V19h-7c-0.552,0-1,0.448-1,1v7c0,0.552,0.448,1,1,1h7v19c0,0.552,0.448,1,1,1h7c0.552,0,1-0.448,1-1V28h7.222c0.51,0,0.938-0.383,0.994-0.89l0.778-7C38.06,19.518,37.596,19,37,19h-8v-5C29,12.343,30.343,11,32,11z" />
                            </svg>
                        </a>
                    </div>
                    <!-- TikTok -->
                    <div class="border border-gray-300 dark:border-gray-500 px-2 py-2 rounded-lg hover:border-transparent transition-colors duration-500">
                        <a href="https://www.tiktok.com/@syihabstore_" target="_blank" rel="noopener noreferrer" class="text-black dark:text-white" aria-label="TikTok">
                            <svg class="sm:size-7 size-5" fill="currentColor" viewBox="0 0 25 25">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Marketplace -->
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
                            :key="account.handle"
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
                                <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ account.handle }}</p>
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
import { ref } from 'vue';
import { instagramAccounts } from '../data/stores.js';

const showInstagram = ref(false);
</script>
