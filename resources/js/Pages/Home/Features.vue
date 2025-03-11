<script setup>
import { Link } from '@inertiajs/vue3';

</script>
<template>
	<section class="p-6 dark:bg-gray-100 dark:text-gray-800">
		<div class="container mx-auto">
			<h2 class="text-2xl font-bold">Pdf to scanable and searchable pdf
			
			</h2>
				<Link :href="route('Pdf2Pdf')" class="text-blue-900 underline font-sans font-semibold" prefetch>PDF Converter tool</Link>
			<p class="mt-2 ">You can upload a PDF file that is neither scannable nor selectable, meaning you cannot
				select any
				text within the PDF. This is where we come in to provide a solution. With our free-to-use PDF
				converter, you can upload your PDF file, and our OCR-based technology will convert your
				non-selectable PDF into a selectable and searchable format. This means you can export your
				resume from Canva and make it selectable and searchable with our tool, ensuring your PDF is
				ATS-compatible.</p>
		</div>
	</section>
</template>