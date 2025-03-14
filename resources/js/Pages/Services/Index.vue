<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { appName } from '@/constants';
import { Head, Link } from "@inertiajs/vue3";
import Keywords from '@/Components/Keywords.vue'; // Importing Keywords component
const pageKeywords = ['Tools', 'Pdf to selectable, searchable and ATS readable PDF'];
</script>
<template>
    <GuestLayout>
        <Head title="Tools"></Head>
        <section class="dark:bg-gray-100 dark:text-gray-800">
            <div class="container flex flex-col justify-center p-6 mx-auto sm:py-12 lg:py-24 lg:flex-row lg:justify-between">
                <div class="flex flex-col justify-center p-6 text-center rounded-sm lg:max-w-md xl:max-w-lg lg:text-left">
                    <h1 class="text-5xl font-bold leading-none sm:text-6xl">Tools
                        <span class="dark:text-violet-600">Features</span>
                    </h1>
                    <p class="mt-4 text-lg">Use our powerful tools for PDF management.</p>
                </div>
            </div>
        </section>
        <section class="p-6 my-8 bg-white rounded-lg ">
            <div class="container grid grid-cols-1 gap-6 mx-auto md:grid-cols-3 xl:grid-cols-4">
                <Link :href="route('Pdf2Pdf')" class="flex flex-col items-center p-4 transition-transform transform hover:scale-105 rounded-lg bg-gray-50 ">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-violet-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-8 h-8">
                            <path d="M487.938,162.108l-224-128a16,16,0,0,0-15.876,0l-224,128a16,16,0,0,0,.382,28l224,120a16,16,0,0,0,15.112,0l224-120a16,16,0,0,0,.382-28ZM256,277.849,65.039,175.548,256,66.428l190.961,109.12Z"></path>
                            <path d="M263.711,394.02,480,275.061V238.539L256,361.74,32,238.539v36.522L248.289,394.02a16.005,16.005,0,0,0,15.422,0Z"></path>
                            <path d="M32,362.667,248.471,478.118a16,16,0,0,0,15.058,0L480,362.667V326.4L256,445.867,32,326.4Z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold">PDF to Searchable PDF</h2>
                    <p class="text-center text-gray-600">Convert your PDFs into selectable and searchable documents.</p>
                </Link>

                <Link :href="route('ImageToText')" class="flex flex-col items-center p-4 transition-transform transform hover:scale-105 rounded-lg bg-gray-50 ">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-violet-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-8 h-8">
                            <path d="M487.938,162.108l-224-128a16,16,0,0,0-15.876,0l-224,128a16,16,0,0,0,.382,28l224,120a16,16,0,0,0,15.112,0l224-120a16,16,0,0,0,.382-28ZM256,277.849,65.039,175.548,256,66.428l190.961,109.12Z"></path>
                            <path d="M263.711,394.02,480,275.061V238.539L256,361.74,32,238.539v36.522L248.289,394.02a16.005,16.005,0,0,0,15.422,0Z"></path>
                            <path d="M32,362.667,248.471,478.118a16,16,0,0,0,15.058,0L480,362.667V326.4L256,445.867,32,326.4Z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold">Image to Text</h2>
                    <p class="text-center text-gray-600">Convert your image into text.</p>
                </Link>
                <!-- Additional tools can be added here in the future -->
            </div>
        </section>
        <Keywords :pageKeywords="pageKeywords" /> <!-- Using Keywords component for SEO -->
    </GuestLayout>
</template>