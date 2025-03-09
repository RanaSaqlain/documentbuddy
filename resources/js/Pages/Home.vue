<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Hero from './Home/Hero.vue'
import Features from './Home/Features.vue'
import { Head } from '@inertiajs/vue3'
</script>
<template>
    <GuestLayout>

        <Head>
            <meta name="keywords"
                content="PDF to DOCX, PDF converter, document conversion, online PDF tool, free PDF converter, free seal maker, free stamp designer, stamp maker">
        </Head>
        <Hero></Hero>
        <Features></Features>
    </GuestLayout>
</template>