<template>
  <div
    class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans"
  >
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <div class="mx-auto mb-4">
          <img src="@/assets/logo.svg" alt="Logo" class="h-32 w-auto mx-auto" />
        </div>
        <p class="text-gray-600">Choisir un nouveau mot de passe</p>
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
                Nouveau mot de passe
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

            <div>
              <label
                for="password_confirmation"
                class="block text-sm font-medium text-gray-700 mb-2"
              >
                Confirmer le mot de passe
              </label>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                name="password_confirmation"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors duration-200 text-gray-900 placeholder-gray-500 focus:outline-none focus:border-primary-green focus:ring-2 focus:ring-primary-green/20"
                placeholder="••••••••"
              />
            </div>
          </div>

          <div
            v-if="error"
            class="bg-red-50 border border-red-200 rounded-lg p-3"
          >
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <div
            v-if="success"
            class="bg-green-50 border border-green-200 rounded-lg p-4"
          >
            <div class="flex">
              <div class="flex-shrink-0">
                <svg
                  class="h-5 w-5 text-green-400"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">
                  Mot de passe mis à jour !
                </h3>
                <div class="mt-2 text-sm text-green-700">
                  <p>Votre mot de passe a été changé avec succès.</p>
                </div>
              </div>
            </div>
          </div>

          <button
            v-if="!success"
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
              Mise à jour...
            </span>
            <span v-else>Mettre à jour le mot de passe</span>
          </button>
        </form>

        <div class="mt-6 text-center">
          <router-link
            v-if="success"
            to="/login"
            class="text-sm font-medium text-primary-green hover:text-primary-green-dark hover:underline"
          >
            → Se connecter avec le nouveau mot de passe
          </router-link>
          <router-link
            v-else
            to="/password/forgot"
            class="text-sm font-medium text-primary-green hover:text-primary-green-dark hover:underline"
          >
            ← Demander un nouveau lien
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const route = useRoute();
const authStore = useAuthStore();
const error = ref("");
const success = ref(false);

const form = reactive({
  email: route.query.email || "",
  password: "",
  password_confirmation: "",
  token: route.query.token || "",
});

const handleSubmit = async () => {
  if (form.password !== form.password_confirmation) {
    error.value = "Les mots de passe ne correspondent pas.";
    return;
  }

  if (!form.token) {
    error.value = "Token de réinitialisation manquant.";
    return;
  }

  error.value = "";

  try {
    const result = await authStore.resetPassword({
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
      token: form.token,
    });

    if (result.success) {
      success.value = true;
    } else {
      error.value = result.message;
    }
  } catch (err) {
    error.value = "Erreur lors de la mise à jour. Veuillez réessayer.";
  }
};

// Vérification des paramètres au montage
onMounted(() => {
  if (!route.query.token) {
    error.value =
      "Lien de réinitialisation invalide. Veuillez demander un nouveau lien.";
  }
});
</script>
