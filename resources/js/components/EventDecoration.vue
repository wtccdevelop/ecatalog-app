<script setup>
import { computed } from 'vue';
import { useEventTheme } from '@/composables/useEventTheme';

const {
    themeConfig,
    assets,
    decorations,
    isActive,
} = useEventTheme();

const getAsset = (assetName) => {
    return assets.value?.[assetName] || null;
};

const getPositionClass = (position) => {
    const positions = {
        'top-left': 'event-decoration--top-left',
        'top-right': 'event-decoration--top-right',
        'top': 'event-decoration--top',
        'bottom-left': 'event-decoration--bottom-left',
        'bottom-right': 'event-decoration--bottom-right',
        'bottom': 'event-decoration--bottom',
        'left': 'event-decoration--left',
        'right': 'event-decoration--right',
    };

    return positions[position] || '';
};

const getAnimationClass = (animation) => {
    const animations = {
        float: 'event-animation-float',
        fade: 'event-animation-fade',
        pulse: 'event-animation-pulse',
        twinkle: 'event-animation-twinkle',
        wave: 'event-animation-wave',
        fall: 'event-animation-fall',
        'snow-fall': 'event-animation-snow',
    };

    return animations[animation] || '';
};

const visibleDecorations = computed(() => {
    return decorations.value.filter((decoration) => {
        return getAsset(decoration.asset);
    });
});
</script>

<template>
    <div
        v-if="isActive && visibleDecorations.length"
        class="event-decoration-container"
        :data-theme="themeConfig.name"
        aria-hidden="true"
    >
        <template
            v-for="(decoration, index) in visibleDecorations"
            :key="`${decoration.asset}-${index}`"
        >
            <img
                :src="getAsset(decoration.asset)"
                :alt="''"
                class="event-decoration"
                :class="[
                    getPositionClass(decoration.position),
                    getAnimationClass(decoration.animation),
                ]"
                loading="lazy"
            />
        </template>
    </div>
</template>

<style scoped>
.event-decoration-container {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 20;
    overflow: hidden;
}

.event-decoration {
    position: absolute;
    width: 100px;
    height: auto;
    object-fit: contain;
    pointer-events: none;
    user-select: none;
}

/* =========================
   POSITIONS
   ========================= */

.event-decoration--top-left {
    top: 70px;
    left: 15px;
}

.event-decoration--top-right {
    top: 70px;
    right: 15px;
}

.event-decoration--top {
    top: 0;
    left: 50%;
    transform: translateX(-50%);
}

.event-decoration--bottom-left {
    bottom: 20px;
    left: 15px;
}

.event-decoration--bottom-right {
    bottom: 20px;
    right: 15px;
}

.event-decoration--bottom {
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

.event-decoration--left {
    left: 0;
    top: 50%;
    transform: translateY(-50%);
}

.event-decoration--right {
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}

/* =========================
   ANIMATIONS
   ========================= */

.event-animation-float {
    animation: eventFloat 4s ease-in-out infinite;
}

.event-animation-fade {
    animation: eventFade 3s ease-in-out infinite alternate;
}

.event-animation-pulse {
    animation: eventPulse 3s ease-in-out infinite;
}

.event-animation-twinkle {
    animation: eventTwinkle 2s ease-in-out infinite;
}

.event-animation-wave {
    transform-origin: top center;
    animation: eventWave 3s ease-in-out infinite;
}

.event-animation-fall {
    animation: eventFall 8s linear infinite;
}

.event-animation-snow {
    width: 100%;
    max-width: none;
    top: 0;
    left: 0;
    animation: eventSnow 12s linear infinite;
}

/* =========================
   KEYFRAMES
   ========================= */

@keyframes eventFloat {
    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-8px);
    }
}

@keyframes eventFade {
    0% {
        opacity: 0.55;
    }

    100% {
        opacity: 1;
    }
}

@keyframes eventPulse {
    0%,
    100% {
        opacity: 0.7;
        transform: scale(1);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }
}

@keyframes eventTwinkle {
    0%,
    100% {
        opacity: 0.4;
        transform: scale(0.95);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }
}

@keyframes eventWave {
    0%,
    100% {
        transform: rotate(-3deg);
    }

    50% {
        transform: rotate(3deg);
    }
}

@keyframes eventFall {
    0% {
        transform: translateY(-100px);
        opacity: 0;
    }

    15% {
        opacity: 1;
    }

    85% {
        opacity: 1;
    }

    100% {
        transform: translateY(100vh);
        opacity: 0;
    }
}

@keyframes eventSnow {
    0% {
        transform: translateY(-5%);
        opacity: 0;
    }

    10% {
        opacity: 1;
    }

    90% {
        opacity: 1;
    }

    100% {
        transform: translateY(100%);
        opacity: 0;
    }
}

/* =========================
   MOBILE
   ========================= */

@media (max-width: 640px) {
    .event-decoration {
        width: 70px;
    }

    .event-decoration--top-left {
        top: 60px;
        left: 5px;
    }

    .event-decoration--top-right {
        top: 60px;
        right: 5px;
    }

    .event-decoration--bottom-left {
        bottom: 10px;
        left: 5px;
    }

    .event-decoration--bottom-right {
        bottom: 10px;
        right: 5px;
    }

    .event-animation-snow {
        width: 180%;
        left: -40%;
    }
}

/* =========================
   REDUCE MOTION
   ========================= */

@media (prefers-reduced-motion: reduce) {
    .event-decoration {
        animation: none !important;
    }
}
</style>