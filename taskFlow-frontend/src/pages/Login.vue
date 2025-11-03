<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 p-4 relative overflow-hidden">
    <!-- Ilustraciones decorativas en la parte inferior -->
    <div class="absolute bottom-0 left-0 w-full pointer-events-none">
      <div class="max-w-7xl mx-auto px-8 pb-8 flex justify-between items-end">
        <!-- Ilustración izquierda -->
        <div class="hidden lg:block w-80 xl:w-100 absolute left-10 bottom-0">
            <img :src=ilustracion1 alt="">
        </div>
        
        <!-- Ilustración derecha -->
        <div class="hidden lg:block w-90 xl:w-100 absolute right-10 bottom-0">
            <img :src=ilustracion2 alt="">
        </div>
      </div>
    </div>

    <!-- Card principal -->
    <div class="max-w-md w-full relative z-10">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl mb-4">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">TaskFlow</h1>
      </div>

      <!-- Card de login -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-semibold text-gray-900 text-center mb-6">
          Inicia sesión para continuar
        </h2>
        
        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Correo</label>
            <input 
              v-model="email" 
              type="email" 
              required
              class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="Introduce tu correo electrónico"
            >
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
            <input 
              v-model="password" 
              type="password" 
              required
              class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="Introduce tu contraseña"
            >
          </div>

          <!-- Remember me -->
          <div class="flex items-center">
            <input 
              id="remember" 
              type="checkbox" 
              class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            >
            <label for="remember" class="ml-2 text-sm text-gray-700">
              Recordarme
            </label>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span>{{ error }}</span>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm text-sm"
          >
            <span v-if="!loading">Continuar</span>
            <span v-else class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Iniciando sesión...
            </span>
          </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
          <router-link to="/register" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            ¿No puedes iniciar sesión?
          </router-link>
          <span class="text-gray-400 mx-2">•</span>
          <router-link to="/register" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            Crear una cuenta
          </router-link>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">
          Una cuenta para TaskFlow. 
          <a href="#" class="text-gray-700 hover:underline">Política de privacidad</a>
          <span class="mx-1">•</span>
          <a href="#" class="text-gray-700 hover:underline">Aviso de usuario</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import ilustracion1 from '@/assets/ilustracion1.svg'
import ilustracion2 from '@/assets/ilustracion2.svg'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await authStore.login(email.value, password.value)
    router.push('/dashboard')
  } catch (err) {
    error.value = err.message || 'Credenciales inválidas'
  } finally {
    loading.value = false
  }
}
</script>