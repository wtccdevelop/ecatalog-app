<template>
    <canvas
        v-if="theme"
        ref="canvas"
        class="pointer-events-none fixed inset-0 z-40 h-full w-full"
        aria-hidden="true"
    ></canvas>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({ theme: { type: String, default: null } });

const canvas = ref(null);
let ctx = null, raf = 0, parts = [], w = 0, h = 0, dpr = 1, last = 0, time = 0, reduced = false;

const rnd = (a, b) => a + Math.random() * (b - a);
const isDark = () => document.documentElement.classList.contains('dark');

function count() {
    if (props.theme === 'christmas') return Math.round(Math.min(70, w / 22));
    if (props.theme === 'lebaran') return Math.round(Math.min(26, w / 50));
    return Math.round(Math.min(30, w / 45));
}

function make(initial) {
    const t = props.theme;
    const p = { x: rnd(0, w), y: initial ? rnd(0, h) : 0, ph: rnd(0, 6.28), sw: rnd(0.4, 1.2), rot: rnd(0, 6.28), vr: rnd(-1.5, 1.5), a: rnd(0.35, 0.75), c: 0, r: 0, vy: 0 };

    if (t === 'christmas') {
        p.r = rnd(1.2, 3.8);
        p.vy = 14 + p.r * 6;
        if (!initial) p.y = -10;
    } else if (t === 'lebaran') {
        p.r = rnd(3, 6);
        p.vy = -rnd(8, 20);
        p.c = Math.floor(rnd(0, 3));
        if (!initial) p.y = h + 10;
    } else {
        p.r = rnd(3, 5);
        p.vy = rnd(22, 42);
        p.c = Math.random() < 0.5 ? 0 : 1;
        if (!initial) p.y = -10;
    }
    return p;
}

function star(x, y, r) {
    ctx.beginPath();
    for (let i = 0; i < 8; i++) {
        const a = (i * Math.PI) / 4 - Math.PI / 2;
        const rr = i % 2 ? r * 0.35 : r;
        ctx.lineTo(x + Math.cos(a) * rr, y + Math.sin(a) * rr);
    }
    ctx.closePath();
    ctx.fill();
}

const GOLD = ['#fbbf24', '#34d399', '#fde68a'];

function draw(p) {
    const t = props.theme;
    const x = p.x + Math.sin(time * p.sw + p.ph) * 14;

    if (t === 'christmas') {
        const col = isDark() ? '255,255,255' : '147,197,253';
        ctx.fillStyle = `rgba(${col},${p.a})`;
        ctx.strokeStyle = `rgba(${col},${p.a})`;
        if (p.r > 2.8) {
            // kepingan salju kecil
            ctx.lineWidth = 1;
            ctx.beginPath();
            for (let i = 0; i < 3; i++) {
                const a = p.rot + (i * Math.PI) / 3;
                ctx.moveTo(x - Math.cos(a) * p.r * 1.5, p.y - Math.sin(a) * p.r * 1.5);
                ctx.lineTo(x + Math.cos(a) * p.r * 1.5, p.y + Math.sin(a) * p.r * 1.5);
            }
            ctx.stroke();
        } else {
            ctx.beginPath();
            ctx.arc(x, p.y, p.r, 0, 6.283);
            ctx.fill();
        }
    } else if (t === 'lebaran') {
        const tw = 0.4 + 0.6 * (0.5 + 0.5 * Math.sin(time * 2 + p.ph));
        ctx.globalAlpha = p.a * tw;
        ctx.fillStyle = GOLD[p.c];
        star(x, p.y, p.r);
        ctx.globalAlpha = 1;
    } else {
        ctx.save();
        ctx.translate(x, p.y);
        ctx.rotate(p.rot);
        ctx.globalAlpha = Math.min(0.85, p.a + 0.1);
        ctx.fillStyle = p.c ? '#ffffff' : '#dc2626';
        ctx.strokeStyle = '#d1d5db';
        ctx.lineWidth = 0.8;
        ctx.fillRect(-p.r, -p.r / 2, p.r * 2, p.r);
        if (p.c) ctx.strokeRect(-p.r, -p.r / 2, p.r * 2, p.r);
        ctx.restore();
        ctx.globalAlpha = 1;
    }
}

function tick(now) {
    const dt = Math.min((now - last) / 1000, 0.05);
    last = now;
    time += dt;

    ctx.clearRect(0, 0, w, h);

    for (let i = 0; i < parts.length; i++) {
        const p = parts[i];
        p.y += p.vy * dt;
        p.rot += p.vr * dt;

        if (p.vy > 0 && p.y > h + 12) parts[i] = make(false);
        else if (p.vy < 0 && p.y < -12) parts[i] = make(false);
        else draw(p);
    }

    raf = requestAnimationFrame(tick);
}

function resize() {
    if (!canvas.value) return;
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = window.innerWidth;
    h = window.innerHeight;
    canvas.value.width = w * dpr;
    canvas.value.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    parts = Array.from({ length: count() }, () => make(true));
}

function onVisibility() {
    if (document.hidden) {
        cancelAnimationFrame(raf);
        raf = 0;
    } else if (!raf) {
        last = performance.now();
        raf = requestAnimationFrame(tick);
    }
}

function stop() {
    cancelAnimationFrame(raf);
    raf = 0;
    window.removeEventListener('resize', resize);
    document.removeEventListener('visibilitychange', onVisibility);
}

function start() {
    stop();
    if (!props.theme || reduced || !canvas.value) return;

    ctx = canvas.value.getContext('2d');
    if (!ctx) return;

    resize();
    window.addEventListener('resize', resize);
    document.addEventListener('visibilitychange', onVisibility);
    last = performance.now();
    raf = requestAnimationFrame(tick);
}

onMounted(() => {
    reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    start();
});

// tema baru dimuat setelah /api/site selesai, atau berganti
watch(() => props.theme, () => start(), { flush: 'post' });

onUnmounted(stop);
</script>