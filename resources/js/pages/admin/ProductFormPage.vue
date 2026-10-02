<template>
    <div class="max-w-4xl mx-auto">
        <RouterLink to="/admin/products" class="inline-block mb-4 text-sm text-blue-600 hover:underline">← Kembali ke daftar produk</RouterLink>

        <p v-if="loading" class="text-sm text-gray-500 dark:text-neutral-400">Memuat...</p>

        <form v-else @submit.prevent="submit" class="space-y-6" novalidate>
            <!-- Error -->
            <div v-if="errorList.length" class="rounded-lg bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm text-red-700 dark:text-red-400" role="alert">
                <p class="font-semibold mb-1">Periksa kembali isian Anda:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    <li v-for="(m, i) in errorList" :key="i">{{ m }}</li>
                </ul>
            </div>

            <!-- Info dasar -->
            <section :class="card">
                <h2 :class="h2">Informasi Produk</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label :class="lbl">Brand</label>
                        <select v-model="form.brand_id" :class="input">
                            <option value="" disabled>Pilih brand</option>
                            <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label :class="lbl">Nama Produk</label>
                        <input v-model.trim="form.name" type="text" :class="input" />
                    </div>
                    <div>
                        <label :class="lbl">Urutan</label>
                        <input v-model.number="form.sort_order" type="number" min="0" :class="input" />
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 sm:mt-7">
                        <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" />
                        Tampilkan di website
                    </label>
                </div>

                <div class="mt-4">
                    <label :class="lbl">Deskripsi (opsional)</label>
                    <textarea v-model="form.description" rows="3" :class="input"></textarea>
                </div>

                <div class="mt-4">
                    <label :class="lbl">Gambar Utama</label>
                    <div class="flex items-center gap-3">
                        <img v-if="preview" :src="preview" alt="Preview" class="size-20 rounded-lg object-contain border border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-600" />
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="onImage" class="text-xs text-gray-600 dark:text-gray-300" />
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">PNG/JPG/WEBP, maks. 3 MB.</p>
                </div>
            </section>

            <!-- Varian -->
            <section :class="card">
                <div class="flex items-center justify-between mb-3">
                    <h2 :class="h2 + ' !mb-0'">Varian & Harga</h2>
                    <button type="button" @click="variants.push(blankVariant())" class="px-3 py-1.5 rounded-lg text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 dark:border-neutral-600 dark:hover:bg-neutral-700">
                        + Tambah varian
                    </button>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-neutral-400 mb-3">
                    Kosongkan RAM, storage, dan warna bila produk tidak punya pilihan varian.
                </p>

                <div class="space-y-3">
                    <div
                        v-for="(v, i) in variants"
                        :key="v.key"
                        class="p-3 rounded-lg bg-gray-50 dark:bg-neutral-900 space-y-2"
                    >
                        <!-- Baris 1: field teks -->
                        <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 items-end">
                            <div>
                                <label :class="lblSm">RAM (GB)</label>
                                <input v-model.number="v.ram" type="number" min="0" :class="input" />
                            </div>
                            <div>
                                <label :class="lblSm">Storage (GB)</label>
                                <input v-model.number="v.storage" type="number" min="0" :class="input" />
                            </div>
                            <div>
                                <label :class="lblSm">Warna</label>
                                <input v-model="v.color" type="text" :class="input" />
                            </div>
                            <div>
                                <label :class="lblSm">Harga (Rp)</label>
                                <input v-model.number="v.price" type="number" min="0" :class="input" />
                                <p class="mt-0.5 text-[10px] text-gray-500">{{ formatRupiah(v.price || 0) }}</p>
                            </div>
                            <div>
                                <label :class="lblSm">Stok</label>
                                <input v-model.number="v.stock" type="number" min="0" :class="input" />
                            </div>
                            <div class="flex items-center justify-between gap-2 pb-2">
                                <label class="flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
                                    <input v-model="v.is_active" type="checkbox" class="rounded border-gray-300" />
                                    Aktif
                                </label>
                                <button
                                    type="button"
                                    :disabled="variants.length === 1"
                                    @click="variants.splice(i, 1)"
                                    class="text-red-600 text-sm disabled:opacity-30 disabled:cursor-not-allowed"
                                    aria-label="Hapus varian"
                                >✕</button>
                            </div>
                        </div>

                        <!-- Baris 2: gambar varian -->
                        <div class="flex items-center gap-3 pt-1 border-t border-gray-200 dark:border-neutral-700">
                            <img
                                v-if="v.imagePreview"
                                :src="v.imagePreview"
                                alt="Preview gambar varian"
                                class="size-14 rounded-lg object-contain border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 shrink-0"
                            />
                            <div v-else class="size-14 rounded-lg border border-dashed border-gray-300 dark:border-neutral-600 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V4.5a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v15a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V9.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <div>
                                <label :class="lblSm">Gambar Varian (opsional)</label>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg,image/webp"
                                    @change="onVariantImage($event, v)"
                                    class="text-xs text-gray-600 dark:text-gray-300"
                                />
                                <p class="mt-0.5 text-[10px] text-gray-500">PNG/JPG/WEBP, maks. 3 MB. Jika tidak diisi, gambar utama produk yang dipakai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Spesifikasi -->
            <section :class="card">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                    <h2 :class="h2 + ' !mb-0'">Spesifikasi</h2>
                    <div class="flex gap-2">
                        <button type="button" @click="fillTemplate" class="px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 dark:text-white dark:border-neutral-600 dark:hover:bg-neutral-700">
                            Isi template
                        </button>
                        <button type="button" @click="specs.push(blankSpec())" class="px-3 py-1.5 rounded-lg text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 dark:border-neutral-600 dark:hover:bg-neutral-700">
                            + Tambah
                        </button>
                    </div>
                </div>

                <p v-if="!specs.length" class="text-xs text-gray-500 dark:text-neutral-400">Belum ada spesifikasi.</p>

                <div class="space-y-2">
                    <div v-for="(s, i) in specs" :key="s.key" class="grid grid-cols-[1fr_2fr_auto] gap-2 items-center">
                        <input v-model="s.label" type="text" placeholder="Label (mis. Chipset)" :class="input" />
                        <input v-model="s.value" type="text" placeholder="Nilai" :class="input" />
                        <button type="button" @click="specs.splice(i, 1)" class="text-red-600 text-sm px-2" aria-label="Hapus spesifikasi">✕</button>
                    </div>
                </div>
            </section>

            <div class="flex justify-end gap-2">
                <RouterLink to="/admin/products" class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 text-gray-800 dark:border-neutral-600 dark:text-white">
                    Batal
                </RouterLink>
                <button type="submit" :disabled="saving" class="px-5 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60">
                    {{ saving ? 'Menyimpan...' : 'Simpan Produk' }}
                </button>
            </div>
        </form>

        <!-- Galeri & Ulasan (hanya saat edit) -->
        <template v-if="!loading">
            <section v-if="!id" :class="[card, 'mt-6']">
                <p class="text-sm text-gray-500 dark:text-neutral-400">Galeri dan ulasan tersedia setelah produk disimpan.</p>
            </section>

            <template v-else>
                <!-- Galeri -->
                <section :class="[card, 'mt-6']">
                    <h2 :class="h2">Galeri Produk</h2>

                    <div v-if="gallery.length" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        <div v-for="g in gallery" :key="g.id" class="relative group">
                            <img :src="g.url" alt="Galeri" class="w-full aspect-square object-cover rounded-lg border border-gray-200 dark:border-neutral-600" />
                            <button
                                type="button"
                                @click="delImage(g)"
                                class="absolute top-1.5 right-1.5 size-7 rounded-full bg-red-600 text-white text-xs opacity-90 hover:opacity-100"
                                aria-label="Hapus gambar"
                            >✕</button>
                        </div>
                    </div>
                    <p v-else class="text-xs text-gray-500 dark:text-neutral-400 mb-4">Belum ada gambar galeri.</p>

                    <div class="flex items-center gap-3">
                        <input type="file" multiple accept="image/png,image/jpeg,image/webp" :disabled="uploading" @change="onGallery" class="text-xs text-gray-600 dark:text-gray-300" />
                        <span v-if="uploading" class="text-xs text-gray-500">Mengunggah...</span>
                    </div>
                    <p class="mt-1 text-[11px] text-gray-500">Maks. 10 gambar sekali unggah, masing-masing 3 MB.</p>
                </section>

                <!-- Ulasan -->
                <section :class="[card, 'mt-6']">
                    <h2 :class="h2">Ulasan ({{ reviews.length }})</h2>

                    <p v-if="!reviews.length" class="text-xs text-gray-500 dark:text-neutral-400">Belum ada ulasan.</p>

                    <div class="divide-y divide-gray-100 dark:divide-neutral-700">
                        <div v-for="r in reviews" :key="r.id" class="py-3 flex items-start justify-between gap-3" :class="r.is_active ? '' : 'opacity-50'">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ r.name }}
                                    <span class="ml-2 text-yellow-500">{{ '★'.repeat(r.rating) }}<span class="text-gray-300">{{ '★'.repeat(5 - r.rating) }}</span></span>
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">{{ r.comment }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ r.date }}</p>
                            </div>
                            <div class="flex gap-1 shrink-0">
                                <button type="button" @click="toggleReview(r)" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-neutral-700">
                                    {{ r.is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                </button>
                                <button type="button" @click="delReview(r)" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-neutral-700">Hapus</button>
                            </div>
                        </div>
                    </div>
                </section>
            </template>
        </template>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from '../../lib/api.js';
import { formatRupiah } from '../../lib/format.js';
import { toFormData } from '../../composables/useResource.js';
import { toast } from '../../stores/toast.js';

// kelas dipakai ulang
const card  = 'bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-5';
const h2    = 'text-base font-bold text-gray-900 dark:text-white mb-4';
const lbl   = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1';
const lblSm = 'block text-[11px] font-semibold text-gray-600 dark:text-gray-400 mb-1';
const input = 'block w-full px-3 py-2 rounded-lg border border-gray-200 text-sm dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500';

const SPEC_TEMPLATE = ['Chipset', 'Display', 'Battery', 'OS', 'Resolution', 'Main Camera', 'Front Camera', 'Weight'];

const route = useRoute();
const router = useRouter();
const id = computed(() => route.params.id ?? null);

let seq = 0;
const blankVariant = () => ({ key: ++seq, ram: null, storage: null, color: '', price: 0, stock: 0, is_active: true, image: null, imagePreview: null });
const blankSpec = () => ({ key: ++seq, label: '', value: '' });

const brands = ref([]);
const loading = ref(false);
const saving = ref(false);
const uploading = ref(false);
const errors = ref({});

const form = reactive({ brand_id: '', name: '', description: '', sort_order: 0, is_active: true, image: null });
const preview = ref(null);
const variants = ref([blankVariant()]);
const specs = ref([]);
const gallery = ref([]);
const reviews = ref([]);

const errorList = computed(() => Object.values(errors.value).flat());

function expired(e) {
    if (e.status === 401) {
        window.location.href = '/login';
        return true;
    }
    return false;
}

function reset() {
    Object.assign(form, { brand_id: '', name: '', description: '', sort_order: 0, is_active: true, image: null });
    preview.value = null;
    variants.value = [blankVariant()];
    specs.value = [];
    gallery.value = [];
    reviews.value = [];
}

function fill(p) {
    Object.assign(form, {
        brand_id: p.brand_id,
        name: p.name,
        description: p.description ?? '',
        sort_order: p.sort_order,
        is_active: p.is_active,
        image: null,
    });
    preview.value = p.image;
    variants.value = p.variants.length ? p.variants.map((v) => ({ ...v, key: ++seq, image: null, imagePreview: v.image ?? null })) : [blankVariant()];
    specs.value = p.specs.map((s) => ({ key: ++seq, label: s.label, value: s.value ?? '' }));
    gallery.value = p.gallery;
    reviews.value = p.reviews;
}

async function init() {
    if (!String(route.name).startsWith('admin.products.')) return;

    errors.value = {};

    if (!id.value) {
        reset();
        return;
    }

    loading.value = true;
    try {
        fill(await api(`/admin/products/${id.value}`));
    } catch (e) {
        if (expired(e)) return;
        toast.error('Produk tidak ditemukan.');
        router.replace('/admin/products');
    } finally {
        loading.value = false;
    }
}

function onImage(e) {
    const f = e.target.files[0];
    if (!f) return;
    form.image = f;
    preview.value = URL.createObjectURL(f);
}

function onVariantImage(e, v) {
    const f = e.target.files[0];
    if (!f) return;
    v.image = f;
    v.imagePreview = URL.createObjectURL(f);
}

function fillTemplate() {
    const have = new Set(specs.value.map((s) => s.label.trim().toLowerCase()));
    for (const label of SPEC_TEMPLATE) {
        if (!have.has(label.toLowerCase())) specs.value.push({ key: ++seq, label, value: '' });
    }
}

async function submit() {
    if (saving.value) return;

    saving.value = true;
    errors.value = {};

    try {
        const fd = toFormData({
            brand_id: form.brand_id,
            name: form.name,
            description: form.description,
            sort_order: form.sort_order,
            is_active: form.is_active,
            image: form.image,
            variants: JSON.stringify(
                variants.value.map((v) => ({
                    ram: v.ram || null,
                    storage: v.storage || null,
                    color: v.color?.trim() || null,
                    price: Number(v.price) || 0,
                    stock: Number(v.stock) || 0,
                    is_active: !!v.is_active,
                }))
            ),
            specs: JSON.stringify(
                specs.value
                    .filter((s) => s.label.trim())
                    .map((s) => ({ label: s.label.trim(), value: s.value?.trim() || null }))
            ),
        });

        // lampirkan gambar per-varian (hanya yang baru di-upload)
        variants.value.forEach((v, i) => {
            if (v.image instanceof File) {
                fd.append(`variant_images[${i}]`, v.image);
            }
        });
        if (id.value) fd.append('_method', 'PUT');

        const res = await api(id.value ? `/admin/products/${id.value}` : '/admin/products', {
            method: 'POST',
            body: fd,
        });

        //redirect ke halaman edit product setelah sukses menambahkan product
        if (id.value) {
            fill(res);
            toast.success('Produk berhasil diperbarui.');
        } else {
            await router.replace({
                name: 'admin.products.edit',
                params: { id: res.id },
            });

            toast.success('Produk berhasil dibuat.');
        }
    } catch (e) {
        if (expired(e)) return;
        if (e.status === 422) {
            errors.value = e.data?.errors ?? {};
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            toast.error('Terjadi kesalahan. Coba lagi.');
        }
    } finally {
        saving.value = false;
    }
}

/* ---------- Galeri ---------- */
async function onGallery(e) {
    const files = [...e.target.files];
    if (!files.length) return;

    const fd = new FormData();
    files.forEach((f) => fd.append('images[]', f));

    uploading.value = true;
    try {
        gallery.value = await api(`/admin/products/${id.value}/images`, { method: 'POST', body: fd });
        toast.success('Gambar ditambahkan.');
    } catch (err) {
        if (expired(err)) return;
        toast.error(Object.values(err.data?.errors ?? {}).flat()[0] ?? 'Gagal mengunggah gambar.');
    } finally {
        uploading.value = false;
        e.target.value = '';
    }
}

async function delImage(g) {
    if (!confirm('Hapus gambar ini?')) return;
    try {
        await api(`/admin/products/${id.value}/images/${g.id}`, { method: 'DELETE' });
        gallery.value = gallery.value.filter((x) => x.id !== g.id);
        toast.success('Gambar dihapus.');
    } catch (err) {
        if (!expired(err)) toast.error('Gagal menghapus gambar.');
    }
}

/* ---------- Ulasan ---------- */
async function toggleReview(r) {
    try {
        const d = await api(`/admin/reviews/${r.id}`, { method: 'PATCH' });
        r.is_active = d.is_active;
    } catch (err) {
        if (!expired(err)) toast.error('Gagal mengubah ulasan.');
    }
}

async function delReview(r) {
    if (!confirm(`Hapus ulasan dari ${r.name}?`)) return;
    try {
        await api(`/admin/reviews/${r.id}`, { method: 'DELETE' });
        reviews.value = reviews.value.filter((x) => x.id !== r.id);
        toast.success('Ulasan dihapus.');
    } catch (err) {
        if (!expired(err)) toast.error('Gagal menghapus ulasan.');
    }
}

watch(() => route.params.id, init, { immediate: true });

onMounted(async () => {
    try {
        brands.value = await api('/admin/brands');
    } catch (e) {
        expired(e);
    }
});
</script>