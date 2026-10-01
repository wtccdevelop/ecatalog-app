<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari kategori..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <button
                type="button"
                @click="openCreate"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Kategori
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Rentang Harga</th>
                        <th class="px-4 py-3">Urutan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 dark:text-white">
                    <tr v-if="loading">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Memuat...</td>
                    </tr>
                    <tr v-else-if="!filtered.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                    <tr v-for="c in filtered" :key="c.id">
                        <td class="px-4 py-2 font-semibold">{{ c.name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-600 dark:text-neutral-300 whitespace-nowrap">
                            {{ rangeLabel(c) }}
                        </td>
                        <td class="px-4 py-2">{{ c.sort_order }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggle(c)"
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold"
                                :class="c.is_active
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400'
                                    : 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300'"
                            >
                                {{ c.is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <button type="button" @click="openEdit(c)" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</button>
                            <button type="button" @click="del(c)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <AdminModal v-model="showForm" :title="editId ? 'Edit Kategori Harga' : 'Tambah Kategori Harga'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label :class="lbl">Nama Kategori</label>
                    <input v-model.trim="form.name" type="text" placeholder="mis. 2 Jutaan" :class="input" />
                    <p v-if="errors.name" :class="err">{{ errors.name[0] }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label :class="lbl">Harga Minimum (Rp)</label>
                        <input v-model.number="form.min_price" type="number" min="0" :class="input" />
                        <p v-if="form.min_price" class="mt-0.5 text-[10px] text-gray-500">{{ formatRupiah(form.min_price) }}</p>
                        <p v-if="errors.min_price" :class="err">{{ errors.min_price[0] }}</p>
                    </div>
                    <div>
                        <label :class="lbl">Harga Maksimum (Rp)</label>
                        <input v-model.number="form.max_price" type="number" min="0" :class="input" />
                        <p v-if="form.max_price" class="mt-0.5 text-[10px] text-gray-500">{{ formatRupiah(form.max_price) }}</p>
                        <p v-if="errors.max_price" :class="err">{{ errors.max_price[0] }}</p>
                    </div>
                </div>
                <p class="-mt-2 text-[11px] text-gray-500">
                    Kosongkan maksimum untuk "ke atas" (mis. 5 Jutaan+). Kosongkan keduanya untuk kategori non-harga (mis. Flagship).
                </p>

                <div>
                    <label :class="lbl">Urutan</label>
                    <input v-model.number="form.sort_order" type="number" min="0" :class="input" />
                    <p v-if="errors.sort_order" :class="err">{{ errors.sort_order[0] }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" />
                    Tampilkan di website
                </label>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showForm = false" class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 text-gray-800 dark:border-neutral-600 dark:text-white">
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60"
                    >
                        {{ saving ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </AdminModal>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { api } from '../../lib/api.js';
import { formatRupiah } from '../../lib/format.js';
import { useResource } from '../../composables/useResource.js';
import { toast } from '../../stores/toast.js';
import AdminModal from '../../components/admin/AdminModal.vue';

const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const err   = 'mt-1 text-xs text-red-600';
const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/price-categories');

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter((c) => c.name.toLowerCase().includes(q));
});

function rangeLabel(c) {
    if (c.min_price === null && c.max_price === null) return '-';
    if (c.max_price === null) return `≥ ${formatRupiah(c.min_price)}`;
    if (c.min_price === null) return `≤ ${formatRupiah(c.max_price)}`;
    return `${formatRupiah(c.min_price)} – ${formatRupiah(c.max_price)}`;
}

const showForm = ref(false);
const editId = ref(null);
const form = reactive({ name: '', min_price: '', max_price: '', sort_order: 0, is_active: true });

function openCreate() {
    editId.value = null;
    Object.assign(form, { name: '', min_price: '', max_price: '', sort_order: items.value.length + 1, is_active: true });
    errors.value = {};
    showForm.value = true;
}

function openEdit(c) {
    editId.value = c.id;
    Object.assign(form, {
        name: c.name,
        min_price: c.min_price ?? '',
        max_price: c.max_price ?? '',
        sort_order: c.sort_order,
        is_active: c.is_active,
    });
    errors.value = {};
    showForm.value = true;
}

async function submit() {
    if (saving.value) return;
    if (await save({ ...form }, editId.value)) showForm.value = false;
}

async function toggle(c) {
    try {
        const d = await api(`/admin/price-categories/${c.id}/toggle`, { method: 'PATCH' });
        c.is_active = d.is_active;
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal mengubah status.');
    }
}

async function del(c) {
    if (confirm(`Hapus kategori "${c.name}"?`)) await remove(c.id);
}

onMounted(load);
</script>