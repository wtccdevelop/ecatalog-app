<template>
    <div class="py-8 px-4 sm:px-6 lg:px-16 bg-white dark:bg-neutral-800">
        <div class="max-w-[96rem] mx-auto">
            <div class="mb-4">
                <h1 class="text-lg tracking-widest font-extrabold dark:text-white">KAMI MENERIMA</h1>
                <hr class="border-b border-gray-200 my-2 dark:border-neutral-500" />
            </div>
            <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                <div
                    v-for="method in paymentMethods"
                    :key="method.alt"
                    class="bg-white px-2 py-1 overflow-hidden rounded-lg flex items-center justify-center"
                >
                    <img
                        class="w-full size-10 object-contain rounded-md"
                        :src="method.src"
                        :alt="method.alt"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { paymentMethods } from '../data/payments.js';
</script>
