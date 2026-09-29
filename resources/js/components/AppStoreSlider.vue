<template>
    <div v-if="stores.length" class="py-8 px-4 sm:px-6 lg:px-16 bg-gray-100 dark:bg-neutral-700">
        <div class="max-w-[96rem] mx-auto">
            <div class="text-center mb-6">
                <h2 class="font-bold text-lg sm:text-2xl dark:text-white">Kunjungi Toko Kami</h2>
            </div>

            <div class="relative overflow-hidden rounded-2xl shadow-lg aspect-[16/7]">
                <transition-group name="fade" tag="div" class="relative w-full h-full">
                    <div
                        v-for="(store, i) in stores"
                        :key="store.id"
                        v-show="i === current"
                        class="absolute inset-0 w-full h-full"
                    >
                        <img
                            :src="store.photo"
                            :alt="store.name"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 md:p-6">
                            <p class="text-white font-semibold text-sm md:text-lg">{{ store.name }}</p>
                            <a
                                v-if="store.maps_url"
                                :href="store.maps_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-xs text-emerald-300 hover:text-emerald-100 mt-1 inline-block"
                            >
                                Lihat di Google Maps →
                            </a>
                        </div>
                    </div>
                </transition-group>

                <template v-if="stores.length > 1">
                    <button
                        @click="prev"
                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 z-10"
                        aria-label="Previous"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        @click="next"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 z-10"
                        aria-label="Next"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="absolute bottom-3 right-4 flex gap-1.5 z-10">
                        <button
                            v-for="(s, i) in stores"
                            :key="s.id"
                            @click="goTo(i)"
                            class="size-2 rounded-full transition-colors"
                            :class="i === current ? 'bg-white' : 'bg-white/40'"
                            :aria-label="`Slide ${i + 1}`"
                        />
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { catalog } from '../stores/catalog.js';

// hanya toko yang punya foto
const stores = computed(() => (catalog.home?.stores ?? []).filter((s) => s.photo));

const current = ref(0);
let timer = null;

function next()  { if (stores.value.length) current.value = (current.value + 1) % stores.value.length; }
function prev()  { if (stores.value.length) current.value = (current.value - 1 + stores.value.length) % stores.value.length; }
function goTo(i) { current.value = i; }

onMounted(()  => { timer = setInterval(next, 5000); });
onUnmounted(() => { clearInterval(timer); });
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.6s ease; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }
</style>