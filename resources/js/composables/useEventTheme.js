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

        return (
            activeTheme.value.type ||
            activeTheme.value.slug ||
            activeTheme.value.theme ||
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