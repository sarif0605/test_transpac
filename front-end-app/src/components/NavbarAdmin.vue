<template>
  <div class="navbar bg-primary fixed z-50">
    <div class="flex-none">
      <button
        class="btn btn-square btn-ghost"
        @click="themeStore.drawer = !themeStore.drawer"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          class="inline-block w-6 h-6 stroke-current"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
          ></path>
        </svg>
      </button>
    </div>
    <div class="flex-1">
      <a class="btn btn-ghost normal-case text-xl">PNS</a>
    </div>
    <div class="flex-none gap-2">
      <div class="form-control">
        <label class="swap swap-rotate">
          <input
            type="checkbox"
            class="theme-controller"
            value="synthwave"
            @change="toggleTheme"
            :checked="themeStore.currentTheme === 'light'"
          />
          <svg
            class="swap-off h-8 w-8 fill-current"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
          >
            <path
              class="bg-white"
              d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"
            />
          </svg>
          <svg
            class="swap-on h-8 w-8 fill-current"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
          >
            <path
              class="bg-white"
              d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"
            />
          </svg>
        </label>
      </div>
      <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img :src="profile" />
          </div>
        </label>
        <ul
          tabindex="0"
          class="mt-3 p-2 shadow menu menu-compact dropdown-content bg-base-100 rounded-box w-52"
        >
          <li><button @click="logoutUser = true">Logout</button></li>
        </ul>
      </div>
    </div>
    <Logout
      :visible="logoutUser"
      @update:visible="logoutUser = $event"
      @confirmLogout="confirmLogout"
    />
  </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/AuthStore";
import { ref, onMounted, watch } from "vue";
import Logout from "@/components/Logout.vue";
import { useThemeStore } from "@/stores/ThemeStore";
import profile from "@/assets/img/profile.png";
import { useRouter } from "vue-router";

const router = useRouter();
const authStore = useAuthStore();
const logoutUser = ref(false);

const handleLogout = () => {
  authStore.logoutUser();
  logoutUser.value = false;
  console.log("berhasil logout");
  router.push({ name: "Home" });
};

const confirmLogout = () => {
  handleLogout();
};

const themeStore = useThemeStore();
const toggleTheme = () => {
  themeStore.toggleTheme();
};

onMounted(() => {
  document.documentElement.setAttribute("data-theme", themeStore.currentTheme);
});

watch(
  () => themeStore.currentTheme,
  (newTheme) => {
    document.documentElement.setAttribute("data-theme", newTheme);
  }
);
</script>