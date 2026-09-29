<template>
    <div class="py-6 px-3 flex justify-center font-poppins dark:bg-neutral-700">
        <div class="flex flex-col group bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-800 shadow-md rounded-4xl overflow-hidden hover:shadow-lg transition">
            <div class="relative pt-[40%] rounded-t-xl overflow-hidden">
                <img
                    class="size-full absolute top-0 start-0 object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-t-xl"
                    :src="gskImage"
                    alt="GSK Tukar Tambah"
                    loading="lazy"
                />
            </div>
            <div class="p-4 md:p-5 flex flex-col justify-end">
                <div class="px-20 lg:px-60 sm:px-40 py-1">
                    <a
                        href="https://tukartambah.gskstore.id/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold lg:py-2 lg:px-6 px-4 py-2 rounded-full shadow-md transform hover:scale-105 transition-all duration-300 flex items-center space-x-2 sm:text-base text-xs"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        Tukar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const gskImage = '/assets/images/bekas-gsk.jpg';
</script>
