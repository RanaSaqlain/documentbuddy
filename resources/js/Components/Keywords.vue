<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    pageKeywords: {
        type: Array,
        required: true
    }
});
function getColorClass(index) {
    const colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
    return colors[index % colors.length];
}
</script>
<template>
    <section class="p-6 bg-gray-100 dark:text-gray-800">
        <div class="container mx-auto">
            <h3 class="text-center font-bold text-lg">Keywords</h3>
            <div class="flex flex-wrap justify-center">
                <span v-for="(keyword, index) in pageKeywords" :key="index"
                    class="m-1 px-3 py-1 text-sm font-semibold text-white rounded-full"
                    :class="getColorClass(index)">
                    {{ keyword }}
                </span>
            </div>
        </div>
    </section>
</template>