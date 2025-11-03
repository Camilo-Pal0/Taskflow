<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 p-4 relative overflow-hidden">
    <!-- Ilustraciones decorativas en la parte inferior -->
    <div class="absolute bottom-0 left-0 w-full pointer-events-none">
      <div class="max-w-7xl mx-auto px-8 pb-8 flex justify-between items-end">
        <!-- Ilustración izquierda -->
        <div class="hidden lg:block w-80 xl:w-100 absolute left-10 bottom-0">
          <img :src="ilustracion1" alt="" class="w-full h-auto">
        </div>
        
        <!-- Ilustración derecha -->
        <div class="hidden lg:block w-80 xl:w-100 absolute right-10 bottom-0">
          <img :src="ilustracion2" alt="" class="w-full h-auto">
        </div>
      </div>
    </div>

    <!-- Card principal -->
    <div class="max-w-md w-full relative z-10">

      <!-- Card de registro -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-semibold text-gray-900 text-center mb-2">
          Crea tu cuenta
        </h2>
        <p class="text-center text-sm text-gray-600 mb-6">
          Únete a TaskFlow y aumenta tu productividad
        </p>
        
        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Name Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
            <input 
              v-model="name" 
              type="text" 
              required
              class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="Juan Pérez"
            >
          </div>

          <!-- Email Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico</label>
            <input 
              v-model="email" 
              type="email" 
              required
              class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="tu@ejemplo.com"
            >
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
            <input 
              v-model="password" 
              type="password" 
              required
              minlength="8"
              class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="Mínimo 8 caracteres"
              @input="checkPasswordStrength"
            >
            
            <!-- Password Strength Indicator -->
            <div v-if="password" class="mt-2">
              <div class="flex gap-1 mb-1.5">
                <div 
                  v-for="i in 4" 
                  :key="i"
                  class="h-1 flex-1 rounded-full transition-all duration-300"
                  :class="i <= passwordStrength ? getStrengthColor() : 'bg-gray-200'"
                ></div>
              </div>
              <p class="text-xs font-medium" :class="getStrengthTextColor()">
                {{ getStrengthText() }}
              </p>
            </div>
          </div>

          <!-- Confirm Password Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar contraseña</label>
            <div class="relative">
              <input 
                v-model="passwordConfirmation" 
                type="password" 
                required
                minlength="8"
                class="w-full px-3 py-2.5 text-sm border rounded-lg outline-0 focus:ring-2 focus:ring-blue-500 transition pr-10"
                :class="getPasswordConfirmClass()"
                placeholder="Repite tu contraseña"
              >
              <!-- Match Indicator -->
              <div v-if="passwordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg v-if="password === passwordConfirmation" class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <svg v-else class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Terms Checkbox -->
          <div class="flex items-start pt-2">
            <input 
              v-model="acceptTerms"
              type="checkbox" 
              required
              id="terms"
              class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            >
            <label for="terms" class="ml-2 text-xs text-gray-600">
              Acepto los 
              <a href="#" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">Términos de Servicio</a> 
              y la 
              <a href="#" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">Política de Privacidad</a>
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
            :disabled="loading || !acceptTerms"
            class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm text-sm"
          >
            <span v-if="!loading">Crear Cuenta</span>
            <span v-else class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Creando cuenta...
            </span>
          </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
          <span class="text-sm text-gray-600">¿Ya tienes una cuenta? </span>
          <router-link to="/login" class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline">
            Inicia sesión
          </router-link>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">
          Al registrarte, aceptas nuestros 
          <a href="#" class="text-gray-700 hover:underline">Términos</a> y 
          <a href="#" class="text-gray-700 hover:underline">Política de Privacidad</a>
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

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const acceptTerms = ref(false)
const error = ref('')
const loading = ref(false)
const passwordStrength = ref(0)

const checkPasswordStrength = () => {
  const pwd = password.value
  let strength = 0
  
  if (pwd.length >= 8) strength++
  if (pwd.length >= 12) strength++
  if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) strength++
  if (/[0-9]/.test(pwd)) strength++
  if (/[^a-zA-Z0-9]/.test(pwd)) strength++
  
  passwordStrength.value = Math.min(strength, 4)
}

const getStrengthColor = () => {
  const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500']
  return colors[passwordStrength.value - 1] || 'bg-gray-200'
}

const getStrengthTextColor = () => {
  const colors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-green-600']
  return colors[passwordStrength.value - 1] || 'text-gray-500'
}

const getStrengthText = () => {
  const texts = ['Débil', 'Regular', 'Buena', 'Fuerte']
  return texts[passwordStrength.value - 1] || 'Muy débil'
}

const getPasswordConfirmClass = () => {
  if (!passwordConfirmation.value) return 'border-gray-300'
  return password.value === passwordConfirmation.value 
    ? 'border-green-300 focus:border-green-500 focus:ring-green-500' 
    : 'border-red-300 focus:border-red-500 focus:ring-red-500'
}

const handleRegister = async () => {
  if (password.value !== passwordConfirmation.value) {
    error.value = 'Las contraseñas no coinciden'
    return
  }

  if (!acceptTerms.value) {
    error.value = 'Debes aceptar los términos y condiciones'
    return
  }

  loading.value = true
  error.value = ''
  
  try {
    await authStore.register(name.value, email.value, password.value, passwordConfirmation.value)
    router.push('/dashboard')
  } catch (err) {
    error.value = err.message || 'Error al registrarse'
  } finally {
    loading.value = false
  }
}
</script>