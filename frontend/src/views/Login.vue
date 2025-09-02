<template>
  <div
    class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans"
  >
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <div class="mx-auto mb-4">
          <img src="@/assets/logo.svg" alt="Logo" class="h-32 w-auto mx-auto" />
        </div>
        <p class="text-gray-600">Connectez-vous à votre compte</p>
      </div>

      <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="space-y-4">
            <div>
              <label
                for="email"
                class="block text-sm font-medium text-gray-700 mb-2"
              >
                Adresse email
              </label>
              <input
                id="email"
                v-model="form.email"
                name="email"
                type="email"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors duration-200 text-gray-900 placeholder-gray-500 focus:outline-none focus:border-primary-green focus:ring-2 focus:ring-primary-green/20"
                placeholder="votre@email.com"
              />
            </div>

            <div>
              <label
                for="password"
                class="block text-sm font-medium text-gray-700 mb-2"
              >
                Mot de passe
              </label>
              <input
                id="password"
                v-model="form.password"
                name="password"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors duration-200 text-gray-900 placeholder-gray-500 focus:outline-none focus:border-primary-green focus:ring-2 focus:ring-primary-green/20"
                placeholder="••••••••"
              />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 text-primary-green focus:ring-primary-green border-gray-300 rounded"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Se souvenir de moi
              </label>
            </div>
            <router-link
              to="/password/forgot"
              class="text-sm font-medium text-primary-green hover:text-primary-green-dark hover:underline"
            >
              Mot de passe oublié ?
            </router-link>
          </div>

          <div
            v-if="error"
            class="bg-red-50 border border-red-200 rounded-lg p-3"
          >
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full bg-gradient-to-r from-primary-green to-primary-green-dark hover:from-primary-green-dark hover:to-primary-green text-white py-3 px-4 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] active:scale-[0.98]"
          >
            <span
              v-if="authStore.loading"
              class="flex items-center justify-center"
            >
              <svg
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Connexion en cours...
            </span>
            <span v-else>Se connecter</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();
const error = ref("");

const form = reactive({
  email: "",
  password: "",
  remember: false,
});

const handleSubmit = async () => {
  error.value = "";

  try {
    const result = await authStore.login({
      email: form.email,
      password: form.password,
      remember: form.remember,
    });

    if (result.success) {
      router.push("/");
    } else {
      error.value = result.message;
    }
  } catch (err) {
    error.value = "Erreur de connexion. Veuillez réessayer.";
  }
};
</script>
