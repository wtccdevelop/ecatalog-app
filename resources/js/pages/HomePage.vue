<template>
    <div v-if="catalog.home">
        <div class="bg-white dark:bg-neutral-700">
            <div class="max-w-[96rem] mx-auto">
                <AppBanner />
                <AppBrandGrid />
                <AppPriceCategory />
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-neutral-700">
            <div class="lg:px-16 sm:px-6 max-w-[96rem] mx-auto">
                <AppProductList />
                <AppBenefits />
                <AppStoreSlider />
            </div>

            <div class="grid lg:grid-cols-2 gap-5 px-4 lg:px-24 sm:px-12 mt-14 max-w-[96rem] mx-auto">
                <AppAbout />
                <AppPayments />
                <AppSocial />
                <AppShipping />
            </div>

            <div class="mt-16 max-w-[96rem] mx-auto">
                <AppLocations />
            </div>
        </div>
    </div>

    <div v-else class="min-h-[60vh] flex items-center justify-center text-sm dark:text-white">
        <div v-if="catalog.homeError" class="text-center">
            <p class="mb-3">Gagal memuat data.</p>
            <button
                type="button"
                @click="retry"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700"
            >
                Coba lagi
            </button>
        </div>
        <p v-else>Memuat...</p>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import AppBanner from '../components/AppBanner.vue';
import AppBrandGrid from '../components/AppBrandGrid.vue';
import AppPriceCategory from '../components/AppPriceCategory.vue';
import AppProductList from '../components/AppProductList.vue';
import AppBenefits from '../components/AppBenefits.vue';
import AppStoreSlider from '../components/AppStoreSlider.vue';
import AppAbout from '../components/AppAbout.vue';
import AppPayments from '../components/AppPayments.vue';
import AppSocial from '../components/AppSocial.vue';
import AppShipping from '../components/AppShipping.vue';
import AppLocations from '../components/AppLocations.vue';
import { catalog, loadHome } from '../stores/catalog.js';

function retry() {
    loadHome(true).catch(() => {});
}

onMounted(() => {
    loadHome().catch(() => {});
});
</script>