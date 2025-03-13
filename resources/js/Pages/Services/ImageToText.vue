<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import Keywords from '@/Components/Keywords.vue';

const pageKeywords = ['image to text', 'OCR', 'text extraction', 'image processing', 'convert image to text'];
</script>
<template>
    <GuestLayout>
        <Head title="Image to Text">
            <meta name="keywords"
                content="image to text, OCR, text extraction, image processing, convert image to text">
        </Head>
        
        <section class="p-6 my-8 bg-white rounded-lg">
            <h1 class="text-3xl font-bold">Image to Text Conversion</h1>
            <p class="mt-4 text-lg">Transform your images into editable text with our powerful OCR technology. 
            Whether you have scanned documents, photos, or any other image format, our service can extract text quickly and accurately.</p>
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

        <section class="p-6 my-8 bg-white rounded-lg">
            <h2 class="text-2xl font-semibold">Frequently Asked Questions (FAQ)</h2>
            <div class="mt-2">
                <h3 class="font-bold">1. What is OCR?</h3>
                <p>OCR stands for Optical Character Recognition, a technology that converts different types of documents, 
                such as scanned paper documents, PDF files, or images captured by a digital camera, into editable and searchable data.</p>
                
                <h3 class="font-bold">2. How long does the conversion take?</h3>
                <p>The conversion time depends on the size and complexity of the image. However, our service is optimized for speed.</p>
                
                <h3 class="font-bold">3. What formats can I upload?</h3>
                <p>You can upload images in formats such as JPEG, PNG, JPG, and GIF.</p>
            </div>
        </section>

        <Keywords :pageKeywords="pageKeywords" />
    </GuestLayout>
</template>