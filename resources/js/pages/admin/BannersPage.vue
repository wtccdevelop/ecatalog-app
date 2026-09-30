<template>
    <div>
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <div class="inline-flex rounded-lg border border-gray-200 dark:border-neutral-600 overflow-hidden text-sm">
                <button
                    v-for="t in tabs"
                    :key="t.value"
                    type="button"
                    @click="typeFilter = t.value"
                    class="px-4 py-2 font-semibold transition-colors"
                    :class="typeFilter === t.value
                        ? 'bg-blue-600 text-white'
                        : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700'"
                >
                    {{ t.label }}
                </button>
            </div>
            <button
                type="button"
                @click="openCreate"
                class="ml-auto px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Banner
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Jadwal</th>
                        <th class="px-4 py-3">Urutan</th>
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
                    <tr v-for="b in filtered" :key="b.id">
                        <td class="px-4 py-2">
                            <img :src="b.image" :alt="b.title" class="h-14 w-28 rounded-lg object-cover bg-gray-100 dark:bg-neutral-700" />
                        </td>
                        <td class="px-4 py-2 max-w-xs">
                            <p class="font-semibold truncate">{{ b.title || '-' }}</p>
                            <p v-if="b.link_url" class="text-[11px] text-gray-500 dark:text-neutral-400 truncate">{{ b.link_url }}</p>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                {{ b.type === 'main' ? 'Utama' : 'Kecil' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-xs text-gray-600 dark:text-neutral-300 whitespace-nowrap">
                            <template v-if="b.starts_at || b.ends_at">
                                <p>Mulai: {{ fmt(b.starts_at) }}</p>
                                <p>Akhir: {{ fmt(b.ends_at) }}</p>
                            </template>
                            <span v-else class="text-gray-400">Selalu tampil</span>
                        </td>
                        <td class="px-4 py-2">{{ b.sort_order }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggle(b)"
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold"
                                :class="stateStyle[b.state].cls"
                            >
                                {{ stateStyle[b.state].label }}
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

        <!-- Modal form -->
        <AdminModal v-model="showForm" :title="editId ? 'Edit Banner' : 'Tambah Banner'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label :class="lbl">Tipe Banner</label>
                    <select v-model="form.type" :class="input">
                        <option value="main">Utama (carousel besar)</option>
                        <option value="small">Kecil (samping carousel)</option>
                    </select>
                    <p class="mt-1 text-[11px] text-gray-500">
                        {{ form.type === 'main' ? 'Rekomendasi rasio ±1600×543 px.' : 'Rekomendasi rasio 2:1 (mis. 800×400 px).' }}
                    </p>
                    <p v-if="errors.type" :class="err">{{ errors.type[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Judul (opsional)</label>
                    <input v-model.trim="form.title" type="text" :class="input" />
                    <p v-if="errors.title" :class="err">{{ errors.title[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Link tujuan (opsional)</label>
                    <input v-model.trim="form.link_url" type="text" placeholder="https://... atau /product/iphone-air" :class="input" />
                    <p v-if="errors.link_url" :class="err">{{ errors.link_url[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Gambar</label>
                    <div class="flex items-center gap-3">
                        <img v-if="preview" :src="preview" alt="Preview" class="h-16 w-28 rounded-lg object-cover border border-gray-200 dark:border-neutral-600" />
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="onFile" class="text-xs text-gray-600 dark:text-gray-300" />
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">PNG/JPG/WEBP, maks. 3 MB.</p>
                    <p v-if="errors.image" :class="err">{{ errors.image[0] }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label :class="lbl">Mulai tayang</label>
                        <input v-model="form.starts_at" type="datetime-local" :class="input" />
                        <p v-if="errors.starts_at" :class="err">{{ errors.starts_at[0] }}</p>
                    </div>
                    <div>
                        <label :class="lbl">Berakhir</label>
                        <input v-model="form.ends_at" type="datetime-local" :class="input" />
                        <p v-if="errors.ends_at" :class="err">{{ errors.ends_at[0] }}</p>
                    </div>
                </div>
                <p class="-mt-2 text-[11px] text-gray-500">Kosongkan kedua tanggal jika banner ingin selalu tampil.</p>

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

const stateStyle = {
    live:      { label: 'Tayang',    cls: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' },
    scheduled: { label: 'Terjadwal', cls: 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400' },
    expired:   { label: 'Berakhir',  cls: 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' },
    inactive:  { label: 'Nonaktif',  cls: 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300' },
};

const tabs = [
    { value: '',      label: 'Semua' },
    { value: 'main',  label: 'Utama' },
    { value: 'small', label: 'Kecil' },
];

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/banners');

const typeFilter = ref('');
const filtered = computed(() =>
    items.value.filter((b) => !typeFilter.value || b.type === typeFilter.value)
);

const fmt = (v) => (v ? v.replace('T', ' ') : '-');

const showForm = ref(false);
const editId = ref(null);
const preview = ref(null);
const form = reactive({
    type: 'main', title: '', link_url: '', sort_order: 0,
    is_active: true, starts_at: '', ends_at: '', image: null,
});

function openCreate() {
    editId.value = null;
    Object.assign(form, {
        type: typeFilter.value || 'main', title: '', link_url: '',
        sort_order: items.value.length + 1, is_active: true,
        starts_at: '', ends_at: '', image: null,
    });
    preview.value = null;
    errors.value = {};
    showForm.value = true;
}

function openEdit(b) {
    editId.value = b.id;
    Object.assign(form, {
        type: b.type, title: b.title ?? '', link_url: b.link_url ?? '',
        sort_order: b.sort_order, is_active: b.is_active,
        starts_at: b.starts_at ?? '', ends_at: b.ends_at ?? '', image: null,
    });
    preview.value = b.image;
    errors.value = {};
    showForm.value = true;
}

function onFile(e) {
    const f = e.target.files[0];
    if (!f) return;
    form.image = f;
    preview.value = URL.createObjectURL(f);
}

async function submit() {
    if (saving.value) return;
    if (await save({ ...form }, editId.value)) showForm.value = false;
}

async function toggle(b) {
    try {
        const d = await api(`/admin/banners/${b.id}/toggle`, { method: 'PATCH' });
        Object.assign(b, d);
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal mengubah status.');
    }
}

async function del(b) {
    if (confirm(`Hapus banner "${b.title || 'tanpa judul'}"?`)) await remove(b.id);
}

onMounted(load);
</script>