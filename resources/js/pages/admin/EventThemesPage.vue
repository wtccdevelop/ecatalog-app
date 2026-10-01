<template>
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Tema Event
                </h2>

                <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1">
                    Atur tema musiman yang aktif otomatis berdasarkan jadwal.
                </p>
            </div>

            <button
                type="button"
                @click="resetForm"
                class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold"
            >
                + Tambah Event
            </button>
        </div>

        <!-- Form -->
        <section class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white mb-5">
                {{ form.id ? 'Edit Event' : 'Tambah Event' }}
            </h3>

            <div
                v-if="errorList.length"
                class="mb-5 rounded-lg bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-400"
            >
                <ul class="list-disc pl-5 space-y-1">
                    <li v-for="(message, index) in errorList" :key="index">
                        {{ message }}
                    </li>
                </ul>
            </div>

            <div class="grid lg:grid-cols-2 gap-5">

                <div>
                    <label :class="label">
                        Nama Event
                    </label>

                    <input
                        v-model.trim="form.name"
                        type="text"
                        placeholder="Natal 2026"
                        :class="input"
                    />
                </div>

                <div>
                    <label :class="label">
                        Slug
                    </label>

                    <input
                        v-model.trim="form.slug"
                        type="text"
                        placeholder="natal-2026"
                        :class="input"
                    />
                </div>

                <div>
                    <label :class="label">
                        Tema
                    </label>

                    <select
                        v-model="form.theme"
                        :class="input"
                    >
                        <option value="christmas">
                            🎄 Natal
                        </option>

                        <option value="eid">
                            🌙 Lebaran
                        </option>

                        <option value="independence">
                            🇮🇩 Agustusan
                        </option>

                        <option value="new_year">
                            ✨ Tahun Baru
                        </option>
                    </select>
                </div>

                <div>
                    <label :class="label">
                        Status
                    </label>

                    <label class="flex items-center gap-3 h-11">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="size-4 rounded border-gray-300 text-blue-600"
                        />

                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Event boleh aktif sesuai jadwal
                        </span>
                    </label>
                </div>

                <div>
                    <label :class="label">
                        Mulai
                    </label>

                    <input
                        v-model="form.starts_at"
                        type="datetime-local"
                        :class="input"
                    />
                </div>

                <div>
                    <label :class="label">
                        Selesai
                    </label>

                    <input
                        v-model="form.ends_at"
                        type="datetime-local"
                        :class="input"
                    />
                </div>
            </div>

            <div class="mt-6 border-t border-gray-100 dark:border-neutral-700 pt-5">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">
                    Dekorasi
                </h4>

                <div class="space-y-3">

                    <label class="flex items-center gap-3">
                        <input
                            v-model="form.settings.show_product_decoration"
                            type="checkbox"
                            class="size-4 rounded border-gray-300 text-blue-600"
                        />

                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Dekorasi pada card produk
                        </span>
                    </label>

                    <label class="flex items-center gap-3">
                        <input
                            v-model="form.settings.show_header_decoration"
                            type="checkbox"
                            class="size-4 rounded border-gray-300 text-blue-600"
                        />

                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Dekorasi header
                        </span>
                    </label>

                    <label class="flex items-center gap-3">
                        <input
                            v-model="form.settings.show_background_effect"
                            type="checkbox"
                            class="size-4 rounded border-gray-300 text-blue-600"
                        />

                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Efek background
                        </span>
                    </label>

                </div>
            </div>

            <!-- Preview -->
            <div class="mt-6 border-t border-gray-100 dark:border-neutral-700 pt-5">

                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        Preview Tema
                    </h4>

                    <span class="text-xs text-gray-500">
                        Tidak mengaktifkan event sebenarnya
                    </span>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900 p-5"
                >
                    <div
                        class="text-center py-3"
                        :class="previewThemeClass"
                    >
                        <div class="text-3xl">
                            {{ previewEmoji }}
                        </div>

                        <p class="mt-1 text-sm font-bold">
                            {{ form.name || 'Nama Event' }}
                        </p>

                        <p class="text-xs opacity-70">
                            WTC Cell Supermarket Seluler
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5">

                        <div
                            v-for="n in 4"
                            :key="n"
                            class="relative bg-white dark:bg-neutral-800 rounded-xl border border-gray-200 dark:border-neutral-700 overflow-hidden"
                        >
                            <div class="h-24 bg-gray-100 dark:bg-neutral-700 flex items-center justify-center text-4xl">
                                📱
                            </div>

                            <div class="p-3">
                                <div class="h-3 w-20 rounded bg-gray-200 dark:bg-neutral-700"></div>

                                <div class="h-3 w-16 rounded bg-gray-200 dark:bg-neutral-700 mt-2"></div>

                                <div class="h-3 w-24 rounded bg-blue-100 mt-3"></div>
                            </div>

                            <span
                                v-if="form.settings.show_product_decoration"
                                class="absolute top-1 right-1 text-xl"
                            >
                                {{ previewEmoji }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">

                <button
                    v-if="form.id"
                    type="button"
                    @click="resetForm"
                    class="px-4 py-2 rounded-lg border border-gray-200 dark:border-neutral-600 text-sm font-semibold"
                >
                    Batal
                </button>

                <button
                    type="button"
                    :disabled="saving"
                    @click="save"
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold disabled:opacity-60"
                >
                    {{ saving ? 'Menyimpan...' : 'Simpan Event' }}
                </button>

            </div>
        </section>

        <!-- List -->
        <section class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b border-gray-100 dark:border-neutral-700">
                <h3 class="font-bold text-gray-900 dark:text-white">
                    Jadwal Event
                </h3>
            </div>

            <div v-if="loading" class="p-6 text-sm text-gray-500">
                Memuat event...
            </div>

            <div v-else-if="!events.length" class="p-8 text-center text-sm text-gray-500">
                Belum ada event.
            </div>

            <div v-else class="divide-y divide-gray-100 dark:divide-neutral-700">

                <div
                    v-for="event in events"
                    :key="event.id"
                    class="p-5 flex flex-col lg:flex-row lg:items-center gap-4"
                >

                    <div class="size-12 rounded-xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center text-2xl shrink-0">
                        {{ emoji(event.theme) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white">
                            {{ event.name }}
                        </h4>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ formatDate(event.starts_at) }}
                            →
                            {{ formatDate(event.ends_at) }}
                        </p>
                    </div>

                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-semibold w-fit"
                        :class="stateClass(event.state)"
                    >
                        {{ stateLabel(event.state) }}
                    </span>

                    <div class="flex items-center gap-2">

                        <button
                            type="button"
                            @click="edit(event)"
                            class="px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-600 text-xs font-semibold"
                        >
                            Edit
                        </button>

                        <button
                            type="button"
                            @click="toggle(event)"
                            class="px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-600 text-xs font-semibold"
                        >
                            {{ event.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>

                        <button
                            type="button"
                            @click="remove(event)"
                            class="px-3 py-2 rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 text-xs font-semibold"
                        >
                            Hapus
                        </button>

                    </div>
                </div>

            </div>
        </section>

    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { api } from '../../lib/api.js';
import { toast } from '../../stores/toast.js';

const events = ref([]);
const loading = ref(true);
const saving = ref(false);
const errors = ref({});

const label = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';

const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const emptyForm = () => ({
    id: null,
    name: '',
    slug: '',
    theme: 'christmas',
    starts_at: '',
    ends_at: '',
    is_active: true,
    settings: {
        show_product_decoration: true,
        show_header_decoration: true,
        show_background_effect: false,
    },
});

const form = reactive(emptyForm());

const errorList = computed(() => Object.values(errors.value).flat());

const previewEmoji = computed(() => emoji(form.theme));

const previewThemeClass = computed(() => ({
    'bg-red-50 text-red-800': form.theme === 'christmas',
    'bg-emerald-50 text-emerald-800': form.theme === 'eid',
    'bg-red-50 text-red-800': form.theme === 'independence',
    'bg-blue-50 text-blue-800': form.theme === 'new_year',
}));

function emoji(theme) {
    return {
        christmas: '🎄',
        eid: '🌙',
        independence: '🇮🇩',
        new_year: '✨',
    }[theme] ?? '🎉';
}

function stateLabel(state) {
    return {
        live: 'Sedang Aktif',
        scheduled: 'Terjadwal',
        expired: 'Selesai',
        inactive: 'Nonaktif',
    }[state] ?? state;
}

function stateClass(state) {
    return {
        live: 'bg-green-100 text-green-700',
        scheduled: 'bg-blue-100 text-blue-700',
        expired: 'bg-gray-100 text-gray-600',
        inactive: 'bg-gray-100 text-gray-500',
    }[state] ?? 'bg-gray-100 text-gray-600';
}

function formatDate(value) {
    if (!value) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

async function load() {
    loading.value = true;

    try {
        events.value = await api('/admin/event-themes');
    } catch (e) {
        if (e.status === 401) {
            window.location.href = '/login';
            return;
        }

        toast.error('Gagal memuat tema event.');
    } finally {
        loading.value = false;
    }
}

function resetForm() {
    Object.assign(form, emptyForm());
    errors.value = {};
}

function edit(event) {
    Object.assign(form, {
        ...event,
        settings: {
            ...emptyForm().settings,
            ...(event.settings ?? {}),
        },
    });

    errors.value = {};

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
}

function autoSlug() {
    if (form.id || form.slug) return;

    form.slug = form.name
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

async function save() {
    if (saving.value) return;

    autoSlug();

    saving.value = true;
    errors.value = {};

    try {
        const isEdit = !!form.id;

        const payload = {
            name: form.name,
            slug: form.slug,
            theme: form.theme,
            starts_at: form.starts_at,
            ends_at: form.ends_at,
            is_active: form.is_active,
            settings: form.settings,
        };

        const result = await api(
            isEdit
                ? `/admin/event-themes/${form.id}`
                : '/admin/event-themes',
            {
                method: isEdit ? 'PUT' : 'POST',
                body: payload,
            }
        );

        if (isEdit) {
            const index = events.value.findIndex((x) => x.id === result.id);

            if (index !== -1) {
                events.value[index] = result;
            }

            toast.success('Tema event diperbarui.');
        } else {
            events.value.push(result);
            events.value.sort((a, b) =>
                new Date(a.starts_at) - new Date(b.starts_at)
            );

            toast.success('Tema event berhasil dibuat.');
        }

        resetForm();

    } catch (e) {
        if (e.status === 422) {
            errors.value = e.data?.errors ?? {
                general: ['Data tidak valid.'],
            };

            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });

        } else if (e.status === 401) {
            window.location.href = '/login';
        } else {
            toast.error('Gagal menyimpan tema event.');
        }
    } finally {
        saving.value = false;
    }
}

async function toggle(event) {
    try {
        const result = await api(
            `/admin/event-themes/${event.id}/toggle`,
            {
                method: 'PATCH',
            }
        );

        const index = events.value.findIndex((x) => x.id === result.id);

        if (index !== -1) {
            events.value[index] = result;
        }

        toast.success(
            result.is_active
                ? 'Tema event diaktifkan.'
                : 'Tema event dinonaktifkan.'
        );

    } catch (e) {
        toast.error('Gagal mengubah status event.');
    }
}

async function remove(event) {
    if (!confirm(`Hapus event "${event.name}"?`)) {
        return;
    }

    try {
        await api(`/admin/event-themes/${event.id}`, {
            method: 'DELETE',
        });

        events.value = events.value.filter((x) => x.id !== event.id);

        if (form.id === event.id) {
            resetForm();
        }

        toast.success('Tema event dihapus.');

    } catch (e) {
        toast.error('Gagal menghapus event.');
    }
}

onMounted(load);
</script>