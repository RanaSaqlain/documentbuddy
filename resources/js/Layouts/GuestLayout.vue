<script setup>
import TopNav from '@/Components/TopNav.vue';
import Footer from '@/Components/Footer.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>

    <Head>
        <meta name="description"
        content="Document Buddy is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta name="author" content="Document Buddy">
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="Document Buddy - Convert PDF to DOCX with Ease">
        <meta property="og:description"
            content="Document Buddy is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://www.documentbuddy.com">
        <meta property="og:image" content="https://www.documentbuddy.com/images/og-image.jpg">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Document Buddy - Convert PDF to DOCX with Ease">
        <meta name="twitter:description" content="Document Buddy is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta name="twitter:image" content="https://www.documentbuddy.com/images/og-image.jpg">

    </Head>
    <TopNav></TopNav>
    <section>
        <slot />
    </section>
    <Footer></Footer>
</template>
