<template>
    <div class="pdf-container">
        <div v-if="loading" class="pdf-loader">Loading PDF...</div>
        <div v-if="error" class="pdf-error">{{ error }}</div>

        <!-- Navigation controls -->
        <div v-if="pageCount > 1" class="pdf-navigation">
            <button @click="currentPage > 1 && (currentPage--)" :disabled="currentPage === 1" class="nav-button">
                <
            </button>
            <span> {{ currentPage }} of {{ pageCount }}</span>
            <button @click="currentPage < pageCount && (currentPage++)" :disabled="currentPage === pageCount"
                class="nav-button">
                >
            </button>
        </div>

        <!-- PDF Viewer -->
        <vue-pdf-embed :source="src" :page="currentPage" :width="width" :height="height" :rotation="rotation"
            :annotation="annotations" :renderTextLayer="renderText" :renderAnnotationLayer="renderAnnotations"
            :renderInteractiveForms="renderInteractiveForms" @loading="onLoading" @loaded="onLoaded" @error="onError" />
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
const emit = defineEmits(['loaded', 'loading', 'error', 'page-change']);

// Component state
const loading = ref(true);
const error = ref(null);
const pageCount = ref(0);
const currentPage = ref(1);

// Event handlers
const onLoading = () => {
    loading.value = true;
    emit('loading');
};

const onLoaded = (pdf) => {
    loading.value = false;
    // If the loaded event provides the document, we can get the page count
    if (pdf && pdf.numPages) {
        pageCount.value = pdf.numPages;
    }
    emit('loaded', { pageCount: pageCount.value, currentPage: currentPage.value });
};

const onError = (err) => {
    loading.value = false;
    error.value = `Error displaying PDF: ${err.message || err}`;
    emit('error', err);
};

// Manual page counting fallback
const countPages = async () => {
    try {
        // This is a workaround if the loaded event doesn't provide page count
        // You might need to adjust based on what vue-pdf-embed provides
        const loadingTask = window.pdfjsLib.getDocument(props.src);
        const pdfDocument = await loadingTask.promise;
        pageCount.value = pdfDocument.numPages;
    } catch (err) {
        console.error('Error counting pages:', err);
    }
};

onMounted(() => {
    // If the library doesn't provide page count in the loaded event
    // you might need to use this fallback
    if (window.pdfjsLib) {
        countPages();
    }
});
</script>

<style scoped>
.pdf-container {
    position: relative;
    min-height: 200px;
    user-select: text; 
}
.pdf-container * {
  user-select: text;
}
.pdf-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.pdf-error {
    color: #e53e3e;
    padding: 1rem;
    border: 1px solid #e53e3e;
    border-radius: 0.25rem;
    background-color: #fff5f5;
    margin: 1rem 0;
}

.pdf-navigation {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin: 1rem 0;
}

.nav-button {
    padding: 0.5rem 1rem;
    background-color: #4299e1;
    color: white;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
}

.nav-button:disabled {
    background-color: #a0aec0;
    cursor: not-allowed;
}
</style>