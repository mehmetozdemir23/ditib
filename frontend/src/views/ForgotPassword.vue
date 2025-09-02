<template>
  <div
    class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans"
  >
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <div class="mx-auto mb-4">
          <img src="@/assets/logo.svg" alt="Logo" class="h-32 w-auto mx-auto" />
        </div>
        <p class="text-gray-600">Réinitialiser votre mot de passe</p>
      </div>

      <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <form @submit.prevent="handleSubmit" class="space-y-6">
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

          <div
            v-if="error"
            class="bg-red-50 border border-red-200 rounded-lg p-3"
          >
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <div v-if="!emailSent">
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
                Envoi en cours...
              </span>
              <span v-else>Envoyer le lien de réinitialisation</span>
            </button>
          </div>

          <div
            v-if="emailSent"
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
                  Email envoyé !
                </h3>
                <div class="mt-2 text-sm text-green-700">
                  <p>
                    Un lien de réinitialisation a été envoyé à
                    <span class="font-medium">{{ form.email }}</span
                    >. Vérifiez votre boîte de réception et vos spams.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="mt-6 text-center">
          <router-link
            to="/login"
            class="text-sm font-medium text-primary-green hover:text-primary-green-dark hover:underline"
          >
            ← Retour à la connexion
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
const emailSent = ref(false);
const error = ref("");

const form = reactive({
  email: "",
});

const handleSubmit = async () => {
  error.value = "";

  try {
    const result = await authStore.forgotPassword(form.email);

    if (result.success) {
      emailSent.value = true;
    } else {
      error.value = result.message;
    }
  } catch (err) {
    error.value = "Erreur lors de l'envoi. Veuillez réessayer.";
  }
};

// Gérer les erreurs de token en query params
onMounted(() => {
  const errorParam = route.query.error;
  if (errorParam === "invalid_token") {
    error.value =
      "Le lien de réinitialisation est invalide. Veuillez en demander un nouveau.";
  } else if (errorParam === "token_expired") {
    error.value =
      "Le lien de réinitialisation a expiré. Veuillez en demander un nouveau.";
  }
});
</script>
