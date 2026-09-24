<template>
    <header class="sticky z-50 top-0 drop-shadow-md flex flex-wrap lg:justify-start lg:flex-nowrap w-full bg-white text-sm py-3 dark:bg-neutral-800">
        <nav class="max-w-[85rem] w-full mx-auto px-4 lg:flex lg:items-center lg:justify-between">
            <!-- Logo + Search + Mobile icons -->
            <div class="flex items-center gap-2 w-full">
                <!-- Logo -->
                <a class="flex-none text-xl font-semibold dark:text-white focus:outline-hidden focus:opacity-80" href="/" aria-label="Brand">
                    <span class="inline-flex items-center gap-x-2 text-xs sm:text-lg font-normal dark:text-white">
                        <span class="uppercase font-audiowide antialiased">
                            <span class="inline text-center">Syihab</span>
                            <span class="block md:inline font-poppins uppercase font-normal antialiased text-center">Store</span>
                        </span>
                    </span>
                </a>

                <!-- Search -->
                <div class="flex w-full ml-2 sm:px-8 lg:px-0 lg:ml-16">
                    <div class="w-full relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            class="py-2.5 sm:py-3 px-5 dark:text-white block w-full border border-gray-200 rounded-full sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-600"
                            placeholder="Cari produk atau brand..."
                        />
                        <!-- Search results dropdown -->
                        <div
                            v-if="searchQuery.length > 1 && searchResults.length > 0"
                            class="absolute top-full mt-1 left-0 w-full bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl shadow-lg z-50 max-h-64 overflow-y-auto"
                        >
                            <a
                                v-for="result in searchResults"
                                :key="result.id"
                                href="#"
                                class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-700"
                            >
                                <img :src="result.image" :alt="result.name" class="w-10 h-10 object-contain rounded" />
                                <div>
                                    <p class="text-xs font-semibold text-gray-800 dark:text-white">{{ result.name }}</p>
                                    <p class="text-xs text-green-600">{{ result.price }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile cart -->
                <div class="lg:hidden px-1">
                    <a href="#" class="relative inline-block hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                        </svg>
                    </a>
                </div>

                <!-- Mobile hamburger -->
                <div class="lg:hidden flex gap-3">
                    <button
                        type="button"
                        @click="mobileOpen = !mobileOpen"
                        class="relative size-9 flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-white dark:hover:bg-white/10"
                        aria-label="Toggle navigation"
                    >
                        <svg v-if="!mobileOpen" class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6" /><line x1="3" x2="21" y1="12" y2="12" /><line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                        <svg v-else class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Desktop nav + Mobile dropdown -->
            <div :class="['overflow-hidden transition-all duration-300 basis-full grow lg:block', mobileOpen ? 'block' : 'hidden']">
                <div class="flex flex-col gap-5 mt-5 lg:flex-row lg:items-center lg:justify-end lg:mt-0 lg:ps-5">

                    <!-- Mobile profile -->
                    <div class="lg:hidden">
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0 size-9 rounded-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <div class="text-start">
                                    <p class="text-xs text-gray-500 dark:text-neutral-500">Login Sebagai</p>
                                    <p class="text-xs font-medium text-gray-800 dark:text-neutral-200">Belum Login</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Nav links -->
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="/#product">Product</a>
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="#">Events</a>
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="#">Simulasi Kredit</a>
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="#">Tentang Kami</a>

                    <!-- Desktop cart -->
                    <div class="hidden lg:block">
                        <a href="#" class="relative inline-block dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                            </svg>
                        </a>
                    </div>

                    <!-- Desktop profile dropdown -->
                    <div class="hidden lg:block relative" ref="profileDropdown">
                        <button
                            @click="profileOpen = !profileOpen"
                            class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 dark:text-white"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0 size-9 rounded-full">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div
                            v-show="profileOpen"
                            class="absolute right-0 mt-2 min-w-60 bg-white shadow-md rounded-lg dark:bg-neutral-800 dark:border dark:border-neutral-700 z-50"
                        >
                            <div class="py-3 px-5 bg-gray-100 rounded-t-lg flex justify-between dark:bg-neutral-700">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-neutral-500">Login Sebagai</p>
                                    <p class="text-xs font-medium text-gray-800 dark:text-neutral-200">Belum Login</p>
                                </div>
                                <!-- Dark mode toggle -->
                                <div class="ml-3">
                                    <button
                                        @click="toggleDark"
                                        class="p-1 block bg-white/50 dark:bg-neutral-800 shadow-md font-medium text-gray-800 dark:text-neutral-200 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-700"
                                        :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                                    >
                                        <span class="inline-flex shrink-0 justify-center items-center size-9">
                                            <!-- Moon icon (show when light) -->
                                            <svg v-if="!isDark" class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                                            </svg>
                                            <!-- Sun icon (show when dark) -->
                                            <svg v-else class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="/register" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    Register Account
                                </a>
                                <a href="/login" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                    Login
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { productsByBrand } from '../data/products.js';

const props = defineProps({
    isDark: { type: Boolean, default: false },
});
const emit = defineEmits(['toggle-dark']);

const mobileOpen  = ref(false);
const profileOpen = ref(false);
const searchQuery = ref('');
const profileDropdown = ref(null);

// Flatten all products for search
const allProducts = Object.values(productsByBrand).flat();

const searchResults = computed(() => {
    if (searchQuery.value.length < 2) return [];
    const q = searchQuery.value.toLowerCase();
    return allProducts.filter(p => p.name.toLowerCase().includes(q)).slice(0, 8);
});

function toggleDark() {
    emit('toggle-dark');
}

// Close profile dropdown when clicking outside
function handleOutsideClick(e) {
    if (profileDropdown.value && !profileDropdown.value.contains(e.target)) {
        profileOpen.value = false;
    }
}

onMounted(() => document.addEventListener('click', handleOutsideClick));
onUnmounted(() => document.removeEventListener('click', handleOutsideClick));
</script>
