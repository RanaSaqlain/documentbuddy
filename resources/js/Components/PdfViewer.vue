<template>
    <div class="pdf-container" :class="{ 'loading': loading }">
        <div v-if="loading" class="pdf-loader">Loading PDF...</div>
        <vue-pdf-embed :source="src" :page="page" :width="width" :height="height" :rotation="rotation"
            :annotation="annotations" :renderTextLayer="renderText" :renderAnnotationLayer="renderAnnotations"
            :renderInteractiveForms="renderInteractiveForms" :lazyLoading="lazyLoading" @loading="onLoading"
            @loaded="onLoaded" @error="onError" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import VuePdfEmbed from 'vue-pdf-embed';

// Props definition
const props = defineProps({
    src: {
        type: [String, Object, Uint8Array],
        required: true
    },
    page: {
        type: Number,
        default: 1
    },
    width: {
        type: Number,
        default: null
    },
    height: {
        type: Number,
        default: null
    },
    rotation: {
        type: Number,
        default: 0
    },
    annotations: {
        type: Boolean,
        default: true
    },
    renderText: {
        type: Boolean,
        default: true
    },
    renderAnnotations: {
        type: Boolean,
        default: true
    },
    renderInteractiveForms: {
        type: Boolean,
        default: true
    },
    lazyLoading: {
        type: Boolean,
        default: false
    }
});

// Emits
const emit = defineEmits(['loaded', 'loading', 'error']);

// Component state
const loading = ref(true);

// Event handlers
const onLoading = () => {
    loading.value = true;
    emit('loading');
};

const onLoaded = () => {
    loading.value = false;
    emit('loaded');
};

const onError = (error) => {
    loading.value = false;
    emit('error', error);
};

onMounted(() => {
    // Any initialization code if needed
});
</script>

<style scoped>
.pdf-container {
    position: relative;
    min-height: 200px;
}

.pdf-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.loading {
    opacity: 0.7;
}
</style>