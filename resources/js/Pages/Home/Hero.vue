<script setup>
import { Link } from '@inertiajs/vue3';
import { appName } from '@/constants.js';


</script>
<template>
	<section>
		<div class="dark:bg-teal-600">
			<div class="container flex flex-col items-center px-4 py-16 pb-24 mx-auto text-center lg:pb-56 md:py-32 md:px-10 lg:px-32 dark:text-gray-50">
				<h1 class="text-5xl font-bold leading-none sm:text-6xl xl:max-w-3xl dark:text-gray-50">Online Document Editor Tool.</h1>
				<p class="mt-6 mb-8 text-lg sm:mb-12 xl:max-w-3xl dark:text-gray-50">{{ appName }} is an online document editor tool. Here you can perform a variety of functions upon your document i-e Image to Text,PDF to DOC, PDF to ATS PDF, PDF to searchable and selectable PDF and many more. </p>
				<div class="flex flex-wrap justify-center">
					<Link prefetch :href="route('services')" class="px-8 py-3 m-2 text-lg font-semibold rounded dark:bg-gray-100 dark:text-gray-900">PDF editor and converter</Link>
				</div>
			</div>
		</div>
		<img src="https://source.unsplash.com/random/480x320" alt="" class="w-5/6 max-h-96 mx-auto mb-12 -mt-20 dark:bg-gray-500 rounded-lg shadow-md lg:-mt-40">
	</section>
</template>