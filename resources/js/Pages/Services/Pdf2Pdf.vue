<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ref } from "vue";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { appName } from '@/constants.js';


const form = useForm({
  file: null,
});

const removeFile = useForm({
  url: null,
});

const src = ref("");
const converted = ref();

const selectedFileName = ref("");
const downloadUrl = ref("");

function handleFileInput(event) {
  const file = event.target.files[0];
  if (file) {
    form.file = file;
    selectedFileName.value = file.name;
    src.value = URL.createObjectURL(file);
    converted.value = false;
  }
}

async function convertPdfToSearchable() {
  try {
    await form.post("/convert-pdf-to-searchable", {
      forceFormData: true,
      onSuccess: (response) => {
        downloadUrl.value = response.props.downloadUrl;
        src.value = downloadUrl.value;
        converted.value = true;
      },
      onError: (errors) => {
        console.error("Conversion failed:", errors);
      },
    });
  } catch (error) {
    console.error("Conversion failed:", error);
  }
}

const handlePdfLoaded = (data) => {};

const handlePdfError = (error) => {
  console.error("PDF loading error:", error);
};
</script>

<template>
  <GuestLayout>
    <Head title="Convert Non-Searchable PDF to Searchable PDF - {{ appName }}">
      <meta
        name="keywords"
        content="Pdf to ATS Pdf, pdf to scanable pdf, PDF to DOCX, PDF converter, document conversion, online PDF tool, free PDF converter"
      />
    </Head>
    <div class="bg-gray-100 min-h-screen flex flex-col">
      <main class="flex-grow container mx-auto py-10 text-center">
        <h2 class="text-3xl font-bold mb-4">
          Convert non-selectable PDF into selectable and searchable PDF
        </h2>
        <p class="text-lg text-gray-600 mb-8">
          Transform your non-selectable, non-searchable PDFs into searchable, ATS-friendly
          PDFs with our free tool. Enhance your document's accessibility and usability.
        </p>
        <div class="bg-white shadow-md rounded-lg p-8 mb-4 flex justify-center">
          <form
            @submit.prevent="convertPdfToSearchable"
            v-if="!downloadUrl"
            class="w-1/2"
          >
            <h2 class="text-2xl font-bold mb-6 text-center">
              Convert to Searchable PDF online
            </h2>
            <div class="mb-6">
              <label class="block text-lg font-medium text-gray-700 mb-2" for="pdf-upload"
                >Choose a PDF File</label
              >
              <input
                id="pdf-upload"
                class="p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/2 transition duration-200 hover:border-blue-400"
                type="file"
                @input="handleFileInput"
                accept="application/pdf"
              />
            </div>
            <button
              class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition duration-200"
              type="submit"
            >
              Convert to Searchable PDF
            </button>
          </form>
          <div v-if="downloadUrl" class="w-full">
            <a
              :href="downloadUrl"
              class="text-white hover:bg-violet-500 border border-gray-400 rounded-lg p-4 font-bold bg-teal-500"
              download
              >Download Your Searchable PDF</a
            >
          </div>
        </div>
        <div class="flex justify-center">
          <div v-if="src && !downloadUrl" class="w-1/2">
            <PdfViewer
              :src="src"
              :lazyLoading="true"
              @loaded="handlePdfLoaded"
              @error="handlePdfError"
            />
          </div>
        </div>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">Features</h3>
          <ul class="list-disc list-inside text-left mx-auto max-w-lg">
            <li>Convert non-searchable PDFs to searchable PDFs</li>
            <li>Enhance document accessibility</li>
            <li>Completely free to use</li>
          </ul>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">How It Works</h3>
          <p class="text-left mx-auto max-w-lg">
            Upload your non-searchable PDF file using the form above. Our tool will
            process the file and convert it to a searchable PDF format, which you can then
            download. The process is simple and quick, ensuring you get your converted
            document in no time.
          </p>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">FAQs</h3>
          <div class="text-left mx-auto max-w-lg">
            <h4 class="font-semibold">Is the conversion free?</h4>
            <p>Yes, our PDF to searchable PDF converter is completely free to use.</p>
            <h4 class="font-semibold mt-4">What is a searchable PDF?</h4>
            <p>
              A searchable PDF is a PDF document that has been processed with OCR to allow
              text search and selection.
            </p>
          </div>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">Keywords</h3>
          <p>
            Non-Searchable PDF to Searchable PDF, Non-Selectable PDF to Selectable PDF,
            Convert into ATS scanable resume
          </p>
        </section>
      </main>
    </div>
  </GuestLayout>
</template>
