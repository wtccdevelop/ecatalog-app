const eventThemes = {
    default: {
        name: 'Default',
        assets: [],
        decorations: [],
    },

    christmas: {
        name: 'Natal',

        colors: {
            primary: '#D62828',
            secondary: '#16804B',
            accent: '#FFD54A',
            light: '#FFF8E7',
        },

        assets: {
            snow: '/assets/themes/christmas/snow.svg',
            stars: '/assets/themes/christmas/stars.svg',
            gift: '/assets/themes/christmas/gift.svg',
            tree: '/assets/themes/christmas/tree.svg',
        },

        decorations: [
            {
                type: 'image',
                asset: 'tree',
                position: 'bottom-left',
                animation: 'float',
            },
            {
                type: 'image',
                asset: 'gift',
                position: 'bottom-right',
                animation: 'float',
            },
            {
                type: 'image',
                asset: 'stars',
                position: 'top-right',
                animation: 'twinkle',
            },
            {
                type: 'image',
                asset: 'snow',
                position: 'top',
                animation: 'snow-fall',
            },
        ],
    },

    ramadan: {
        name: 'Ramadan / Lebaran',

        colors: {
            primary: '#16804B',
            secondary: '#0F6B3D',
            accent: '#F4C542',
            light: '#F4F8F2',
        },

        assets: {
            moon: '/assets/themes/ramadan/moon.svg',
            lantern: '/assets/themes/ramadan/lantern.svg',
            stars: '/assets/themes/ramadan/stars.svg',
            mosque: '/assets/themes/ramadan/mosque.svg',
        },

        decorations: [
            {
                type: 'image',
                asset: 'moon',
                position: 'top-right',
                animation: 'float',
            },
            {
                type: 'image',
                asset: 'lantern',
                position: 'top-left',
                animation: 'float',
            },
            {
                type: 'image',
                asset: 'stars',
                position: 'top',
                animation: 'twinkle',
            },
            {
                type: 'image',
                asset: 'mosque',
                position: 'bottom',
                animation: 'fade',
            },
        ],
    },

    independence: {
        name: 'Kemerdekaan Indonesia',

        colors: {
            primary: '#D62828',
            secondary: '#B91C1C',
            accent: '#FFFFFF',
            light: '#FFF5F5',
        },

        assets: {
            flag: '/assets/themes/independence/flag.svg',
            ribbon: '/assets/themes/independence/ribbon.svg',
            confetti: '/assets/themes/independence/confetti.svg',
        },

        decorations: [
            {
                type: 'image',
                asset: 'flag',
                position: 'top-left',
                animation: 'wave',
            },
            {
                type: 'image',
                asset: 'ribbon',
                position: 'top-right',
                animation: 'float',
            },
            {
                type: 'image',
                asset: 'confetti',
                position: 'top',
                animation: 'fall',
            },
        ],
    },

    'new-year': {
        name: 'Tahun Baru',
        assets: {
            fireworks: '/assets/themes/new-year/fireworks.svg',
            stars: '/assets/themes/new-year/stars.svg',
            confetti: '/assets/themes/new-year/confetti.svg',
        },

        decorations: [
            {
                type: 'image',
                asset: 'fireworks',
                position: 'top-left',
                animation: 'pulse',
            },
            {
                type: 'image',
                asset: 'fireworks',
                position: 'top-right',
                animation: 'pulse',
            },
            {
                type: 'image',
                asset: 'stars',
                position: 'top',
                animation: 'twinkle',
            },
            {
                type: 'image',
                asset: 'confetti',
                position: 'top',
                animation: 'fall',
            },
        ],
    },
};

export default eventThemes;