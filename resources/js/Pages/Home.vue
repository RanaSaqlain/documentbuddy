<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Hero from './Home/Hero.vue';
import Features from './Home/Features.vue';
import { Head } from '@inertiajs/vue3';
import Keywords from '@/Components/Keywords.vue';

const pageKeywords = ['PDF to Selectable PDF', 'PDF to Searchable PDF', 'PDF to ATS scanlable PDF', 'PDF to PDF Converter']
</script>
<template>
    <GuestLayout>
        <Head title="Online Document Converter">
            <meta name="keywords"
                content="PDF to DOCX, PDF converter, document conversion, online PDF tool, free PDF converter, free seal maker, free stamp designer, stamp maker" />
        </Head>
        <Hero />
        <Features />
        <Keywords :pageKeywords="pageKeywords" />
    </GuestLayout>
</template>