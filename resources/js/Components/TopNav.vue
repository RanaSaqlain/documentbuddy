<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { appName } from '@/constants.js';

const isMenuOpen = ref(false);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const getColorClass = (index) => {
    const colors = ['text-red-500', 'text-blue-500', 'text-green-500', 'text-yellow-500', 'text-purple-500', 'text-pink-500'];
    return colors[index % colors.length];
};
</script>
<template>
    <nav class="bg-wheat-800 shadow-md">
        <div class="container mx-auto px-2">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="text-3xl font-bold flex items-center space-x-1">
                    <span v-for="(char, index) in appName.split('')" :key="index" :class="getColorClass(index)">
                        {{ char }}
                    </span>
                </div>
                <!-- Desktop Menu -->
                <ul class="hidden md:flex space-x-6">
                    <li>
                        <Link
                            class="hover:text-violet-800 font-semibold hover:font-extrabold hover:underline text-gray-800"
                            href="/" prefetch="mount">Home</Link>
                    </li>
                    <li>
                        <Link
                            class="hover:text-violet-800 font-semibold hover:font-extrabold hover:underline text-gray-800"
                            :href="route('services')" prefetch="mount">Tools</Link>
                    </li>
                    <li>
                        <Link
                            class="hover:text-violet-800 font-semibold hover:font-extrabold hover:underline text-gray-800"
                            href="about" prefetch="mount">About</Link>
                    </li>
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
                    <li>
                        <a href="#" class="block text-center py-2 hover:bg-gray-200">Services</a>
                    </li>
                    <li>
                        <a href="#" class="block text-center py-2 hover:bg-gray-200">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
