<template>
    <div class="lg:col-start-2 max-w-[40rem] w-full">
        <div class="mb-4">
            <h1 class="text-lg tracking-widest font-extrabold dark:text-white">JASA KIRIM</h1>
            <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
        </div>
        <div class="w-60 sm:w-80 bg-white dark:bg-neutral-700 px-2 py-2 rounded-md">
            <img :src="img" alt="Jasa kirim" loading="lazy" />
        </div>
    </div>
</template>

<script setup>
const img = '/assets/WTC Cell - Official Storeee_files/jasakirim.png';
</script>