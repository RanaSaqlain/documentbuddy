<script setup>
import { ref, onMounted } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import WebViewer from '@pdftron/webviewer/webviewer.min.js';

const props = defineProps({
    documentId: {
        type: String,
        required: true
    },
    documentUrl: {
        type: String,
        required: true
    }
});

const viewer = ref(null);
const instance = ref(null);

onMounted(async () => {
    const element = viewer.value;
    
    try {
        instance.value = await WebViewer({
            path: '/webviewer/lib',
            initialDoc: props.documentUrl,
            enableFilePicker: true,
            fullAPI: true,
            licenseKey: 'your_license_key_here' // Add your license key if you have one
        }, element);

        const { documentViewer, annotationManager, Tools } = instance.value.Core;

        // Set up viewer
        documentViewer.setViewerElement(element);
        documentViewer.setScrollViewElement(element);

        // Add custom tools and features
        setupCustomTools(Tools);
        
        // Load document
        await documentViewer.loadDocument(props.documentUrl);
        
    } catch (error) {
        console.error('Failed to initialize PDF editor:', error);
    }
});

const setupCustomTools = (Tools) => {
    // Add custom tools here
    // Example: Signature tool, Image insertion tool, etc.
};

const saveChanges = async () => {
    try {
        const doc = await instance.value.Core.documentViewer.getDocument();
        const data = await doc.getFileData();
        
        const response = await fetch(route('pdfEditor.save'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                documentId: props.documentId,
                changes: data,
            }),
        });

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.error);
        }

    } catch (error) {
        console.error('Failed to save changes:', error);
    }
};
</script>

<template>
    <GuestLayout>
        <div class="h-screen flex flex-col">
            <div class="flex-none bg-gray-100 p-4">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <h1 class="text-2xl font-bold">PDF Editor</h1>
                    <button
                        @click="saveChanges"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                    >
                        Save Changes
                    </button>
                </div>
            </div>

            <div class="flex-grow">
                <div ref="viewer" class="h-full"></div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.h-screen {
    height: calc(100vh - 64px); /* Adjust based on your layout */
}
</style> 