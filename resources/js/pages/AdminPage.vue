<template>
    <div class="bg-gray-100 dark:bg-neutral-700 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-10">
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-md p-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-extrabold text-gray-900 dark:text-white">Dashboard Admin</h1>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Masuk sebagai {{ auth.user?.email }}</p>
                </div>
                <button
                    type="button"
                    @click="doLogout"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 text-gray-800 hover:bg-gray-50 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-700"
                >
                    Keluar
                </button>
            </div>

            <p class="mt-6 text-sm text-gray-500 dark:text-neutral-400">
                Area admin siap. Fitur CRUD dan statistik pengunjung bisa ditambahkan di sini.
            </p>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { auth, logout } from '../stores/auth.js';

const router = useRouter();

async function doLogout() {
    await logout();
    router.replace('/login');
}

onMounted(() => {
    document.title = 'WTC Cell - Admin';
});
</script>