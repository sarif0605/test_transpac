<template>
  <div class="flex flex-col items-center justify-center h-screen bg-base-100">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-primary">404</h1>
      <h2 class="text-2xl font-semibold mt-4">Page Not Found</h2>
      <p class="mt-2 text-lg text-gray-600">
        We're sorry, but the page you were looking for doesn't exist.
      </p>
      <div class="mt-6">
          <RouterLink to="/" class="btn btn-primary">Go to Home</RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/AuthStore";

const authStore = useAuthStore();
</script>
