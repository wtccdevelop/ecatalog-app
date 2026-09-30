import { ref } from 'vue';
import { api } from '../lib/api.js';
import { toast } from '../stores/toast.js';

export function toFormData(obj) {
    const fd = new FormData();
    for (const [k, v] of Object.entries(obj)) {
        if (v === null || v === undefined) continue;
        fd.append(k, typeof v === 'boolean' ? (v ? '1' : '0') : v);
    }
    return fd;
}

export function useResource(base) {
    const items = ref([]);
    const loading = ref(false);
    const saving = ref(false);
    const errors = ref({});

    // sesi habis -> kembali ke login
    function expired(e) {
        if (e.status === 401) {
            window.location.href = '/login';
            return true;
        }
        return false;
    }

    async function load() {
        loading.value = true;
        try {
            items.value = await api(base);
        } catch (e) {
            if (!expired(e)) toast.error('Gagal memuat data.');
        } finally {
            loading.value = false;
        }
    }

    // id null = tambah, id terisi = ubah
    async function save(form, id = null) {
        saving.value = true;
        errors.value = {};
        try {
            const fd = toFormData(form);
            if (id) fd.append('_method', 'PUT');

            await api(id ? `${base}/${id}` : base, { method: 'POST', body: fd });
            toast.success(id ? 'Data diperbarui.' : 'Data ditambahkan.');
            await load();
            return true;
        } catch (e) {
            if (expired(e)) return false;
            if (e.status === 422) errors.value = e.data?.errors ?? {};
            else toast.error('Terjadi kesalahan. Coba lagi.');
            return false;
        } finally {
            saving.value = false;
        }
    }

    async function remove(id) {
        try {
            await api(`${base}/${id}`, { method: 'DELETE' });
            toast.success('Data dihapus.');
            await load();
            return true;
        } catch (e) {
            if (!expired(e)) toast.error('Gagal menghapus data.');
            return false;
        }
    }

    return { items, loading, saving, errors, load, save, remove };
}