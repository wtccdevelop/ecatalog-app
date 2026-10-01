import { computed } from 'vue';
import { useCatalogStore } from '@/stores/catalog';
import eventThemes from '@/config/eventThemes';

export function useEventTheme() {
    const catalogStore = useCatalogStore();

    const activeTheme = computed(() => {
        return catalogStore.eventTheme || null;
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
        return themeType.value !== 'default';
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