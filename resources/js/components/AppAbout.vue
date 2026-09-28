<template>
    <div id="about" class="py-10 px-4 sm:px-6 lg:px-16 bg-white dark:bg-neutral-800">
        <div class="max-w-[96rem] mx-auto">
            <div class="mb-4">
                <h2 class="text-lg font-extrabold tracking-widest dark:text-white">TENTANG KAMI</h2>
                <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                        <strong class="text-gray-900 dark:text-white">WTC Cell</strong> adalah toko gadget &amp; smartphone terpercaya di Banyuwangi. Berkomitmen memberikan produk 100% original dengan harga terbaik dan pelayanan prima.
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                        Kami hadir dengan berbagai brand ternama: Apple, Samsung, Oppo, Vivo, Xiaomi, Realme, Infinix, dan Tecno. Tersedia pilihan pembelian tunai maupun kredit dengan cicilan ringan.
                    </p>
                    <div class="flex items-start gap-3 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-teal-700 dark:text-teal-400 mt-0.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">Jam Operasional</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Senin – Minggu: 08.00 – 21.00 WITA</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-teal-700 dark:text-teal-400 mt-0.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">WhatsApp</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-xs text-teal-600 dark:text-teal-400 hover:underline">+62 812-3456-7890</a>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <img
                        v-for="store in storeLocations.slice(0,4)"
                        :key="store.title"
                        :src="store.image"
                        :alt="store.title"
                        class="w-full h-32 object-cover rounded-xl shadow-sm"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { storeLocations } from '../data/stores.js';
</script>
