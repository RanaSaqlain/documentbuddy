<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    pageKeywords: {
        type: Array,
        required: true
    }
});

function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
<template>
    <section class="p-6 dark:bg-gray-100 dark:text-gray-800">
        <div class="container mx-auto">
            <h3 class="text-center text-lg">Keywords</h3>
            <div class="flex flex-wrap justify-center">
                <span v-for="(keyword, index) in pageKeywords" :key="index"
                    class="m-1 px-3 py-1 text-sm font-semibold text-white rounded-full"
                    :style="{ backgroundColor: getRandomColor() }">
                    {{ keyword }}
                </span>
            </div>
        </div>
    </section>
</template>