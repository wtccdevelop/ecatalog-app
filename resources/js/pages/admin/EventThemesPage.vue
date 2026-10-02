<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <p class="text-xs text-gray-500 dark:text-neutral-400">
                Tema tayang otomatis sesuai jadwal. Jadwal tidak boleh bentrok dengan event aktif lain.
            </p>
            <button type="button" @click="openCreate" class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700">
                + Tambah Event
            </button>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-neutral-900 text-xs uppercase text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3">Event</th>
                        <th class="px-4 py-3">Tema</th>
                        <th class="px-4 py-3">Jadwal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 dark:text-white">
                    <tr v-if="loading"><td colspan="5" class="px-4 py-8 text-center text-gray-500">Memuat...</td></tr>
                    <tr v-else-if="!items.length"><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada event.</td></tr>
                    <tr v-for="e in items" :key="e.id">
                        <td class="px-4 py-2 font-semibold">{{ e.name }}</td>
                        <td class="px-4 py-2">{{ themeLabel(e.theme) }}</td>
                        <td class="px-4 py-2 text-xs text-gray-600 dark:text-neutral-300 whitespace-nowrap">
                            <p>Mulai: {{ fmt(e.starts_at) }}</p>
                            <p>Selesai: {{ fmt(e.ends_at) }}</p>
                        </td>
                        <td class="px-4 py-2">
                            <button type="button" @click="toggle(e)" class="px-2.5 py-1 rounded-full text-[11px] font-bold" :class="stateStyle[e.state].cls">
                                {{ stateStyle[e.state].label }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <button type="button" @click="openEdit(e)" class="px-3 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">Edit</button>
                            <button type="button" @click="del(e)" class="px-3 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <AdminModal v-model="showForm" :title="editId ? 'Edit Event' : 'Tambah Event'">
            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div v-if="errorList.length" class="rounded-lg bg-red-50 dark:bg-red-950/40 px-4 py-3 text-xs text-red-700 dark:text-red-400" role="alert">
                    <p v-for="(m, i) in errorList" :key="i">{{ m }}</p>
                </div>

                <div>
                    <label :class="lbl">Tema</label>
                    <select v-model="form.theme" @change="autoName" :class="input">
                        <option value="" disabled>Pilih tema</option>
                        <option v-for="t in eventThemeOptions" :key="t.value" :value="t.value">{{ t.label }}</option>
                    </select>
                </div>

                <div>
                    <label :class="lbl">Nama Event</label>
                    <input v-model.trim="form.name" type="text" placeholder="mis. Natal 2026" :class="input" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label :class="lbl">Mulai tayang</label>
                        <input v-model="form.starts_at" type="datetime-local" :class="input" />
                    </div>
                    <div>
                        <label :class="lbl">Selesai</label>
                        <input v-model="form.ends_at" type="datetime-local" :class="input" />
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" />
                    Aktifkan jadwal event ini
                </label>

                <!-- Pratinjau -->
                <div>
                    <p :class="lbl">Pratinjau card</p>
                    <div class="relative mx-auto w-44 rounded-2xl bg-white dark:bg-neutral-700 ring-1 ring-black/5 shadow-sm p-3">
                        <EventDecor :theme="form.theme" />
                        <div class="aspect-square rounded-xl bg-gray-100 dark:bg-neutral-600 flex items-center justify-center text-[10px] text-gray-400">Foto HP</div>
                        <p class="mt-2 text-xs font-bold text-gray-800 dark:text-white">IPHONE 17</p>
                        <p class="text-sm font-extrabold text-green-600">Rp 17.999.000</p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showForm = false" class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 text-gray-800 dark:border-neutral-600 dark:text-white">Batal</button>
                    <button type="submit" :disabled="saving" class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60">
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
import { eventThemeOptions } from '../../data/eventThemes.js';
import AdminModal from '../../components/admin/AdminModal.vue';
import EventDecor from '../../components/event/EventDecor.vue';

const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const stateStyle = {
    live:      { label: 'Tayang',    cls: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' },
    scheduled: { label: 'Terjadwal', cls: 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400' },
    expired:   { label: 'Berakhir',  cls: 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' },
    inactive:  { label: 'Nonaktif',  cls: 'bg-gray-200 text-gray-600 dark:bg-neutral-700 dark:text-neutral-300' },
};

const { items, loading, saving, errors, load, save, remove } = useResource('/admin/event-themes');

const themeLabel = (v) => eventThemeOptions.find((t) => t.value === v)?.label ?? v;
const fmt = (v) => (v ? v.replace('T', ' ') : '-');
const errorList = computed(() => Object.values(errors.value).flat());

const showForm = ref(false);
const editId = ref(null);
const form = reactive({ name: '', theme: '', starts_at: '', ends_at: '', is_active: true });

function autoName() {
    if (!form.name) form.name = themeLabel(form.theme).replace(/ \(.*\)/, '');
}

function openCreate() {
    editId.value = null;
    Object.assign(form, { name: '', theme: '', starts_at: '', ends_at: '', is_active: true });
    errors.value = {};
    showForm.value = true;
}

function openEdit(e) {
    editId.value = e.id;
    Object.assign(form, { name: e.name, theme: e.theme, starts_at: e.starts_at, ends_at: e.ends_at, is_active: e.is_active });
    errors.value = {};
    showForm.value = true;
}

async function submit() {
    if (saving.value) return;

    if (await save({ ...form }, editId.value)) {
        showForm.value = false;
        return;
    }

    // notifikasi bila gagal (mis. jadwal bentrok)
    const msg = errorList.value[0];
    if (msg) toast.error(msg);
}

async function toggle(e) {
    try {
        Object.assign(e, await api(`/admin/event-themes/${e.id}/toggle`, { method: 'PATCH' }));
    } catch (err) {
        if (err.status === 401) window.location.href = '/login';
        else if (err.status === 422) toast.error(Object.values(err.data?.errors ?? {}).flat()[0] ?? 'Jadwal bentrok.');
        else toast.error('Gagal mengubah status.');
    }
}

async function del(e) {
    if (confirm(`Hapus event "${e.name}"?`)) await remove(e.id);
}

onMounted(load);
</script>