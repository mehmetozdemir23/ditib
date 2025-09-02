import { defineStore } from "pinia";
import axios from "@/plugins/axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    isAuthenticated: false,
    loading: false,
  }),

  actions: {
    async getCsrfToken() {
      await axios.get("/sanctum/csrf-cookie");
    },

    async login(credentials) {
      this.loading = true;
      try {
        await this.getCsrfToken();
        await axios.post("/login", credentials);

        return { success: true };
      } catch (error) {
        console.error("Erreur de connexion:", error);
        return {
          success: false,
          message: error.response?.data?.message || "Erreur de connexion",
        };
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post("/logout");
      } catch (error) {
        console.error("Erreur lors de la déconnexion:", error);
      } finally {
        this.user = null;
        this.isAuthenticated = false;
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get("/api/user");
        this.user = response.data;
        this.isAuthenticated = true;
      } catch (error) {
        this.user = null;
        this.isAuthenticated = false;
        throw error;
      }
    },

    async forgotPassword(email) {
      this.loading = true;
      try {
        await this.getCsrfToken();
        await axios.post("/password/forgot", { email });
        return { success: true };
      } catch (error) {
        return {
          success: false,
          message: error.response?.data?.message || "Erreur lors de l'envoi",
        };
      } finally {
        this.loading = false;
      }
    },
  },
});
