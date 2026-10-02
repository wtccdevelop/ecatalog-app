<template>
    <div
        v-if="theme && !hidden"
        class="pointer-events-none fixed bottom-3 right-3 z-40 ev-fly-in"
        :class="theme === 'christmas' ? 'w-28 sm:w-44' : 'w-14 sm:w-20'"
        aria-hidden="true"
    >
        <button
            type="button"
            @click="hide"
            class="pointer-events-auto absolute -top-1 -left-1 z-10 size-5 rounded-full bg-black/30 text-[10px] leading-none text-white opacity-60 hover:opacity-100 transition-opacity"
            aria-label="Sembunyikan hiasan"
        >✕</button>

        <!-- NATAL: kereta sinterklas -->
        <svg v-if="theme === 'christmas'" class="ev-bob w-full drop-shadow-md" viewBox="0 0 230 100">
            <!-- rusa -->
            <g>
                <path d="M20 42 16 30M16 30 11 28M16 30 17 24M26 42 29 30M29 30 34 28" stroke="#78350f" stroke-width="2" stroke-linecap="round" fill="none" />
                <ellipse cx="45" cy="62" rx="18" ry="10" fill="#92400e" />
                <circle cx="22" cy="49" r="8" fill="#92400e" />
                <circle cx="14.5" cy="51" r="3.5" fill="#ef4444" />
                <circle cx="20" cy="46" r="1.3" fill="#1f2937" />
                <circle cx="63" cy="58" r="3" fill="#fff" />
                <g stroke="#78350f" stroke-width="3" stroke-linecap="round">
                    <path d="M35 70 29 80" /><path d="M41 71 46 80" /><path d="M52 71 60 78" /><path d="M57 68 67 74" />
                </g>
            </g>
            <!-- tali kekang -->
            <path d="M30 56 Q80 50 118 64" stroke="#fbbf24" stroke-width="1.4" fill="none" />
            <!-- kereta -->
            <path d="M104 80H184Q196 80 196 70" stroke="#fbbf24" stroke-width="3" fill="none" stroke-linecap="round" />
            <path d="M125 70V80M165 70V80" stroke="#fbbf24" stroke-width="2" />
            <ellipse cx="172" cy="50" rx="12" ry="11" fill="#15803d" />
            <path d="M166 40q6 -6 12 0" stroke="#fbbf24" stroke-width="2" fill="none" />
            <path d="M130 46Q140 43 150 46V62H130Z" fill="#ef4444" />
            <circle cx="140" cy="40" r="8" fill="#fde0c5" />
            <path d="M133 42Q140 56 148 42Z" fill="#fff" />
            <path d="M131 37C132 26 142 22 150 30L148 37Z" fill="#dc2626" />
            <rect x="130" y="35" width="20" height="4" rx="2" fill="#fff" />
            <circle cx="151" cy="29" r="3" fill="#fff" />
            <path d="M110 58H186Q192 58 190 50L186 48Q184 66 170 70H122Q110 70 110 58Z" fill="#dc2626" />
            <!-- kilau di belakang -->
            <g fill="#fde68a">
                <circle class="ev-twinkle" style="animation-delay:0s" cx="206" cy="34" r="2.6" />
                <circle class="ev-twinkle" style="animation-delay:.6s" cx="215" cy="52" r="2" />
                <circle class="ev-twinkle" style="animation-delay:1.1s" cx="222" cy="40" r="1.6" />
            </g>
        </svg>

        <!-- LEBARAN: bulan sabit + lentera -->
        <svg v-else-if="theme === 'lebaran'" class="w-full drop-shadow-md" viewBox="0 0 120 130">
            <g class="ev-bob">
                <path d="M70 6A30 30 0 1 0 70 62A23 23 0 1 1 70 6Z" fill="#fbbf24" />
                <path class="ev-twinkle" d="M92 22l2.4 5 5.4.6-4 3.8 1.1 5.4-4.9-2.7-4.9 2.7 1.1-5.4-4-3.8 5.4-.6z" fill="#fde68a" />
                <g class="ev-swing-svg" style="transform-origin:62px 58px">
                    <path d="M62 58V76" stroke="#9ca3af" stroke-width="1" />
                    <circle class="ev-pulse" cx="62" cy="99" r="24" fill="#fde68a" />
                    <g transform="translate(42 64)">
                        <path d="M13 12h14l-3 5H16z" fill="#b45309" />
                        <path d="M12 18h16l4 10v14l-4 10H12L8 42V28z" fill="#f59e0b" stroke="#b45309" stroke-width="1.2" />
                        <ellipse cx="20" cy="35" rx="6" ry="11" fill="#fef3c7" />
                        <rect x="13" y="52" width="14" height="4" rx="1.5" fill="#b45309" />
                    </g>
                </g>
            </g>
        </svg>

        <!-- AGUSTUSAN: bendera berkibar -->
        <svg v-else class="w-full drop-shadow-md" viewBox="0 0 120 130">
            <rect x="18" y="10" width="4" height="116" rx="2" fill="#9ca3af" />
            <circle cx="20" cy="8" r="5" fill="#fbbf24" />
            <g class="ev-wave" style="transform-origin:22px 20px">
                <rect x="22" y="14" width="76" height="22" fill="#dc2626" />
                <rect x="22" y="36" width="76" height="22" fill="#ffffff" stroke="#e5e7eb" />
            </g>
            <ellipse cx="20" cy="126" rx="14" ry="3" fill="#000" opacity=".12" />
        </svg>
    </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({ theme: { type: String, default: null } });

const KEY = 'ev_mascot_off';
const hidden = ref(false);
try { hidden.value = sessionStorage.getItem(KEY) === '1'; } catch (e) {}

function hide() {
    hidden.value = true;
    try { sessionStorage.setItem(KEY, '1'); } catch (e) {}
}
</script>

<style scoped>
.ev-fly-in { animation: ev-fly-in 1.6s cubic-bezier(.2,.8,.2,1) both; }
.ev-bob { animation: ev-bob 3.2s ease-in-out infinite; }
.ev-swing-svg { animation: ev-swing 3.6s ease-in-out infinite; }
.ev-wave { animation: ev-wave 1.8s ease-in-out infinite; }
.ev-twinkle { animation: ev-twinkle 1.8s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
.ev-pulse { animation: ev-pulse 2.4s ease-in-out infinite; opacity: .35; }

@keyframes ev-fly-in { from { transform: translateX(120%) translateY(-30px); opacity: 0; } to { transform: none; opacity: 1; } }
@keyframes ev-bob { 0%, 100% { transform: translateY(0) rotate(-1.5deg); } 50% { transform: translateY(-8px) rotate(1.5deg); } }
@keyframes ev-swing { 0%, 100% { transform: rotate(-5deg); } 50% { transform: rotate(5deg); } }
@keyframes ev-wave { 0%, 100% { transform: scaleX(1) skewY(-2deg); } 50% { transform: scaleX(.94) skewY(2deg); } }
@keyframes ev-twinkle { 0%, 100% { opacity: .3; transform: scale(.7); } 50% { opacity: 1; transform: scale(1.2); } }
@keyframes ev-pulse { 0%, 100% { opacity: .2; } 50% { opacity: .5; } }

@media (prefers-reduced-motion: reduce) {
    .ev-fly-in, .ev-bob, .ev-swing-svg, .ev-wave, .ev-twinkle, .ev-pulse { animation: none; }
}
</style>