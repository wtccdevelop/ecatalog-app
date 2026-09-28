<template>
    <div>
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
</template>

<script setup>
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
</script>