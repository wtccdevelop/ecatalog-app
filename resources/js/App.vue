<template>
    <div class="font-poppins" :class="{ dark: isDark }">
        <div class="bg-white dark:bg-neutral-800 min-h-screen">
            <AppNavbar :is-dark="isDark" @toggle-dark="toggleDark" />

            <div class="bg-white dark:bg-neutral-700">
                <div class="max-w-[96rem] mx-auto">
                    <AppBanner />
                    <AppBrandGrid />
                    <AppPriceCategory />
                </div>
            </div>

            <div class="bg-gray-100 dark:bg-neutral-700">
                <div class="lg:px-16 sm:px-6 max-w-[96rem] mx-auto">
                    <AppGskBanner />
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

            <AppFooter />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import AppBanner from './components/AppBanner.vue';
import AppBrandGrid from './components/AppBrandGrid.vue';
import AppPriceCategory from './components/AppPriceCategory.vue';
import AppGskBanner from './components/AppGskBanner.vue';
import AppProductList from './components/AppProductList.vue';
import AppBenefits from './components/AppBenefits.vue';
import AppStoreSlider from './components/AppStoreSlider.vue';
import AppAbout from './components/AppAbout.vue';
import AppPayments from './components/AppPayments.vue';
import AppSocial from './components/AppSocial.vue';
import AppShipping from './components/AppShipping.vue';
import AppLocations from './components/AppLocations.vue';
import AppFooter from './components/AppFooter.vue';

const isDark = ref(false);

function applyTheme() {
    // class "dark" juga dipasang di <html> supaya modal/teleport ikut ter-style
    document.documentElement.classList.toggle('dark', isDark.value);
    try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light'); } catch (e) {}
}

function toggleDark() {
    isDark.value = !isDark.value;
    applyTheme();
}

onMounted(() => {
    let saved = null;
    try { saved = localStorage.getItem('theme'); } catch (e) {}
    isDark.value = saved
        ? saved === 'dark'
        : window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme();
});
</script>