import { defineStore } from "pinia";

export const useThemeStore = defineStore("theme", {
  state: () => ({
    drawer: false,
    currentTheme: "light",
  }),
  actions: {
    toggleTheme() {
      this.currentTheme = this.currentTheme === "light" ? "forest" : "light";
      document.documentElement.setAttribute("data-theme", this.currentTheme);
    },
    setTheme(theme) {
      this.currentTheme = theme;
      document.documentElement.setAttribute("data-theme", theme);
    },
  },
});
