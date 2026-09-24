<template>
    <div class="grid grid-cols-1 lg:grid-cols-4 p-2 lg:p-4 gap-2 bg-white dark:bg-neutral-700">
        <!-- Main Carousel -->
        <div class="relative lg:col-span-3">
            <!-- Semua Promo button -->
            <div class="absolute bg-black/50 text-xs md:text-base text-white px-3 md:px-4 py-2 rounded-lg cursor-pointer bottom-2 right-2 md:bottom-4 md:right-4 z-10">
                <a href="#">Semua Promo</a>
            </div>

            <!-- Slides -->
            <div class="relative overflow-hidden w-full aspect-[1600/543] rounded-lg shadow-md">
                <div
                    class="flex transition-transform duration-700 h-full"
                    :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
                >
                    <div
                        v-for="(banner, i) in banners"
                        :key="i"
                        class="min-w-full h-full flex justify-center bg-gray-100 dark:bg-neutral-900"
                    >
                        <img :src="banner.image_url" :alt="banner.text" class="object-cover w-full h-full" loading="lazy" />
                    </div>
                </div>
            </div>

            <!-- Dots -->
            <div class="flex justify-center absolute bottom-3 start-3 gap-x-2 z-10">
                <button
                    v-for="(_, i) in banners"
                    :key="i"
                    @click="goTo(i)"
                    class="size-2 md:size-3 border border-gray-400 rounded-full cursor-pointer transition-colors"
                    :class="currentIndex === i ? 'bg-emerald-700 border-emerald-700' : ''"
                    :aria-label="`Slide ${i + 1}`"
                />
            </div>
        </div>

        <!-- Small banners -->
        <div class="grid grid-cols-2 gap-2">
            <div
                v-for="(sb, i) in smallBanners"
                :key="i"
                class="relative pb-[50%] lg:pb-0 lg:col-span-2 bg-gray-100 rounded-lg shadow-md dark:bg-neutral-700 overflow-hidden"
            >
                <a href="#" class="absolute inset-0">
                    <img :src="sb.image_url" :alt="sb.title" class="absolute inset-0 w-full h-full object-cover" loading="lazy" />
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { banners, smallBanners } from '../data/banners.js';

const currentIndex = ref(0);
let autoPlayTimer = null;

function goTo(index) {
    currentIndex.value = index;
}

function next() {
    currentIndex.value = (currentIndex.value + 1) % banners.length;
}

onMounted(() => {
    autoPlayTimer = setInterval(next, 4000);
});

onUnmounted(() => {
    clearInterval(autoPlayTimer);
});
</script>
