<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Keywords from '@/Components/Keywords.vue';
import { ref } from 'vue';

const form = useForm({
    file: null,
});


// Reactive variable to hold error messages
const errorMessage = ref('');
const successMessage = ref(''); // Reactive variable to hold success messages

const handleFileInput = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
    }
}

async function conversion() {
    try {
        await form.post(route('convertionText'), {
            onSuccess: (response) => {
                successMessage.value = response.props.text;
            },
            onError: (errors) => {
                errorMessage.value = errors.file ? errors.file[0] : 'An error occurred during the conversion.';
            }
        });
    } catch (error) {
        console.log("error " + error);
    }
}

const pageKeywords = ['image to text', 'OCR', 'text extraction', 'image processing', 'convert image to text'];
</script>
<template>
    <GuestLayout>

        <Head title="Image to Text">
            <meta name="keywords"
                content="image to text, OCR, text extraction, image processing, convert image to text">
        </Head>
        <div class="bg-gray-100 min-h-screen flex flex-col">
            <main class="flex-grow container mx-auto py-10">
                <section class="p-6 my-8 ">
                    <h1 class="text-3xl font-bold">Image to Text Conversion</h1>
                    <p class="mt-4 text-lg">Transform your images into editable text with our powerful OCR technology.
                        Whether you have scanned documents, photos, or any other image format, our service can extract
                        text
                        quickly and accurately.</p>
                </section>
                <section class="p-6 my-8 bg-white shadow-md rounded-lg w-1/2 mx-auto">
                    <form @submit.prevent="conversion" class="text-center">
                        <h2 class="text-2xl font-bold mb-6">
                            Upload your image
                        </h2>
                        <!-- Display error message if it exists -->
                        <div v-if="errorMessage" class="mb-4 text-red-600">
                            {{ errorMessage }}
                        </div>
                        <!-- Display success message if it exists -->
                        <div v-if="successMessage" class="mb-4 shadow-lg bg-gray-100 p-4">
                            <h3 class="text-lg font-semibold border-b-2 border-gray-300">Converted Text Output</h3>
                            <div class="mt-4 p-4 bg-gray-50 rounded-md shadow-sm" v-html="successMessage"></div>
                        </div>
                        <div class="mb-6">
                            <input id="image-upload"
                                class="p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/2 transition duration-200 hover:border-blue-400"
                                type="file" @input="handleFileInput" accept="image/*" />
                        </div>
                        <button
                            class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                            type="submit">
                            Convert to text
                        </button>
                    </form>
                </section>



                <section class="p-6 my-8 bg-gray-100 rounded-lg">
                    <h2 class="text-2xl font-semibold">Features</h2>
                    <ul class="list-disc list-inside mt-2">
                        <li>High accuracy in text extraction</li>
                        <li>Supports multiple image formats (JPEG, PNG, etc.)</li>
                        <li>Fast processing time</li>
                        <li>Easy to use interface</li>
                        <li>Downloadable text files</li>
                    </ul>
                </section>

                <section class="p-6 my-8 ">
                    <h2 class="text-2xl font-semibold">Frequently Asked Questions (FAQ)</h2>
                    <div class="mt-2">
                        <h3 class="font-bold">1. What is OCR?</h3>
                        <p>OCR stands for Optical Character Recognition, a technology that converts different types of
                            documents,
                            such as scanned paper documents, PDF files, or images captured by a digital camera, into
                            editable and searchable data.</p>

                        <h3 class="font-bold">2. How long does the conversion take?</h3>
                        <p>The conversion time depends on the size and complexity of the image. However, our service is
                            optimized for speed.</p>

                        <h3 class="font-bold">3. What formats can I upload?</h3>
                        <p>You can upload images in formats such as JPEG, PNG, JPG, and GIF.</p>
                    </div>
                </section>

                <Keywords :pageKeywords="pageKeywords" />
            </main>
        </div>

    </GuestLayout>
</template>