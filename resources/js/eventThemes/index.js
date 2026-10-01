/**
 * Semua definisi visual tema event berada di sini.
 *
 * Prinsip:
 * - Tema hanya menjadi dekorasi tambahan.
 * - Produk tetap menjadi fokus utama.
 * - Komponen katalog tidak perlu mengetahui detail tiap event.
 */

export const EVENT_THEMES = {
    christmas: {
        name: 'Natal',
        slug: 'christmas',

        colors: {
            accent: 'red',
            secondary: 'emerald',
        },

        decorations: {
            product: [
                'christmas-hat',
                'christmas-ornament',
            ],
            header: [
                'christmas-lights',
            ],
            background: [],
        },

        intensity: 'soft',
    },

    eid: {
        name: 'Lebaran',
        slug: 'eid',

        colors: {
            accent: 'emerald',
            secondary: 'amber',
        },

        decorations: {
            product: [
                'eid-star',
                'eid-moon',
            ],
            header: [
                'eid-lights',
            ],
            background: [],
        },

        intensity: 'soft',
    },

    independence: {
        name: 'HUT Kemerdekaan RI',
        slug: 'independence',

        colors: {
            accent: 'red',
            secondary: 'white',
        },

        decorations: {
            product: [
                'independence-ribbon',
            ],
            header: [
                'independence-ribbon',
            ],
            background: [],
        },

        intensity: 'soft',
    },

    new_year: {
        name: 'Tahun Baru',
        slug: 'new_year',

        colors: {
            accent: 'blue',
            secondary: 'amber',
        },

        decorations: {
            product: [
                'new-year-sparkle',
            ],
            header: [
                'new-year-sparkle',
            ],
            background: [],
        },

        intensity: 'soft',
    },
};

export function getEventTheme(theme) {
    if (!theme) return null;

    return EVENT_THEMES[theme] ?? null;
}

export function getThemeDecorations(theme, area = 'product') {
    const definition = getEventTheme(theme);

    if (!definition) return [];

    return definition.decorations?.[area] ?? [];
}

export function hasThemeDecoration(theme, decoration, area = 'product') {
    return getThemeDecorations(theme, area).includes(decoration);
}