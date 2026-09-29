<template>
    <div class="bg-gray-100 dark:bg-neutral-700 min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm bg-white dark:bg-neutral-800 rounded-2xl shadow-lg p-6 sm:p-8">
            <div class="text-center mb-6">
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white">Login Admin</h1>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">WTC Cell</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4" novalidate>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input
                        id="email"
                        v-model.trim="email"
                        type="email"
                        autocomplete="username"
                        required
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    />
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            required
                            class="block w-full px-4 py-2.5 pr-16 rounded-lg border border-gray-200 text-sm dark:bg-neutral-900 dark:border-neutral-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400"
                        >
                            {{ showPassword ? 'Sembunyi' : 'Lihat' }}
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <input v-model="remember" type="checkbox" class="rounded border-gray-300" />
                    Ingat saya di perangkat ini
                </label>

                <p v-if="error" class="text-sm text-red-600 bg-red-50 dark:bg-red-950/40 dark:text-red-400 rounded-lg px-3 py-2" role="alert">
                    {{ error }}
                </p>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-2.5 rounded-xl font-bold text-sm text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                >
                    {{ loading ? 'Memproses...' : 'Masuk' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { login } from '../stores/auth.js';

const route = useRoute();
const router = useRouter();

const email = ref('');
const password = ref('');
const remember = ref(false);
const showPassword = ref(false);
const loading = ref(false);
const error = ref('');

// hanya izinkan redirect ke path internal
function safeRedirect() {
    const r = String(route.query.redirect ?? '');
    return r.startsWith('/') && !r.startsWith('//') ? r : '/admin';
}

async function submit() {
    if (loading.value) return;

    error.value = '';
    loading.value = true;

    try {
        await login({ email: email.value, password: password.value, remember: remember.value });
        await router.replace(safeRedirect());
    } catch (e) {
        if (e.status === 422 || e.status === 429) {
            error.value = e.data?.errors?.email?.[0] ?? e.data?.message ?? 'Login gagal.';
        } else if (e.status === 419) {
            error.value = 'Sesi halaman kedaluwarsa. Muat ulang halaman lalu coba lagi.';
        } else {
            error.value = 'Terjadi kesalahan. Coba lagi.';
        }
        password.value = '';
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    document.title = 'WTC Cell - Login';
});
</script>