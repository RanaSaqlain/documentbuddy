<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { appName } from '@/constants.js';


defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <Head title="Welcome to {{ appName }}" />
  <div class="bg-gray-100">
    <div class="relative flex min-h-screen flex-col items-center justify-center">
      <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
          <div class="flex lg:col-start-2 lg:justify-center">
            <span class="text-3xl font-bold text-blue-600">{{ appName }}</span>
          </div>
          <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end text-black">
            <Link
              v-if="$page.props.auth.user"
              :href="route('dashboard')"
              class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
              Dashboard
            </Link>

            <template v-else>
              <Link
                :href="route('login')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Log in
              </Link>

              <Link
                v-if="canRegister"
                :href="route('register')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Register
              </Link>
            </template>
          </nav>
        </header>
        <main class="text-center">
          <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to {{ appName }}</h1>
          <p class="text-lg text-gray-600 mb-8">
            {{ appName }} is a website tool that offers a multitude of features for managing documents all in one place. The best part? It's completely free!
          </p>
          <img src="/images/document-buddy.png" alt="{{ appName }}" class="mx-auto mb-8" onerror="handleImageError()" />
          <div class="flex justify-center space-x-4">
            <!-- <Link
              :href="route('features')"
              class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700 transition"
            >
              Explore Features
            </Link>
            <Link
              :href="route('contact')"
              class="bg-gray-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-700 transition"
            >
              Contact Us
            </Link> -->
          </div>
        </main>
        <footer class="mt-10 text-center text-gray-500">
          <p>&copy; 2025 {{ appName }}. All rights reserved.</p>
        </footer>
      </div>
    </div>
  </div>
</template>
