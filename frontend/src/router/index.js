import { createRouter, createWebHistory } from "vue-router";
import Login from "@/views/Login.vue";
import ForgotPassword from "@/views/ForgotPassword.vue";
import ResetPassword from "@/views/ResetPassword.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: Login,
    },
    {
      path: "/password/forgot",
      name: "password.forgot",
      component: ForgotPassword,
    },
    {
      path: "/password/reset",
      name: "password.reset",
      component: ResetPassword,
    },
  ],
});

export default router;
