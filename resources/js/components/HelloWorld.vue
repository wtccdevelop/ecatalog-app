<template>
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Hello Vue!</h1>
        <p class="text-gray-600 mb-4">Vue berhasil terintegrasi dengan Laravel.</p>
        
        <div class="text-center">
            <p class="text-xl font-semibold text-blue-600 mb-2">Counter: {{ counter }}</p>
            <button 
                @click="counter++"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition"
            >
                + Tambah
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const counter = ref(0);
</script>