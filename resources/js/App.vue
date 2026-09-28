<template>
    <div class="font-poppins" :class="{ dark: isDark }">
        <div class="bg-white dark:bg-neutral-800 min-h-screen">
            <AppNavbar :is-dark="isDark" @toggle-dark="toggleDark" />
            <RouterView />
            <AppFooter />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import AppFooter from './components/AppFooter.vue';

const isDark = ref(false);

function applyTheme() {
    document.documentElement.classList.toggle('dark', isDark.value);
    try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light'); } catch (e) {}
}

function toggleDark() {
    isDark.value = !isDark.value;
    applyTheme();
}

onMounted(() => {
    let saved = null;
    try { saved = localStorage.getItem('theme'); } catch (e) {}
    isDark.value = saved
        ? saved === 'dark'
        : window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme();
});
</script>