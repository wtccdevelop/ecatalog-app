import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';
import eventThemes from '../config/eventThemes.js';

export function useEventTheme() {
    const activeTheme = computed(() => {
        if (!catalog.eventTheme?.active) {
            return null;
        }

        return catalog.eventTheme?.event || null;
    });

    const themeType = computed(() => {
        if (!activeTheme.value) {
            return 'default';
        }

        // Backend menyimpan jenis tema di field "theme".
        // Contoh:
        // name  = Natal
        // slug  = natal
        // theme = christmas
        //
        // Yang harus dipakai untuk mencari konfigurasi
        // eventThemes.js adalah "theme".
        return (
            activeTheme.value.theme ||
            activeTheme.value.type ||
            activeTheme.value.slug ||
            'default'
        );
    });

    const themeConfig = computed(() => {
        return eventThemes[themeType.value] || eventThemes.default;
    });

    const assets = computed(() => {
        return themeConfig.value.assets || {};
    });

    const decorations = computed(() => {
        return themeConfig.value.decorations || [];
    });

    const isActive = computed(() => {
        return (
            catalog.eventTheme?.active === true &&
            themeType.value !== 'default'
        );
    });

    return {
        activeTheme,
        themeType,
        themeConfig,
        assets,
        decorations,
        isActive,
    };
}