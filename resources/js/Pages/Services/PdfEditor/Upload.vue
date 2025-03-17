<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({
    file: null
});

const isUploading = ref(false);
const errors = ref([]);

const handleFileInput = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
    }
};

const uploadPdf = async () => {
    try {
        isUploading.value = true;
        errors.value = [];

        const formData = new FormData();
        formData.append('file', form.file);

        const response = await fetch(route('pdfEditor.upload'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();

        if (data.success) {
            router.visit(route('pdfEditor.edit', { id: data.documentId }));
        } else {
            errors.value = [data.error];
        }
    } catch (error) {
        errors.value = ['Upload failed. Please try again.'];
    } finally {
        isUploading.value = false;
    }
};
</script>

<template>
    <GuestLayout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Upload PDF for Editing</h2>
                
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        <li v-for="error in errors" :key="error">{{ error }}</li>
                    </ul>
                </div>

                <form @submit.prevent="uploadPdf" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Choose PDF File</label>
                        <input
                            type="file"
                            accept="application/pdf"
                            @change="handleFileInput"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <button
                        type="submit"
                        :disabled="isUploading || !form.file"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                    >
                        <span v-if="isUploading">Uploading...</span>
                        <span v-else>Upload and Edit</span>
                    </button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template> 