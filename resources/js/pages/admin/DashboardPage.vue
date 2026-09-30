<template>
    <div>
        <div v-if="loading" class="text-sm text-gray-500 dark:text-neutral-400">Memuat...</div>

        <template v-else-if="data">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="c in cards" :key="c.label" class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-4">
                    <p class="text-xs text-gray-500 dark:text-neutral-400">{{ c.label }}</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900 dark:text-white">{{ c.value }}</p>
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-500 dark:text-neutral-400">
                Pilih menu di sidebar untuk mengelola konten katalog.
            </p>
        </template>

        <p v-else class="text-sm text-red-600">Gagal memuat dashboard.</p>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { api } from '../../lib/api.js';

const data = ref(null);
const loading = ref(true);

const cards = computed(() => [
    { label: 'Total Brand',           value: data.value.counts.brands },
    { label: 'Total Produk',          value: data.value.counts.products },
    { label: 'Banner',                value: data.value.counts.banners },
    { label: 'Toko',                  value: data.value.counts.stores },
    { label: 'Pengunjung Hari Ini',   value: data.value.visitors_today },
    { label: 'Page View Hari Ini',    value: data.value.page_views_today },
]);

onMounted(async () => {
    try {
        data.value = await api('/admin/dashboard');
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
    } finally {
        loading.value = false;
    }
});
</script>