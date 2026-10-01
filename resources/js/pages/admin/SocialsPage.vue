<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari sosial media..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <button
                type="button"
                @click="openCreate"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Sosial Media
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Platform</th>
                        <th class="px-4 py-3">Label / Handle</th>
                        <th class="px-4 py-3">Link</th>
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
                    <tr v-for="s in filtered" :key="s.id">
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <svg v-if="socialIcons[s.platform]" class="size-5" fill="currentColor" :viewBox="socialIcons[s.platform].viewBox">
                                    <path :d="socialIcons[s.platform].path" />
                                </svg>
                                <span class="font-semibold capitalize">{{ s.platform }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <p class="font-semibold">{{ s.label || '-' }}</p>
                            <p v-if="s.handle" class="text-[11px] text-gray-500 dark:text-neutral-400">{{ s.handle }}</p>
                        </td>
                        <td class="px-4 py-2 max-w-xs">
                            <a :href="s.url" target="_blank" rel="noopener noreferrer" class="block truncate text-xs font-semibold text-blue-600 hover:underline">{{ s.url }}</a>
                        </td>
                        <td class="px-4 py-2">{{ s.sort_order }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggle(s)"
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold"
                                :class="s.is_active
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400'
                                    : 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300'"
                            >
                                {{ s.is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <button type="button" @click="openEdit(s)" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</button>
                            <button type="button" @click="del(s)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <AdminModal v-model="showForm" :title="editId ? 'Edit Sosial Media' : 'Tambah Sosial Media'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label :class="lbl">Platform</label>
                    <select v-model="form.platform" :class="input">
                        <option value="" disabled>Pilih platform</option>
                        <option v-for="p in platforms" :key="p.value" :value="p.value">{{ p.label }}</option>
                    </select>
                    <p v-if="errors.platform" :class="err">{{ errors.platform[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Label (opsional)</label>
                    <input v-model.trim="form.label" type="text" placeholder="mis. WTC Cell Jajag" :class="input" />
                    <p v-if="errors.label" :class="err">{{ errors.label[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Handle (opsional)</label>
                    <input v-model.trim="form.handle" type="text" placeholder="mis. @wtccell" :class="input" />
                    <p v-if="errors.handle" :class="err">{{ errors.handle[0] }}</p>
                </div>

                <div>
                    <label :class="lbl">Link</label>
                    <input v-model.trim="form.url" type="url" placeholder="https://www.instagram.com/..." :class="input" />
                    <p v-if="errors.url" :class="err">{{ errors.url[0] }}</p>
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
import { socialIcons } from '../../data/socialIcons.js';
import AdminModal from '../../components/admin/AdminModal.vue';

const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const err   = 'mt-1 text-xs text-red-600';
const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const platforms = [
    { value: 'instagram', label: 'Instagram' },
    { value: 'facebook',  label: 'Facebook' },
    { value: 'tiktok',    label: 'TikTok' },
];

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/socials');

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter((s) =>
        [s.platform, s.label, s.handle].some((v) => (v ?? '').toLowerCase().includes(q))
    );
});

const showForm = ref(false);
const editId = ref(null);
const form = reactive({ platform: '', label: '', handle: '', url: '', sort_order: 0, is_active: true });

function openCreate() {
    editId.value = null;
    Object.assign(form, { platform: '', label: '', handle: '', url: '', sort_order: items.value.length + 1, is_active: true });
    errors.value = {};
    showForm.value = true;
}

function openEdit(s) {
    editId.value = s.id;
    Object.assign(form, {
        platform: s.platform, label: s.label ?? '', handle: s.handle ?? '',
        url: s.url, sort_order: s.sort_order, is_active: s.is_active,
    });
    errors.value = {};
    showForm.value = true;
}

async function submit() {
    if (saving.value) return;
    if (await save({ ...form }, editId.value)) showForm.value = false;
}

async function toggle(s) {
    try {
        const d = await api(`/admin/socials/${s.id}/toggle`, { method: 'PATCH' });
        s.is_active = d.is_active;
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal mengubah status.');
    }
}

async function del(s) {
    if (confirm(`Hapus sosial media "${s.label || s.platform}"?`)) await remove(s.id);
}

onMounted(load);
</script>