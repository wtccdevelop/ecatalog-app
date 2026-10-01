import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';
import {
    getEventTheme,
    getThemeDecorations,
    hasThemeDecoration,
} from '../eventThemes/index.js';

export function useEventTheme() {
    const activeEvent = computed(() => {
        return catalog.eventTheme?.active
            ? catalog.eventTheme.event
            : null;
    });

    const theme = computed(() => {
        if (!activeEvent.value) return null;

        return getEventTheme(activeEvent.value.theme);
    });

    const isActive = computed(() => {
        return !!activeEvent.value && !!theme.value;
    });

    function decorations(area = 'product') {
        if (!theme.value) return [];

        return getThemeDecorations(
            theme.value.slug,
            area
        );
    }

    function hasDecoration(decoration, area = 'product') {
        if (!theme.value) return false;

        return hasThemeDecoration(
            theme.value.slug,
            decoration,
            area
        );
    }

    return {
        activeEvent,
        theme,
        isActive,
        decorations,
        hasDecoration,
    };
}