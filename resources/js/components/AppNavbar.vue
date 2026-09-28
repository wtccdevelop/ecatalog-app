<template>
    <header class="sticky z-50 top-0 drop-shadow-md flex flex-wrap lg:justify-start lg:flex-nowrap w-full bg-white text-sm py-3 dark:bg-neutral-800">
        <nav class="max-w-[85rem] w-full mx-auto px-4 lg:flex lg:items-center lg:justify-between">
            <!-- Logo + Search + Mobile icons -->
            <div class="flex items-center gap-2 w-full">
                <!-- Logo -->
                <RouterLink class="flex-none text-xl font-semibold dark:text-white focus:outline-hidden focus:opacity-80" to="/" aria-label="Brand">

                    <span class="inline-flex items-center gap-x-2 text-xs sm:text-lg font-normal dark:text-white">
                        <span class="uppercase font-audiowide antialiased">
                            <span class="inline text-center">WTC</span>
                            <span class="block md:inline font-poppins uppercase font-normal antialiased text-center">Cell</span>
                        </span>
                    </span>
                </RouterLink>

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

                    <!-- Nav links -->
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="/#product">Product</a>
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="#">Events</a>
                    <a class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400" href="#">Simulasi Kredit</a>
                    <RouterLink
                        class="font-medium text-gray-600 hover:text-green-400 dark:text-neutral-400"
                        :to="{ path: '/', hash: '#about' }"
                        @click="mobileOpen = false"
                    >
                        Tentang Kami
                    </RouterLink>

                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { productsByBrand } from '../data/products.js';
import { slugify } from '../data/productDetails.js';

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
