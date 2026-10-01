<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari benefit..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <button
                type="button"
                @click="openCreate"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Benefit
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Ikon</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Urutan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 dark:text-white">
                    <tr v-if="loading">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Memuat...</td>
                    </tr>
                    <tr v-else-if="!filtered.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                    <tr v-for="b in filtered" :key="b.id">
                        <td class="px-4 py-2">
                            <img v-if="b.icon" :src="b.icon" :alt="b.title" class="size-10 object-contain" />
                            <div v-else class="size-10 rounded-lg bg-gray-100 dark:bg-neutral-700"></div>
                        </td>
                        <td class="px-4 py-2 font-semibold">{{ b.title }}</td>
                        <td class="px-4 py-2 max-w-xs text-xs text-gray-600 dark:text-neutral-300">
                            <p class="line-clamp-2">{{ b.description || '-' }}</p>
                        </td>
                        <td class="px-4 py-2">{{ b.sort_order }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggle(b)"
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold"
                                :class="b.is_active
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400'
                                    : 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300'"
                            >
                                {{ b.is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <button type="button" @click="openEdit(b)" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</button>
                            <button type="button" @click="del(b)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <AdminModal v-model="showForm" :title="editId ? 'Edit Benefit' : 'Tambah Benefit'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label :class="lbl">Judul</label>
                    <input v-model.trim="form.title" type="text" placeholder="mis. Gratis Ongkir" :class="input" />
                    <p v-if="errors.title" :class="err">{{ errors.title[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Deskripsi singkat</label>
                    <input v-model.trim="form.description" type="text" placeholder="mis. Min. pembelian Rp. 1 juta" :class="input" />
                    <p v-if="errors.description" :class="err">{{ errors.description[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Ikon</label>
                    <div class="flex items-center gap-3">
                        <img v-if="preview" :src="preview" alt="Preview" class="size-14 object-contain rounded-lg border border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-600" />
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="onFile" class="text-xs text-gray-600 dark:text-gray-300" />
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">PNG/JPG/WEBP, maks. 1 MB. Disarankan persegi (mis. 128×128 px).</p>
                    <p v-if="errors.icon" :class="err">{{ errors.icon[0] }}</p>
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

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/benefits');

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter((b) => b.title.toLowerCase().includes(q));
});

const showForm = ref(false);
const editId = ref(null);
const preview = ref(null);
const form = reactive({ title: '', description: '', sort_order: 0, is_active: true, icon: null });

function openCreate() {
    editId.value = null;
    Object.assign(form, { title: '', description: '', sort_order: items.value.length + 1, is_active: true, icon: null });
    preview.value = null;
    errors.value = {};
    showForm.value = true;
}

function openEdit(b) {
    editId.value = b.id;
    Object.assign(form, {
        title: b.title, description: b.description ?? '',
        sort_order: b.sort_order, is_active: b.is_active, icon: null,
    });
    preview.value = b.icon;
    errors.value = {};
    showForm.value = true;
}

function onFile(e) {
    const f = e.target.files[0];
    if (!f) return;
    form.icon = f;
    preview.value = URL.createObjectURL(f);
}

async function submit() {
    if (saving.value) return;
    if (await save({ ...form }, editId.value)) showForm.value = false;
}

async function toggle(b) {
    try {
        const d = await api(`/admin/benefits/${b.id}/toggle`, { method: 'PATCH' });
        b.is_active = d.is_active;
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal mengubah status.');
    }
}

async function del(b) {
    if (confirm(`Hapus benefit "${b.title}"?`)) await remove(b.id);
}

onMounted(load);
</script>