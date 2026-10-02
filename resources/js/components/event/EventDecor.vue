<template>
    <div v-if="theme" class="pointer-events-none absolute inset-0 z-10 select-none" aria-hidden="true">

        <!-- NATAL: topi santa + gantungan bola -->
        <template v-if="theme === 'christmas'">
            <svg class="ev-swing absolute right-1 top-0 w-12 sm:w-16" viewBox="0 0 60 44"></svg>
            <svg class="absolute left-0.5 top-15 w-9 sm:w-12 lg:w-14 -rotate-6 drop-shadow-sm" viewBox="0 0 64 56">
                <path d="M8 38 C8 18 26 4 46 10 C42 18 44 28 56 38 Z" fill="#dc2626" />
                <rect x="4" y="36" width="56" height="12" rx="6" fill="#fff" stroke="#e5e7eb" />
                <circle cx="48" cy="10" r="6" fill="#fff" stroke="#e5e7eb" />
            </svg>

            <svg class="absolute right-1 top-15 w-12 sm:w-16" viewBox="0 0 60 44">
                <g stroke="#9ca3af" stroke-width="1">
                    <path d="M10 0V12" /><path d="M30 0V20" /><path d="M50 0V14" />
                </g>
                <rect x="8" y="10" width="4" height="3" rx="1" fill="#fbbf24" />
                <rect x="28" y="18" width="4" height="3" rx="1" fill="#fbbf24" />
                <rect x="48" y="12" width="4" height="3" rx="1" fill="#fbbf24" />
                <circle cx="10" cy="18" r="5" fill="#ef4444" />
                <circle cx="30" cy="26" r="5" fill="#f59e0b" />
                <circle cx="50" cy="20" r="5" fill="#16a34a" />
                <g fill="#fff" opacity=".6">
                    <circle cx="8.5" cy="16.5" r="1.3" /><circle cx="28.5" cy="24.5" r="1.3" /><circle cx="48.5" cy="18.5" r="1.3" />
                </g>
            </svg>
        </template>

        <!-- LEBARAN: ketupat + lentera -->
        <template v-else-if="theme === 'lebaran'">
            <svg class="ev-swing absolute left-1.5 top-0 w-7 sm:w-9 lg:w-11" viewBox="0 0 40 64"></svg>
            <svg class="absolute left-1.5 top-15 w-7 sm:w-9 lg:w-11" viewBox="0 0 40 64">
                <path d="M20 0V14" stroke="#9ca3af" stroke-width="1" />
                <polygon points="20,14 36,32 20,50 4,32" fill="#16a34a" stroke="#14532d" stroke-width="1.2" />
                <g stroke="#bbf7d0" stroke-width="1.2" fill="none">
                    <path d="M12 23 28 41" /><path d="M28 23 12 41" /><path d="M20 14 20 50" opacity=".5" />
                </g>
                <g stroke="#facc15" stroke-width="1.5"><path d="M17 50V60" /><path d="M20 50V62" /><path d="M23 50V60" /></g>
            </svg>

            <svg class="absolute right-1.5 top-15 w-7 sm:w-9 lg:w-11" viewBox="0 0 40 72">
                <path d="M20 0V12" stroke="#9ca3af" stroke-width="1" />
                <path d="M13 12h14l-3 5H16z" fill="#b45309" />
                <path d="M12 18h16l4 10v14l-4 10H12L8 42V28z" fill="#f59e0b" stroke="#b45309" stroke-width="1.2" />
                <ellipse cx="20" cy="35" rx="6" ry="11" fill="#fde68a" />
                <rect x="13" y="52" width="14" height="4" rx="1.5" fill="#b45309" />
                <path d="M20 56V64" stroke="#b45309" stroke-width="1.5" />
            </svg>
        </template>

        <!-- AGUSTUSAN: umbul-umbul merah putih -->
        <template v-else-if="theme === 'kemerdekaan'">
            <svg class="ev-swing absolute right-1.5 top-0 w-7 sm:w-9 lg:w-11" style="animation-delay:.7s" viewBox="0 0 40 72"></svg>
            <svg class="absolute inset-x-0 top-0 w-full h-5 sm:h-6" viewBox="0 0 200 30" preserveAspectRatio="none">
                <path d="M0 3H200" stroke="#9ca3af" stroke-width="1" />
                <polygon
                    v-for="i in 6" :key="i"
                    :points="`${(i - 1) * 33 + 2},3 ${(i - 1) * 33 + 31},3 ${(i - 1) * 33 + 16.5},26`"
                    :fill="i % 2 ? '#dc2626' : '#ffffff'"
                    stroke="#d1d5db" stroke-width="0.8"
                />
            </svg>
        </template>
    </div>
</template>

<script setup>
defineProps({ theme: { type: String, default: null } });
</script>

<style scoped>
.ev-swing { transform-origin: 50% 0; animation: ev-swing 3.4s ease-in-out infinite; }
@keyframes ev-swing { 0%, 100% { transform: rotate(-4deg); } 50% { transform: rotate(4deg); } }
@media (prefers-reduced-motion: reduce) { .ev-swing { animation: none; } }
</style>