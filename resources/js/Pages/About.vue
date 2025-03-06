<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Contact from '@/Components/Contact.vue';
</script>
<template>
    <GuestLayout>
            <section class="dark:bg-gray-100 dark:text-gray-800">
                <div class="container max-w-5xl px-4 py-12 mx-auto">
                    <div class="grid gap-4 mx-4 sm:grid-cols-12">
                        <div class="col-span-12 sm:col-span-3">
                            <div
                                class="text-center sm:text-left mb-14 before:block before:w-24 before:h-3 before:mb-5 before:rounded-md before:mx-auto sm:before:mx-0 before:dark:bg-violet-600">
                                <h1 class="text-3xl font-semibold">About Us</h1>
                                <p class="text-sm font-bold tracking-wider uppercase dark:text-gray-600">At Document Buddy we do</p>
                            </div>
                        </div>
                        <div class="relative col-span-12 px-4 space-y-6 sm:col-span-9">
                            <div
                                class="col-span-12 space-y-12 relative px-4 sm:col-span-8 sm:space-y-8 sm:before:absolute sm:before:top-2 sm:before:bottom-0 sm:before:w-0.5 sm:before:-left-3 before:dark:bg-gray-300">
                                <div
                                    class="flex flex-col sm:relative sm:before:absolute sm:before:top-2 sm:before:w-4 sm:before:h-4 sm:before:rounded-full sm:before:left-[-35px] sm:before:z-[1] before:dark:bg-violet-600">
                                    <h3 class="text-xl font-semibold tracking-wide">Trying to help </h3>
                                    <p class="mt-3">We are always trying to come up with solutions that could help the people. Something that could save them a fough and save them a bunch of time and effort</p>
                                </div>
                                <div
                                    class="flex flex-col sm:relative sm:before:absolute sm:before:top-2 sm:before:w-4 sm:before:h-4 sm:before:rounded-full sm:before:left-[-35px] sm:before:z-[1] before:dark:bg-violet-600">
                                    <h3 class="text-xl font-semibold tracking-wide">Simple, Easy and Secure</h3>
                                    <p class="mt-3">We provide solutions to a variety of things starting with PDF and PDF To DOC and a bunch other features which are simple, reliable and secured for your use.
                                    </p>
                                </div>
                                <div
                                    class="flex flex-col sm:relative sm:before:absolute sm:before:top-2 sm:before:w-4 sm:before:h-4 sm:before:rounded-full sm:before:left-[-35px] sm:before:z-[1] before:dark:bg-violet-600">
                                    <h3 class="text-xl font-semibold tracking-wide">So who are we ?</h3>
                                    <p class="mt-3">A small business and a small team trying to make a difference</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Contact></Contact>
    </GuestLayout>
</template>