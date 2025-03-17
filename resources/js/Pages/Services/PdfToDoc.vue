<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Notifier from "@/Components/Notifier.vue";
import { ref } from "vue";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { appName } from "@/constants.js";
import Keywords from "@/Components/Keywords.vue"; // Importing Keywords component

const form = useForm({
  file: null,
});

const isLoading = ref(false);

const src = ref("");
const converted = ref(false);

const selectedFileName = ref("");
const downloadUrl = ref("");
const errors = ref([]);

function handleFileInput(event) {
  const file = event.target.files[0];
  if (file) {
    form.file = file;
    selectedFileName.value = file.name;
    src.value = URL.createObjectURL(file);
    converted.value = false;
    downloadUrl.value = ""; // Reset download URL
    errors.value = []; // Reset errors
  }
}

async function convertPdfToDoc() {
  try {
    errors.value = [];
    isLoading.value = true;

    await form.post(route('convertionDoc'), {
      preserveScroll: true,
      onSuccess: (page) => {
        if (page.props.success) {
          downloadUrl.value = page.props.downloadUrl;
          converted.value = true;

        } else {
          errors.value = [page.props.error];
        }
      },
      onError: (errors) => {
        if (errors.file) {
          errors.value = Array.isArray(errors.file) ? errors.file : [errors.file];
        } else {
          errors.value = ['An unexpected error occurred'];
        }
        // Redirect to the PDF-to-DOC page after successful conversion
        router.get(route('PdfToDoc'), {}, {
          preserveState: true,
          preserveScroll: true,
          replace: true
        });
      },
      onFinish: () => {
        isLoading.value = false;
        // Redirect to the PDF-to-DOC page after successful conversion
        router.get(route('PdfToDoc'), {}, {
          preserveState: true,
          preserveScroll: true,
          replace: true
        });
      }
    });
  } catch (error) {
    console.error("Conversion failed:", error);
    errors.value = ['An unexpected error occurred'];
    isLoading.value = false;
  }
}

const handlePdfLoaded = (data) => { };

const handlePdfError = (error) => {
  console.error("PDF loading error:", error);
};
</script>

<template>
  <GuestLayout>

    <Head title="Convert PDF to DOC - {{ appName }}">
      <meta name="keywords"
        content="PDF to DOC, PDF converter, document conversion, online PDF tool, free PDF to DOC converter" />
    </Head>
    <div class="bg-gray-100 min-h-screen flex flex-col">
      <main class="flex-grow container mx-auto py-10">
        <h1>{{ appName }}</h1>
        <h2 class="text-2xl font-bold mb-4">
          Convert your PDF files into editable DOC documents
        </h2>
        <p class="text-medium text-wrap text-gray-600 mb-8">
          Transform your PDFs into editable DOC format with our free tool. Enhance your document's usability and
          accessibility.
        </p>
        <Notifier />

        <div class="bg-white shadow-md mt-4 rounded-lg p-8 mb-4 mx-auto lg:w-1/2 sm:w-full">
          <div v-if="errors.length" class=" text-red-700 border p-4 rounded-lg mb-4">
            <ul>
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </div>
          <div v-if="downloadUrl" class="w-full mb-10 text-center">
            <a :href="downloadUrl"
              class="text-white hover:bg-violet-500 border border-gray-400 rounded-lg p-4 font-bold bg-teal-500"
              download>
              <svg class="inline-block w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                  d="M12 0C10.9 0 10 .9 10 2v10H7l5 5 5-5h-3V2c0-1.1-.9-2-2-2zm0 24c-1.1 0-2-.9-2-2h4c0 1.1-.9 2-2 2z" />
              </svg>
              Download Your DOC File
            </a>
            <div class="w-full mt-10 font-bold">OR</div>
          </div>
          <form @submit.prevent="convertPdfToDoc" class="text-center">
            <h2 class="text-2xl font-bold mb-6 text-center">
              Choose your file or drop it in the browser
            </h2>
            <div class="mb-6">
              <input id="pdf-upload"
                class="p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/2 transition duration-200 hover:border-blue-400"
                type="file" @input="handleFileInput" accept="application/pdf" />
            </div>
            <button
              class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition duration-200"
              type="submit" :disabled="isLoading">
              <span v-if="isLoading">
                <svg class="animate-spin h-5 w-5 mr-3 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 100 12v2a8 8 0 01-8-8z">
                  </path>
                </svg>
                Converting...
              </span>
              <span v-else>Convert to DOC</span>
            </button>
          </form>

        </div>
        <div class="flex justify-center">
          <div v-if="src && !downloadUrl" class="w-1/2">
            <PdfViewer :src="src" :lazyLoading="true" @loaded="handlePdfLoaded" @error="handlePdfError" />
          </div>
        </div>
        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">Features</h3>
          <ul class="list-disc list-inside  max-w-lg">
            <li>Convert PDF files to editable DOC format</li>
            <li>Enhance document usability</li>
            <li>Completely free to use</li>
          </ul>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">How It Works</h3>
          <p class=" max-w-lg">
            Upload your PDF file using the form above. Our tool will process the file and convert it to a DOC format,
            which you can then download. The process is simple and quick, ensuring you get your converted document in no
            time.
          </p>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">FAQs</h3>
          <div class=" max-w-lg">
            <h4 class="font-semibold">Is the conversion free?</h4>
            <p>Yes, our PDF to DOC converter is completely free to use.</p>
            <h4 class="font-semibold mt-4">What is a DOC file?</h4>
            <p>
              A DOC file is a document file format used by Microsoft Word and other word processing software, allowing
              for easy editing and formatting.
            </p>
          </div>
        </section>

        <section class="mt-10">
          <Keywords :pageKeywords="[
            'PDF to DOC conversion',
            'Convert PDF to editable document',
            'Free PDF to DOC converter',
          ]" />
        </section>
      </main>
    </div>
  </GuestLayout>
</template>
