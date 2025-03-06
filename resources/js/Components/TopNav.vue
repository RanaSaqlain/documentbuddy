<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
const isMenuOpen = ref(false);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>
<template>
    <nav class="bg-wheat-800 shadow-md">
        <div class="container mx-auto px-2">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="text-3xl font-bold flex items-center space-x-1">
                    <span class="text-red-500 text-4xl -mt-1">D</span>
                    <span class="text-orange-500 text-2xl">o</span>
                    <span class="text-yellow-500 text-3xl -mb-1">c</span>
                    <span class="text-green-500 text-4xl">u</span>
                    <span class="text-blue-500 text-2xl -mt-1">m</span>
                    <span class="text-indigo-500 text-3xl">e</span>
                    <span class="text-violet-500 text-4xl -mb-1">n</span>
                    <span class="text-red-500 text-3xl">t</span>
                    <span class="text-teal-500 text-4xl uppercase">B</span>
                    <span class="text-green-500 text-2xl lowercase -mt-1">u</span>
                    <span class="text-blue-500 text-3xl uppercase">D</span>
                    <span class="text-indigo-500 text-2xl lowercase -mb-1">d</span>
                    <span class="text-violet-500 text-3xl uppercase">Y</span>
                </div>
                <!-- Desktop Menu -->
                <ul class="hidden md:flex space-x-6">
                    <li><Link class="hover:text-violet-800 hover:font-extrabold text-gray-800" href="/" prefetch="mount">Home</Link></li>
                    <li><Link class="hover:text-violet-800 hover:font-extrabold text-gray-800" href="about" prefetch="mount">About</Link></li>
                    <li><a href="#" class="hover:text-violet-800 hover:font-extrabold text-gray-800">Services</a></li>
                    <li><a href="#" class="hover:text-violet-800 hover:font-extrabold text-gray-800">Contact</a></li>
                </ul>

                <!-- Mobile Menu Toggle -->
                <button class="md:hidden focus:outline-none" @click="toggleMenu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div v-if="isMenuOpen" class="md:hidden">
                <ul class="flex flex-col space-y-4 py-2">
                    <li><a href="#" class="block text-center py-2 hover:bg-gray-200">Home</a></li>
                    <li><a href="#" class="block text-center py-2 hover:bg-gray-200">About</a></li>
                    <li><a href="#" class="block text-center py-2 hover:bg-gray-200">Services</a></li>
                    <li><a href="#" class="block text-center py-2 hover:bg-gray-200">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</template>