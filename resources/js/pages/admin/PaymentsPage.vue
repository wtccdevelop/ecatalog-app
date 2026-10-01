<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari metode pembayaran..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <button
                type="button"
                @click="openCreate"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Metode
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Logo</th>
                        <th class="px-4 py-3">Nama</th>
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
                    <tr v-for="p in filtered" :key="p.id">
                        <td class="px-4 py-2">
                            <div class="h-10 w-20 rounded-lg bg-white border border-gray-100 dark:border-neutral-600 flex items-center justify-center px-1">
                                <img v-if="p.logo" :src="p.logo" :alt="p.name" class="max-h-full max-w-full object-contain" />
                            </div>
                        </td>
                        <td class="px-4 py-2 font-semibold">{{ p.name }}</td>
                        <td class="px-4 py-2">{{ p.sort_order }}</td>
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
                            <button type="button" @click="openEdit(p)" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</button>
                            <button type="button" @click="del(p)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <AdminModal v-model="showForm" :title="editId ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label :class="lbl">Nama</label>
                    <input v-model.trim="form.name" type="text" placeholder="mis. Bank BCA" :class="input" />
                    <p v-if="errors.name" :class="err">{{ errors.name[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Logo</label>
                    <div class="flex items-center gap-3">
                        <div v-if="preview" class="h-14 w-24 rounded-lg bg-white border border-gray-200 dark:border-neutral-600 flex items-center justify-center px-1">
                            <img :src="preview" alt="Preview" class="max-h-full max-w-full object-contain" />
                        </div>
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="onFile" class="text-xs text-gray-600 dark:text-gray-300" />
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">PNG/JPG/WEBP, maks. 1 MB. Disarankan latar putih/transparan.</p>
                    <p v-if="errors.logo" :class="err">{{ errors.logo[0] }}</p>
                </div>

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
import { useResource } from '../../composables/useResource.js';
import { toast } from '../../stores/toast.js';
import AdminModal from '../../components/admin/AdminModal.vue';

const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const err   = 'mt-1 text-xs text-red-600';
const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/payments');

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter((p) => p.name.toLowerCase().includes(q));
});

const showForm = ref(false);
const editId = ref(null);
const preview = ref(null);
const form = reactive({ name: '', sort_order: 0, is_active: true, logo: null });

function openCreate() {
    editId.value = null;
    Object.assign(form, { name: '', sort_order: items.value.length + 1, is_active: true, logo: null });
    preview.value = null;
    errors.value = {};
    showForm.value = true;
}

function openEdit(p) {
    editId.value = p.id;
    Object.assign(form, { name: p.name, sort_order: p.sort_order, is_active: p.is_active, logo: null });
    preview.value = p.logo;
    errors.value = {};
    showForm.value = true;
}

function onFile(e) {
    const f = e.target.files[0];
    if (!f) return;
    form.logo = f;
    preview.value = URL.createObjectURL(f);
}

async function submit() {
    if (saving.value) return;
    if (await save({ ...form }, editId.value)) showForm.value = false;
}

async function toggle(p) {
    try {
        const d = await api(`/admin/payments/${p.id}/toggle`, { method: 'PATCH' });
        p.is_active = d.is_active;
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal mengubah status.');
    }
}

async function del(p) {
    if (confirm(`Hapus metode pembayaran "${p.name}"?`)) await remove(p.id);
}

onMounted(load);
</script>