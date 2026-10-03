<template>
    <div class="font-poppins" :class="{ dark: isDark }">
        <div class="bg-white dark:bg-neutral-800 min-h-screen">
            <AppNavbar v-if="showChrome" :is-dark="isDark" @toggle-dark="toggleDark" />
            <RouterView />
            <AppFooter v-if="showChrome" />
        </div>

        <template v-if="showChrome">
            <EventAmbient :theme="eventTheme" />
            <EventMascot :theme="eventTheme" />
            <WelcomeGreeting :raised="!!eventTheme" />   
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import AppNavbar from './components/AppNavbar.vue';
import AppFooter from './components/AppFooter.vue';
import EventAmbient from './components/event/EventAmbient.vue';
import EventMascot from './components/event/EventMascot.vue';
import { useEventTheme } from './composables/useEventTheme.js';
import { loadSite } from './stores/catalog.js';
import WelcomeGreeting from './components/WelcomeGreeting.vue';

const route = useRoute();
const eventTheme = useEventTheme();

const showChrome = computed(() => !route.meta.hideChrome);

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

    loadSite().catch(() => {});
});
</script>