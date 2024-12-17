import { customeApi } from "@/api";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toast-notification";

export const useAuthStore = defineStore("auth", () => {
  const router = useRouter();
  const isLoading = ref(false);
  const isErr = ref(false);
  const $toast = useToast();
  const errMsg = ref([]);
  const expirationTime = ref(
    localStorage.getItem("expirationTime")
      ? JSON.parse(localStorage.getItem("expirationTime"))
      : null
  );

  const userToken = ref(
    localStorage.getItem("token")
      ? JSON.parse(localStorage.getItem("token"))
      : null
  );

  const userData = ref(
    localStorage.getItem("user")
      ? JSON.parse(localStorage.getItem("user"))
      : null
  );

  // Fungsi untuk menghapus token dan data pengguna
  const clearAuthData = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    localStorage.removeItem("expirationTime");
    userToken.value = null;
    userData.value = null;
    expirationTime.value = null;
    $toast.warning("Sesi Anda telah berakhir. Silakan login kembali.", {
      position: "top-right",
    });
    router.push({ name: "Login" });
  };

  // Periksa token saat aplikasi dimuat
  const initializeAuth = () => {
    if (userToken.value && expirationTime.value) {
      const remainingTime = new Date(expirationTime.value).getTime() - Date.now();
      if (remainingTime <= 0) {
        clearAuthData();
      } else {
        setAutoLogout(remainingTime);
      }
    }
  };

  // Atur timeout untuk logout otomatis
  const setAutoLogout = (duration) => {
    setTimeout(() => {
      clearAuthData();
    }, duration);
  };

  const loginUser = async (inputData) => {
    isLoading.value = true;
    try {
      const { email, password } = inputData;
      const { data } = await customeApi.post("/auth/login", { email, password });
      const { token, user } = data;

      userToken.value = token;
      userData.value = user;

      // Set waktu kedaluwarsa
      const expirationDate = new Date(Date.now() + 60 * 60 * 1000); // 60 menit
      expirationTime.value = expirationDate;

      localStorage.setItem("token", JSON.stringify(token));
      localStorage.setItem("user", JSON.stringify(user));
      localStorage.setItem("expirationTime", JSON.stringify(expirationDate));

      // Atur logout otomatis
      setAutoLogout(60 * 60 * 1000);

      isLoading.value = false;
      $toast.success("Berhasil Melakukan Login!", { position: "top-right" });
      router.push({ name: "WorkUnit" });
    } catch (error) {
      isLoading.value = false;
      isErr.value = true;
      if (error.response && error.response.data.errors) {
        errMsg.value = error.response.data.errors;
      } else {
        errMsg.value = { general: [error.response.data.message] };
      }
    }
  };

  const logoutUser = async () => {
    await customeApi.post("/auth/logout", null, {
      headers: {
        Authorization: `Bearer ${userToken.value}`,
      },
    });
    clearAuthData();
    $toast.success("Berhasil Keluar Dari Sistem!", { position: "top-right" });
  };

  // Inisialisasi auth saat aplikasi dimuat
  initializeAuth();

  return {
    userToken,
    userData,
    isErr,
    errMsg,
    isLoading,
    loginUser,
    logoutUser,
  };
});
