<template>
    <Transition name="greet">
        <div
            v-if="visible"
            class="fixed right-3 sm:right-5 z-40 flex items-end gap-2 max-w-[calc(100vw-1.5rem)]"
            :class="raised ? 'bottom-36 sm:bottom-52' : 'bottom-4 sm:bottom-5'"
            role="status"
            aria-live="polite"
            @mouseenter="pause"
            @mouseleave="schedule"
        >
            <!-- Bubble -->
            <div class="relative w-64 sm:w-72 rounded-2xl rounded-br-md bg-white dark:bg-neutral-800 px-4 py-3 shadow-lg ring-1 ring-black/5 dark:ring-white/10">
                <button
                    type="button"
                    @click="close"
                    class="absolute -top-2 -left-2 size-5 rounded-full bg-gray-200 text-[10px] leading-none text-gray-600 hover:bg-gray-300 dark:bg-neutral-600 dark:text-white"
                    aria-label="Tutup sapaan"
                >✕</button>

                <p class="text-sm font-bold text-gray-900 dark:text-white">Selamat datang di WTC Cell 👋</p>
                <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-neutral-300">
                    Silakan lihat etalase produk elektronik kami. Ingin mencoba langsung produk pajangannya?
                    Kami tunggu kedatangan Anda di toko ya!
                </p>

                <RouterLink
                    to="/products"
                    @click="close"
                    class="mt-2.5 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
                >
                    Lihat produk
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </RouterLink>
            </div>

            <!-- Karakter: huruf C + jempol dari logo -->
            <button
                type="button"
                @click="close"
                class="greet-float shrink-0 size-14 rounded-full bg-white shadow-lg ring-2 ring-blue-100 dark:ring-neutral-600 overflow-hidden"
                aria-label="Tutup sapaan"
            >
                <span class="greet-thumb block size-full" :style="avatarStyle"></span>
            </button>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    // naikkan posisi bila maskot event sedang tayang
    raised: { type: Boolean, default: false },
});

const KEY = 'wtc_greeted';
const SHOW_DELAY = 1500;  // muncul setelah 1,5 detik
const HIDE_AFTER = 12000; // hilang sendiri setelah 12 detik

const visible = ref(false);
let showTimer = null;
let hideTimer = null;

/*
 * Crop huruf "C" berjempol dari logo.
 * Bila posisinya kurang pas, ubah 3 angka ini saja.
 */
const LOGO_W = 1580;           // lebar asli gambar (px)
const LOGO_H = 660;            // tinggi asli gambar (px)
const CROP = { x: 640, y: 205, size: 270 }; // area yang diambil
const BOX = 56;                // ukuran avatar (px)
const k = BOX / CROP.size;

const avatarStyle = {
    backgroundImage: "url('/assets/images/redesain_logo.jpg')",
    backgroundRepeat: 'no-repeat',
    backgroundSize: `${LOGO_W * k}px ${LOGO_H * k}px`,
    backgroundPosition: `${-CROP.x * k}px ${-CROP.y * k}px`,
};

function schedule() {
    clearTimeout(hideTimer);
    hideTimer = setTimeout(close, HIDE_AFTER);
}
function pause() {
    clearTimeout(hideTimer);
}
function close() {
    visible.value = false;
    clearTimeout(hideTimer);
}

onMounted(() => {
    try { if (sessionStorage.getItem(KEY)) return; } catch (e) {}

    showTimer = setTimeout(() => {
        visible.value = true;
        try { sessionStorage.setItem(KEY, '1'); } catch (e) {}
        schedule();
    }, SHOW_DELAY);
});

onUnmounted(() => {
    clearTimeout(showTimer);
    clearTimeout(hideTimer);
});
</script>

<style scoped>
/* masuk: geser naik + fade, lembut */
.greet-enter-active { transition: opacity .5s ease, transform .6s cubic-bezier(.2, .8, .2, 1); }
.greet-leave-active { transition: opacity .35s ease, transform .35s ease; }
.greet-enter-from, .greet-leave-to { opacity: 0; transform: translateY(16px); }

/* karakter melayang pelan */
.greet-float { animation: greet-float 4s ease-in-out infinite; }
@keyframes greet-float {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-4px); }
}

/* jempol "menyapa": miring sedikit, hanya beberapa kali di awal */
.greet-thumb { transform-origin: 60% 70%; animation: greet-wave 1.6s ease-in-out .6s 2; }
@keyframes greet-wave {
    0%, 100% { transform: rotate(0); }
    25%      { transform: rotate(-9deg) scale(1.04); }
    60%      { transform: rotate(5deg); }
}

@media (prefers-reduced-motion: reduce) {
    .greet-float, .greet-thumb { animation: none; }
    .greet-enter-active, .greet-leave-active { transition: opacity .2s ease; }
    .greet-enter-from, .greet-leave-to { transform: none; }
}
</style>