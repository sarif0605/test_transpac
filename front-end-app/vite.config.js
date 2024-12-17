import { fileURLToPath, URL } from "node:url";

import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";

export default ({ mode }) => {
  process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };
  return defineConfig({
    plugins: [vue()],
    server: {
      proxy: {
        "/api": {
          target: "http://127.0.0.1:8000", // Ganti localhost dengan 127.0.0.1
          changeOrigin: true,
          secure: false, // Jika tidak menggunakan HTTPS
        },
      },      
    },
    resolve: {
      alias: {
        "@": fileURLToPath(new URL("./src", import.meta.url)),
      },
    },
  });
};
