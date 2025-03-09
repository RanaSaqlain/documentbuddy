<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';

const form = useForm({
  file: null,
});
const src = ref('');


const selectedFileName = ref('');
const downloadUrl = ref('');

function handleFileInput(event) {
  const file = event.target.files[0];
  if (file) {
    form.file = file;
    selectedFileName.value = file.name;
    src.value = URL.createObjectURL(file);
  }
}


async function convertPdfToSearchable() {
  try {
    await form.post('/convert-pdf-to-searchable', {
      forceFormData: true,
      onSuccess: (response) => {
        downloadUrl.value = response.props.downloadUrl;
      },
      onError: (errors) => {
        console.error('Conversion failed:', errors);
      },
    });
  } catch (error) {
    console.error('Conversion failed:', error);
  }
}

const handlePdfLoaded = (data) => {
};

const handlePdfError = (error) => {
  console.error('PDF loading error:', error);
};

</script>

<template>
  <GuestLayout>

    <Head title="Convert Non-Searchable PDF to Searchable PDF - Document Buddy">
      <meta name="keywords"
        content="Pdf to ATS Pdf, pdf to scanable pdf, PDF to DOCX, PDF converter, document conversion, online PDF tool, free PDF converter">

    </Head>
    <div class="bg-gray-100 min-h-screen flex flex-col">
      <main class="flex-grow container mx-auto py-10 text-center">
        <h2 class="text-3xl font-bold mb-4">Convert Non-Searchable PDF to Searchable PDF</h2>
        <p class="text-lg text-gray-600 mb-8">
          Transform your non-searchable PDFs into searchable, ATS-friendly documents with our free tool. Enhance your
          document's accessibility and usability.
        </p>
        <div class="flex justify-between items-center">
          <form @submit.prevent="convertPdfToSearchable" class="bg-white shadow-md rounded-lg p-8 mb-4 w-1/2">
            <h2 class="text-2xl font-bold mb-6 text-center">Upload Your PDF</h2>
            <div class="mb-6">
              <label class="block text-lg font-medium text-gray-700 mb-2" for="pdf-upload">Choose a PDF File</label>
              <input id="pdf-upload"
                class="p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full transition duration-200 hover:border-blue-400"
                type="file" @input="handleFileInput" accept="application/pdf" />
              <p v-if="selectedFileName" class="mt-2 text-sm text-gray-600">Selected file: {{ selectedFileName }}</p>
            </div>
            <button
              class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition duration-200"
              type="submit">
              Convert to Searchable PDF
            </button>
          </form>
          <div class="w-1/2 flex items-center justify-center">
            <div v-if="src" class="">
              <PdfViewer :src="src" :lazyLoading="true"  @loaded="handlePdfLoaded"
                @error="handlePdfError" />
            </div>
            <div v-else class="bg-white shadow-md rounded-lg p-8 mb-4 w-3/4">
              <span class="text-sm">
                The uploaded and converted PDF will be displayed here for your convenience.
              </span>
            </div>
          </div>
        </div>

        <div v-if="downloadUrl" class="mt-10">
          <a :href="downloadUrl" class="text-blue-600 hover:underline" download>Download Converted PDF</a>
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
            Upload your non-searchable PDF file using the form above. Our tool will process the file and convert it to a
            searchable PDF format, which you can then download. The process is simple and quick, ensuring you get your
            converted document in no time.
          </p>
        </section>

        <section class="mt-10">
          <h3 class="text-2xl font-bold mb-4">FAQs</h3>
          <div class="text-left mx-auto max-w-lg">
            <h4 class="font-semibold">Is the conversion free?</h4>
            <p>Yes, our PDF to searchable PDF converter is completely free to use.</p>
            <h4 class="font-semibold mt-4">What is a searchable PDF?</h4>
            <p>A searchable PDF is a PDF document that has been processed with OCR to allow text search and selection.
            </p>
          </div>
        </section>
      </main>
    </div>
  </GuestLayout>
</template>