<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';

const form = useForm({
  file: null,
});

const downloadLink = ref(null);

async function convertPdfToDoc() {
  try {
    const response = await form.post('/convert-pdf-to-doc', {
      forceFormData: true,
      onSuccess: (response) => {
        // Handle the file download
        const url = window.URL.createObjectURL(new Blob([response.data]));
        downloadLink.value = url;
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'converted.docx');
        document.body.appendChild(link);
        link.click();
      },
    });
  } catch (error) {
    console.error('Conversion failed:', error);
  }
}
</script>

<template>
  <Head title="PDF to DOC Converter - Document Buddy" />
  <div class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-blue-600 text-white py-4">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Document Buddy</h1>
        <nav>
          <Link :href="route('register')" class="px-3 py-2 hover:underline">Register</Link>
          <Link :href="route('login')" class="px-3 py-2 hover:underline">Log in</Link>
        </nav>
      </div>
    </header>

    <main class="flex-grow container mx-auto py-10 text-center">
      <h2 class="text-3xl font-bold mb-4">Convert PDF to DOC</h2>
      <p class="text-lg text-gray-600 mb-8">
        Easily convert your PDF documents to DOC format with our free tool. Our PDF to Document Converter is fast, reliable, and completely free.
      </p>
      <form @submit.prevent="convertPdfToDoc">
        <input class="mb-4 p-2 border border-gray-300" type="file" @input="form.file = $event.target.files[0]" />
        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
          {{ form.progress.percentage }}%
        </progress>
        <button class="px-5 border border-gray-600" type="submit">Convert to .DOC</button>
      </form>

      <section class="mt-10">
        <h3 class="text-2xl font-bold mb-4">Features</h3>
        <ul class="list-disc list-inside text-left mx-auto max-w-lg">
          <li>Fast and reliable PDF to DOC conversion</li>
          <li>Supports OCR for scanned PDFs</li>
          <li>Maintains document formatting</li>
          <li>Completely free to use</li>
        </ul>
      </section>

      <section class="mt-10">
        <h3 class="text-2xl font-bold mb-4">How It Works</h3>
        <p class="text-left mx-auto max-w-lg">
          Upload your PDF file using the form above. Our tool will process the file and convert it to a DOC format, which you can then download. The process is simple and quick, ensuring you get your converted document in no time.
        </p>
      </section>

      <section class="mt-10">
        <h3 class="text-2xl font-bold mb-4">FAQs</h3>
        <div class="text-left mx-auto max-w-lg">
          <h4 class="font-semibold">Is the conversion free?</h4>
          <p>Yes, our PDF to DOC converter is completely free to use.</p>
          <h4 class="font-semibold mt-4">Can I convert scanned PDFs?</h4>
          <p>Yes, our tool supports OCR, allowing you to convert scanned PDFs to editable DOC files.</p>
        </div>
      </section>
    </main>

    <footer class="bg-gray-800 text-white py-4 text-center">
      <p>&copy; 2025 Document Buddy. All rights reserved.</p>
    </footer>
  </div>
</template>