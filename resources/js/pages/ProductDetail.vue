<template>
    <div class="bg-gray-100 dark:bg-neutral-700 min-h-[60vh]">
        <div class="max-w-[96rem] mx-auto">
            <div class="container mx-auto px-4 py-6">

                <!-- Produk tidak ditemukan -->
                <div v-if="!detail" class="max-w-6xl mx-auto text-center py-20 dark:text-white">
                    <h1 class="text-xl font-bold mb-2">Produk tidak ditemukan</h1>
                    <RouterLink to="/" class="text-sm text-blue-600 hover:underline">← Kembali ke beranda</RouterLink>
                </div>

                <template v-else>
                    <!-- Breadcrumb -->
                    <nav class="max-w-6xl mx-auto mb-3 flex items-center gap-1.5 text-xs text-gray-500 dark:text-neutral-400">
                        <RouterLink to="/" class="hover:text-blue-600">Beranda</RouterLink>
                        <span>/</span>
                        <RouterLink :to="{ path: '/', hash: '#product' }" class="hover:text-blue-600">{{ detail.brand }}</RouterLink>
                        <span>/</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ detail.name }}</span>
                    </nav>

                    <!-- Kartu utama -->
                    <div class="max-w-6xl mx-auto bg-white dark:bg-neutral-800 shadow-md rounded-lg overflow-hidden">
                        <div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-6">

                            <!-- Gambar -->
                            <div class="md:w-1/2 p-6 flex flex-col items-center">
                                <img
                                    class="h-auto w-full object-contain md:max-w-lg dark:bg-neutral-500 rounded-lg"
                                    :src="detail.image"
                                    :alt="`${detail.name} ${variantLabel}`"
                                />
                            </div>

                            <!-- Info -->
                            <div class="md:w-1/2 p-6">
                                <h1 class="block text-base md:text-xl leading-tight font-extrabold text-gray-900 dark:text-white mb-4">
                                    {{ detail.name }}
                                    <span v-if="variantLabel" class="text-gray-600 dark:text-gray-400 text-sm ml-2">({{ variantLabel }})</span>
                                </h1>

                                <!-- RAM / Penyimpanan -->
                                <div v-if="combos[0]" class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pilih RAM / Penyimpanan:</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="combo in combos"
                                            :key="combo"
                                            type="button"
                                            @click="selectCombo(combo)"
                                            class="px-3 py-1 rounded-full text-sm font-medium transition-colors"
                                            :class="combo === selectedCombo
                                                ? 'bg-blue-600 text-white'
                                                : 'bg-gray-200 text-gray-800 hover:bg-gray-300'"
                                        >
                                            {{ combo }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Warna -->
                                <div v-if="colors[0]" class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pilih Warna:</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="color in colors"
                                            :key="color"
                                            type="button"
                                            @click="selectedColor = color"
                                            class="px-3 py-1 rounded-full text-sm font-medium transition-colors"
                                            :class="color === selectedColor
                                                ? 'bg-blue-600 text-white'
                                                : 'bg-gray-200 text-gray-800 hover:bg-gray-300'"
                                        >
                                            {{ color }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Harga -->
                                <div class="mb-4 bg-blue-50 dark:bg-neutral-900 px-4 py-4 rounded-lg">
                                    <p class="text-gray-700 dark:text-white font-semibold mb-1 text-xs">Harga:</p>
                                    <p class="font-extrabold text-blue-600 text-lg dark:text-blue-400">{{ formatRupiah(activeVariant.price) }}</p>
                                    <p class="mt-1 text-[11px] text-gray-500 dark:text-neutral-400">
                                        Stok tersedia: {{ activeVariant.stock }} unit
                                    </p>
                                </div>

                                <!-- Cicilan -->
                                <div class="mb-2">
                                    <p class="text-gray-700 dark:text-gray-300 font-semibold text-xs">Cicilan mulai:</p>
                                    <p class="text-lg text-gray-800 dark:text-gray-200">
                                        <span class="text-sm">24x</span>
                                        <span class="text-sm font-semibold">{{ formatRupiah(lowestInstallment.monthly) }}/bln</span>
                                        <button
                                            type="button"
                                            @click="showInstallment = true"
                                            class="text-sm text-blue-600 ml-2 hover:text-blue-500 font-medium mt-2"
                                        >
                                            Lihat Selengkapnya
                                        </button>
                                    </p>
                                </div>

                                <!-- Spesifikasi -->
                                <div class="mt-6">
                                    <h2 class="text-base font-bold text-gray-800 dark:text-white mb-3">Spesifikasi Lengkap</h2>
                                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-xs bg-gray-100 dark:bg-neutral-900 px-4 py-4 rounded-lg inset-shadow-sm">
                                        <div
                                            v-for="(spec, i) in detail.specs"
                                            :key="spec.label"
                                            class="flex flex-col pb-2"
                                            :class="i < detail.specs.length - 2 ? 'border-b border-gray-300 dark:border-gray-600' : ''"
                                        >
                                            <span class="text-gray-600 dark:text-gray-400">{{ spec.label }}</span>
                                            <span class="font-semibold text-gray-800 dark:text-white">{{ spec.value }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- CTA WhatsApp -->
                                <div class="mt-8">
                                    
                                        :href="waLink"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center justify-center w-full px-4 md:px-6 md:py-3.5 py-2.5 border border-transparent font-bold text-sm md:text-base rounded-xl shadow-lg text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 active:scale-98 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 ease-in-out gap-2.5 group"
                                    >
                                        <svg class="w-5 h-5 md:w-6 md:h-6 fill-current text-white group-hover:scale-110 transition-transform duration-300" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                        </svg>
                                        <span>Tanya Stok / Pesan via WhatsApp</span>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Galeri -->
                    <div class="max-w-6xl mx-auto mt-6">
                        <h2 class="md:text-2xl text-base font-bold dark:text-white">Galeri Produk</h2>
                        <div v-if="detail.gallery.length" class="flex flex-nowrap gap-2 overflow-x-auto pb-4 mt-3">
                            <img
                                v-for="(g, i) in detail.gallery"
                                :key="i"
                                :src="g"
                                :alt="`${detail.name} ${i + 1}`"
                                class="h-40 w-auto rounded-lg object-cover"
                                loading="lazy"
                            />
                        </div>
                        <div v-else class="flex flex-col items-center justify-center space-y-3 py-6 px-4 text-neutral-500">
                            <svg class="size-14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V4.5a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v15a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V9.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <h3 class="md:text-2xl text-base font-extrabold dark:text-neutral-400">Galeri belum tersedia</h3>
                        </div>
                    </div>

                    <!-- Ulasan -->
                    <div class="max-w-6xl mx-auto mt-6">
                        <h2 class="md:text-2xl text-base font-bold dark:text-white">Ulasan Produk</h2>
                        <div class="bg-white dark:bg-neutral-800 p-3 md:p-6 rounded-lg shadow-md mt-4">
                            <h4 class="text-base md:text-xl font-semibold md:mb-4 dark:text-white">Rating</h4>
                            <p v-if="!detail.reviews.length" class="text-gray-600 dark:text-gray-400 mb-3 md:mb-6">Belum ada rating untuk produk ini.</p>
                            <div v-else class="flex items-center gap-2 mb-3 md:mb-6">
                                <span class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ averageRating }}</span>
                                <div class="flex">
                                    <svg v-for="n in 5" :key="n" class="size-5" :class="n <= Math.round(averageRating) ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500">({{ detail.reviews.length }} ulasan)</span>
                            </div>

                            <div class="border-t border-gray-300 dark:border-neutral-600 pt-3 md:pt-6">
                                <h4 class="text-base md:text-xl font-semibold mb-2 md:mb-4 dark:text-white">Semua Ulasan</h4>
                                <div class="max-h-80 overflow-y-auto space-y-4">
                                    <p v-if="!detail.reviews.length" class="text-gray-600 dark:text-gray-400">Belum ada ulasan untuk produk ini.</p>
                                    <div v-for="r in detail.reviews" :key="r.name + r.date" class="border-b border-gray-100 dark:border-neutral-700 pb-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ r.name }}</p>
                                            <span class="text-[11px] text-gray-500">{{ r.date }}</span>
                                        </div>
                                        <div class="flex my-1">
                                            <svg v-for="n in 5" :key="n" class="size-4" :class="n <= r.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ r.comment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal cicilan -->
        <Teleport to="body">
            <div
                v-if="showInstallment && detail"
                class="fixed inset-0 z-80 flex items-center justify-center bg-black/50"
                @click.self="showInstallment = false"
            >
                <div class="relative w-full max-w-md m-3 bg-white dark:bg-neutral-800 rounded-xl shadow-2xl overflow-hidden">
                    <div class="flex flex-col text-center items-center py-5 px-4 bg-gradient-to-r from-blue-700 to-blue-900">
                        <h3 class="font-extrabold text-white text-lg">Simulasi Cicilan</h3>
                        <p class="mt-1 text-xs text-white/90">{{ detail.name }} {{ variantLabel }} · {{ formatRupiah(activeVariant.price) }}</p>
                    </div>
                    <button
                        @click="showInstallment = false"
                        class="absolute top-3 right-3 size-8 inline-flex justify-center items-center rounded-full bg-white/20 text-white hover:bg-white/30"
                        aria-label="Close"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                    <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                        <div
                            v-for="ins in installments"
                            :key="ins.tenure"
                            class="flex items-center justify-between p-3 border border-gray-200 dark:border-neutral-600 rounded-xl"
                        >
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ ins.name }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-neutral-400">{{ ins.note }} · Total {{ formatRupiah(ins.total) }}</p>
                            </div>
                            <p class="text-sm font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">{{ formatRupiah(ins.monthly) }}/bln</p>
                        </div>
                        <p class="text-[10px] text-gray-400 pt-1">*Estimasi, nominal final mengikuti ketentuan penyedia cicilan.</p>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import { getProductDetail, buildInstallments, formatRupiah, WA_NUMBER } from '../data/productDetails.js';

const route = useRoute();
const detail = computed(() => getProductDetail(route.params.slug));

const selectedCombo = ref('');
const selectedColor = ref(null);
const showInstallment = ref(false);

const comboKey = (v) => (v.ram ? `${v.ram}/${v.storage}` : '');

const combos = computed(() =>
    detail.value ? [...new Set(detail.value.variants.map(comboKey))] : []
);

const colors = computed(() =>
    detail.value
        ? detail.value.variants.filter((v) => comboKey(v) === selectedCombo.value).map((v) => v.color)
        : []
);

const activeVariant = computed(() => {
    const vs = detail.value?.variants ?? [];
    return (
        vs.find((v) => comboKey(v) === selectedCombo.value && v.color === selectedColor.value) ??
        vs[0] ??
        { price: 0, stock: 0 }
    );
});

const variantLabel = computed(() =>
    [activeVariant.value.storage, activeVariant.value.color].filter(Boolean).join(' - ')
);

const installments = computed(() => buildInstallments(activeVariant.value.price));
const lowestInstallment = computed(() => installments.value.find((i) => i.tenure === 24));

const averageRating = computed(() => {
    const rs = detail.value?.reviews ?? [];
    if (!rs.length) return 0;
    return (rs.reduce((s, r) => s + r.rating, 0) / rs.length).toFixed(1);
});

const waLink = computed(() => {
    const title = `${detail.value?.name ?? ''}${variantLabel.value ? ` (${variantLabel.value})` : ''}`;
    const text = `Halo Admin WTC Cell, saya ingin bertanya mengenai produk: *${title}* (${formatRupiah(activeVariant.value.price)}). Apakah produk ini masih tersedia?`;
    return `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(text)}`;
});

function selectCombo(combo) {
    selectedCombo.value = combo;
    if (!colors.value.includes(selectedColor.value)) selectedColor.value = colors.value[0];
}

// reset pilihan tiap pindah produk
watch(
    detail,
    (d) => {
        showInstallment.value = false;
        if (!d) return;
        const first = d.variants[0];
        selectedCombo.value = comboKey(first);
        selectedColor.value = first.color;
        document.title = `WTC Cell - ${d.name}`;
    },
    { immediate: true }
);
</script>