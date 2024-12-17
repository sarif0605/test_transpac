<template>
  <div class="hero bg-current min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-md lg:max-w-lg shadow-2xl">
      <form @submit.prevent="handleLogin" class="card-body">
        <!-- Loading Overlay -->
        <div
          class="absolute top-0 left-0 w-full h-full flex justify-center items-center z-50"
          v-if="authStore.isLoading"
          style="background-color: rgba(0, 0, 0, 0.5)"
        >
          <Loading />
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <!-- Error Messages -->
        <div role="alert" class="alert alert-error" v-if="authStore.isErr">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <ul>
            <li
              v-for="(msg, index) in authStore.errMsg.general"
              :key="'general-' + index"
            >
              {{ msg }}
            </li>
            <li
              v-for="(errors, field) in authStore.errMsg"
              v-if="field !== 'general'"
              :key="field"
            >
              <ul>
                <li v-for="(msg, index) in errors" :key="index">{{ msg }}</li>
              </ul>
            </li>
          </ul>
        </div>

        <!-- Email Field -->
        <div class="form-control">
          <label class="label">
            <span class="label-text">Email</span>
          </label>
          <input
            type="email"
            placeholder="Enter your email"
            v-model="userInput.email"
            class="input input-bordered"
            required
          />
        </div>

        <!-- Password Field -->
        <div class="form-control">
          <label class="label">
            <span class="label-text">Password</span>
          </label>
          <input
            type="password"
            placeholder="Enter your password"
            v-model="userInput.password"
            class="input input-bordered"
            required
          />
        </div>

        <!-- Submit Button -->
        <div class="form-control mt-4">
          <button class="btn btn-primary w-full">Login</button>
        </div>

        <!-- Google Auth Button -->
        <div class="form-control mt-4">
          <button
            @click="authStore.loginWithGoogle"
            type="button"
            class="btn btn-outline w-full flex items-center justify-center"
          >
            <img
              src="https://developers.google.com/identity/images/g-logo.png"
              alt="Google Logo"
              class="w-5 h-5 mr-2"
            />
            Login with Google
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import Loading from "@/components/Loading.vue";
import { useAuthStore } from "@/stores/AuthStore";
import { reactive } from "vue";

// Reactive States
const authStore = useAuthStore();
const userInput = reactive({
  email: "",
  password: "",
});

// Handle Login
const handleLogin = () => {
  authStore.loginUser(userInput);
};
</script>
