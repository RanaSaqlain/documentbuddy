<script setup>
import { Head, Link } from "@inertiajs/vue3";

</script>

<template>
  <Head title="PDF to DOC Converter" />
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
        Easily convert your PDF documents to DOC format with our free tool.
      </p>
      <img src="/images/pdf-to-doc.png" alt="PDF to DOC" class="mx-auto mb-8" onerror="handleImageError()" />
      <div class="flex justify-center space-x-4">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700 transition">
          Upload PDF
        </button>
        <button class="bg-gray-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-700 transition">
          Learn More
        </button>
      </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 text-center">
      <p>&copy; 2025 Document Buddy. All rights reserved.</p>
    </footer>
  </div>
</template>