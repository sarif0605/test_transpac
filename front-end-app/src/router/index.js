import NotFound from "@/components/NotFound.vue";
import AdminLayout from "@/components/AdminLayout.vue";
import { createRouter, createWebHistory } from "vue-router";
import LoginView from "@/views/Auth/LoginView.vue";
import WorkUnitView from "@/views/Owner/WorkUnit/WorkUnitView.vue";
import EmployeeView from "@/views/Owner/Employee/EmployeeView.vue";
import { useAuthStore } from "@/stores/AuthStore";
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: LoginView,
      name: "/",
      meta: { isAuthTrue: true },
    },
    {
      path: "/admin",
      component: AdminLayout,
      meta: { isAuth: true },
      children: [
        {
          path: "/work-unit",
          name: "WorkUnit",
          component: WorkUnitView,
        },
        {
          path: "/employee",
          name: "Employee",
          component: EmployeeView,
        },
      ],
    },
    {
      path: "/login",
      name: "Login",
      component: LoginView,
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: NotFound,
    },
  ],
});

router.beforeEach(async (to, from) => {
  const authStore = await useAuthStore();
  if (to.meta.isAuthTrue && authStore.userToken) {
    return { name: "WorkUnit" };
  }
  if (to.meta.isAuth && !authStore.userToken) {
    return { path: "/" };
  }
});

export default router;
