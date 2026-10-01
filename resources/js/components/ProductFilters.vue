<template>
    <div class="space-y-6">
        <!-- Brand -->
        <section>
            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-400 dark:text-neutral-500 mb-3">Brand</h3>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="$emit('select', { brand: '' })" :class="chip(!brand)">Semua</button>
                <button
                    v-for="b in brands"
                    :key="b.id"
                    type="button"
                    @click="$emit('select', { brand: b.slug })"
                    :class="chip(brand === b.slug)"
                >
                    {{ b.name }}
                </button>
            </div>
        </section>

        <!-- Harga -->
        <section>
            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-400 dark:text-neutral-500 mb-3">Kategori Harga</h3>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="$emit('select', { price: '' })" :class="chip(!price)">Semua harga</button>
                <button
                    v-for="c in categories"
                    :key="c.id"
                    type="button"
                    @click="$emit('select', { price: String(c.id) })"
                    :class="chip(price === String(c.id))"
                >
                    {{ c.name }}
                </button>
            </div>
            <p v-if="activeRange" class="mt-3 text-[11px] text-gray-500 dark:text-neutral-400">
                Rentang: <span class="font-semibold">{{ activeRange }}</span>
            </p>
        </section>

        <button
            v-if="brand || price"
            type="button"
            @click="$emit('reset')"
            class="w-full py-2 rounded-xl text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-neutral-700 dark:text-blue-300 dark:hover:bg-neutral-600 transition-colors"
        >
            Reset
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { formatRupiah } from '../lib/format.js';

const props = defineProps({
    brands: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    brand: { type: String, default: '' },
    price: { type: String, default: '' },
});
defineEmits(['select', 'reset']);

const chip = (active) => [
    'px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200',
    active
        ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30'
        : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-700 ring-1 ring-gray-200 dark:bg-neutral-700 dark:text-neutral-200 dark:ring-neutral-600 dark:hover:bg-neutral-600',
];

const activeRange = computed(() => {
    const c = props.categories.find((x) => String(x.id) === props.price);
    if (!c || (c.min_price == null && c.max_price == null)) return '';
    if (c.max_price == null) return `≥ ${formatRupiah(c.min_price)}`;
    if (c.min_price == null) return `≤ ${formatRupiah(c.max_price)}`;
    return `${formatRupiah(c.min_price)} – ${formatRupiah(c.max_price)}`;
});
</script>