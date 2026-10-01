<template>
    <div class="max-w-3xl mx-auto">
        <p v-if="loading" class="text-sm text-gray-500 dark:text-neutral-400">Memuat...</p>

        <form v-else @submit.prevent="submit" class="space-y-6" novalidate>
            <div v-if="errorList.length" class="rounded-lg bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5 space-y-0.5">
                    <li v-for="(m, i) in errorList" :key="i">{{ m }}</li>
                </ul>
            </div>

            <section :class="card">
                <h2 :class="h2">Kontak & Operasional</h2>

                <div class="space-y-4">
                    <div>
                        <label :class="lbl">Nomor WhatsApp</label>
                        <input v-model.trim="form.wa_number" type="text" inputmode="numeric" placeholder="6281234567890" :class="input" />
                        <p class="mt-1 text-[11px] text-gray-500">Format 62xxxxxxxxxx (tanpa +, spasi, atau 0 di depan). Dipakai untuk tombol "Tanya Stok / Pesan via WhatsApp".</p>
                        <p v-if="preview" class="mt-0.5 text-[11px] text-gray-500">Tampil sebagai: <b>{{ preview }}</b></p>
                    </div>

                    <div>
                        <label :class="lbl">Jam Operasional</label>
                        <input v-model.trim="form.operating_hours" type="text" placeholder="Senin – Minggu: 08.00 – 21.00 WIB" :class="input" />
                    </div>
                </div>
            </section>

            <section :class="card">
                <h2 :class="h2">Tentang Kami</h2>

                <div class="space-y-4">
                    <div>
                        <label :class="lbl">Paragraf 1</label>
                        <textarea v-model="form.about_1" rows="4" :class="input"></textarea>
                    </div>
                    <div>
                        <label :class="lbl">Paragraf 2</label>
                        <textarea v-model="form.about_2" rows="4" :class="input"></textarea>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" :disabled="saving" class="px-5 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60">
                    {{ saving ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { api } from '../../lib/api.js';
import { toast } from '../../stores/toast.js';
import { loadSite, catalog } from '../../stores/catalog.js';

const card  = 'bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-5';
const h2    = 'text-base font-bold text-gray-900 dark:text-white mb-4';
const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const input = 'block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const loading = ref(true);
const saving = ref(false);
const errors = ref({});
const form = reactive({ wa_number: '', operating_hours: '', about_1: '', about_2: '' });

const errorList = computed(() => Object.values(errors.value).flat());

// 6281234567890 -> +62 812-3456-7890
const preview = computed(() => {
    const n = form.wa_number;
    return /^62\d{8,13}$/.test(n) ? `+${n.slice(0, 2)} ${n.slice(2, 5)}-${n.slice(5, 9)}-${n.slice(9)}` : '';
});

async function load() {
    try {
        Object.assign(form, await api('/admin/settings'));
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else toast.error('Gagal memuat pengaturan.');
    } finally {
        loading.value = false;
    }
}

async function submit() {
    if (saving.value) return;

    saving.value = true;
    errors.value = {};

    try {
        const d = await api('/admin/settings', { method: 'PUT', body: { ...form } });
        Object.assign(form, d);

        // segarkan cache /api/site di sisi publik
        catalog.site = null;
        loadSite().catch(() => {});

        toast.success('Pengaturan disimpan.');
    } catch (e) {
        if (e.status === 401) window.location.href = '/login';
        else if (e.status === 422) {
            errors.value = e.data?.errors ?? {};
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else toast.error('Terjadi kesalahan. Coba lagi.');
    } finally {
        saving.value = false;
    }
}

onMounted(load);
</script>