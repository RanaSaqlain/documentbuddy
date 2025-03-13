<script setup>
import TopNav from '@/Components/TopNav.vue';
import Footer from '@/Components/Footer.vue';
import { Head } from '@inertiajs/vue3';
import { appName } from '@/constants.js';

</script>

<template>
    <AppLoader />

    <Head>  
        <meta name="description"
        content="{{ appName }} is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta name="author" content="{{ appName }}">
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="{{ appName }} - Convert PDF to DOCX with Ease">
        <meta property="og:description"
            content="{{ appName }} is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://www.documentbuddy.com">
        <meta property="og:image" content="https://www.documentbuddy.com/images/og-image.jpg">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ appName }} - Convert PDF to DOCX with Ease">
        <meta name="twitter:description" content="{{ appName }} is your go-to tool for converting PDF files to DOCX format. Fast, secure, and easy to use. Try it now for free!">
        <meta name="twitter:image" content="https://www.documentbuddy.com/images/og-image.jpg">

    </Head>
    <TopNav></TopNav>
    <section>
        <slot />
    </section>
    <Footer></Footer>
</template>
