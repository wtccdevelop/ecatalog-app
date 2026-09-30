<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Cari brand..."
                class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
            />
            <button
                type="button"
                @click="openCreate"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700"
            >
                + Tambah Brand
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Logo</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Produk</th>
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
                            <img v-if="b.logo" :src="b.logo" :alt="b.name" class="size-10 rounded-lg object-cover" />
                            <div v-else class="size-10 rounded-lg bg-gray-100 dark:bg-neutral-700"></div>
                        </td>
                        <td class="px-4 py-2 font-semibold">{{ b.name }}</td>
                        <td class="px-4 py-2 text-gray-500 dark:text-neutral-400">{{ b.slug }}</td>
                        <td class="px-4 py-2">{{ b.products_count }}</td>
                        <td class="px-4 py-2">{{ b.sort_order }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                @click="toggleActive(b)"
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

        <!-- Modal form -->
        <AdminModal v-model="showForm" :title="editId ? 'Edit Brand' : 'Tambah Brand'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Brand</label>
                    <input
                        v-model.trim="form.name"
                        type="text"
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="errors.name || errors.slug" class="mt-1 text-xs text-red-600">{{ (errors.name || errors.slug)[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                    <input
                        v-model.number="form.sort_order"
                        type="number"
                        min="0"
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="errors.sort_order" class="mt-1 text-xs text-red-600">{{ errors.sort_order[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                    <div class="flex items-center gap-3">
                        <img v-if="preview" :src="preview" alt="Preview" class="size-16 rounded-lg object-cover border border-gray-200 dark:border-neutral-600" />
                        <input
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            @change="onFile"
                            class="text-xs text-gray-600 dark:text-gray-300"
                        />
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">PNG/JPG/WEBP, maks. 2 MB.</p>
                    <p v-if="errors.logo" class="mt-1 text-xs text-red-600">{{ errors.logo[0] }}</p>
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
import { useResource } from '../../composables/useResource.js';
import AdminModal from '../../components/admin/AdminModal.vue';

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/brands');

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return items.value.filter((b) => b.name.toLowerCase().includes(q));
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

function openEdit(b) {
    editId.value = b.id;
    Object.assign(form, { name: b.name, sort_order: b.sort_order, is_active: b.is_active, logo: null });
    preview.value = b.logo;
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

function toggleActive(b) {
    save({ name: b.name, sort_order: b.sort_order, is_active: !b.is_active }, b.id);
}

async function del(b) {
    const msg = `Hapus brand "${b.name}"?` + (b.products_count ? `\n${b.products_count} produk di brand ini juga akan terhapus.` : '');
    if (confirm(msg)) await remove(b.id);
}

onMounted(load);
</script>