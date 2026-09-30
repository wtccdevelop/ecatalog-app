<template>
    <div>
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari produk..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <select
                v-model="brandFilter"
                class="px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Semua brand</option>
                <option v-for="b in brandNames" :key="b" :value="b">{{ b }}</option>
            </select>
            <RouterLink
                to="/admin/products/create"
                class="ml-auto px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Produk
            </RouterLink>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Brand</th>
                        <th class="px-4 py-3">Harga mulai</th>
                        <th class="px-4 py-3">Varian</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 dark:text-white">
                    <tr v-if="loading">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Memuat...</td>
                    </tr>
                    <tr v-else-if="!filtered.length">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                    <tr v-for="p in filtered" :key="p.id">
                        <td class="px-4 py-2">
                            <img v-if="p.image" :src="p.image" :alt="p.name" class="size-12 rounded-lg object-contain bg-gray-50 dark:bg-neutral-600" />
                            <div v-else class="size-12 rounded-lg bg-gray-100 dark:bg-neutral-700"></div>
                        </td>
                        <td class="px-4 py-2 font-semibold">{{ p.name }}</td>
                        <td class="px-4 py-2 text-gray-500 dark:text-neutral-400">{{ p.brand }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ p.min_price !== null ? formatRupiah(p.min_price) : '-' }}</td>
                        <td class="px-4 py-2">{{ p.variants_count }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggle(p)"
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold"
                                :class="p.is_active
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400'
                                    : 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300'"
                            >
                                {{ p.is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <RouterLink :to="`/admin/products/${p.id}/edit`" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</RouterLink>
                            <button type="button" @click="del(p)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { api } from '../../lib/api.js';
import { formatRupiah } from '../../lib/format.js';
import { useResource } from '../../composables/useResource.js';
import { toast } from '../../stores/toast.js';

const { items, loading, load, remove } = useResource('/admin/products');

const search = ref('');
const brandFilter = ref('');

const brandNames = computed(() => [...new Set(items.value.map((p) => p.brand))]);

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter(
        (p) => p.name.toLowerCase().includes(q) && (!brandFilter.value || p.brand === brandFilter.value)
    );
});

async function toggle(p) {
    try {
        const d = await api(`/admin/products/${p.id}/toggle`, { method: 'PATCH' });
        p.is_active = d.is_active;
    } catch (e) {
        toast.error('Gagal mengubah status.');
    }
}

async function del(p) {
    if (confirm(`Hapus produk "${p.name}"?\nSemua varian, spesifikasi, galeri, dan ulasan ikut terhapus.`)) {
        await remove(p.id);
    }
}

onMounted(load);
</script>