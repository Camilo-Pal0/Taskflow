<template>
  <Navbar>
      <div class="min-h-screen bg-gray-50">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
          <!-- Header -->
          <div class="mb-6 lg:mb-8">
            <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Mi Perfil</h1>
            <p class="text-sm text-gray-600 mt-1">Administra tu información personal y seguridad</p>
          </div>
    
          <!-- Profile Avatar Section -->
          <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm">
            <div class="flex items-center gap-6">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-3xl font-bold text-white">
                  {{ authStore.user?.name?.charAt(0).toUpperCase() || 'U' }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-gray-900 truncate">{{ authStore.user?.name }}</h2>
                <p class="text-sm text-gray-600 truncate">{{ authStore.user?.email }}</p>
                <p class="text-xs text-gray-500 mt-1">Miembro desde {{ formatDate(authStore.user?.created_at) }}</p>
              </div>
            </div>
          </div>
    
          <!-- Personal Information -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 class="text-lg font-semibold text-gray-900">Información Personal</h2>
            </div>
            
            <form @submit.prevent="handleUpdateProfile" class="p-6 space-y-5">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                <input 
                  v-model="profile.name" 
                  type="text" 
                  required
                  class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Tu nombre completo"
                >
              </div>
    
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                  v-model="profile.email" 
                  type="email" 
                  required
                  class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="tu@email.com"
                >
              </div>
    
              <!-- Error Message -->
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
              >
                <div v-if="profileError" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ profileError }}</span>
                </div>
              </transition>
    
              <div class="pt-2">
                <button 
                  type="submit"
                  :disabled="profileLoading"
                  class="inline-flex items-center px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                >
                  <span v-if="profileLoading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Guardando...
                  </span>
                  <span v-else class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Cambios
                  </span>
                </button>
              </div>
            </form>
          </div>
    
          <!-- Change Password -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Cambiar Contraseña</h2>
              </div>
              <p class="text-sm text-gray-600 mt-1">Actualiza tu contraseña para mantener tu cuenta segura</p>
            </div>
            
            <form @submit.prevent="handleUpdatePassword" class="p-6 space-y-5">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                <div class="relative">
                  <input 
                    v-model="password.current" 
                    :type="showCurrentPassword ? 'text' : 'password'" 
                    required
                    class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Tu contraseña actual"
                  >
                  <button 
                    type="button"
                    @click="showCurrentPassword = !showCurrentPassword"
                    class="absolute right-3 top-1/2 transition transform ease-in-out duration-300 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                  >
                    <svg v-if="showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  </button>
                </div>
              </div>
    
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                <div class="relative">
                  <input 
                    v-model="password.new" 
                    :type="showNewPassword ? 'text' : 'password'" 
                    required
                    minlength="8"
                    class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Mínimo 8 caracteres"
                  >
                  <button 
                    type="button"
                    @click="showNewPassword = !showNewPassword"
                    class="absolute right-3 top-1/2 transition transform ease-in-out duration-300 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                  >
                    <svg v-if="showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  </button>
                </div>
              </div>
    
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                <div class="relative">
                  <input 
                    v-model="password.confirm" 
                    :type="showConfirmPassword ? 'text' : 'password'" 
                    required
                    minlength="8"
                    class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Confirma tu nueva contraseña"
                  >
                  <button 
                    type="button"
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute right-3 top-1/2 transition transform ease-in-out duration-300 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                  >
                    <svg v-if="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  </button>
                </div>
              </div>
    
              <!-- Password Strength Indicator -->
              <div v-if="password.new" class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div 
                      :class="passwordStrengthColor" 
                      class="h-full transition-all duration-300"
                      :style="{ width: passwordStrength + '%' }"
                    ></div>
                  </div>
                  <span class="text-xs font-medium text-gray-600">{{ passwordStrengthText }}</span>
                </div>
                <ul class="text-xs text-gray-600 space-y-1">
                  <li :class="password.new.length >= 8 ? 'text-green-600' : 'text-gray-500'" class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Mínimo 8 caracteres
                  </li>
                  <li :class="/[A-Z]/.test(password.new) ? 'text-green-600' : 'text-gray-500'" class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Una letra mayúscula
                  </li>
                  <li :class="/[0-9]/.test(password.new) ? 'text-green-600' : 'text-gray-500'" class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Un número
                  </li>
                </ul>
              </div>
    
              <!-- Error Message -->
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
              >
                <div v-if="passwordError" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ passwordError }}</span>
                </div>
              </transition>
    
              <div class="pt-2">
                <button 
                  type="submit"
                  :disabled="passwordLoading"
                  class="inline-flex items-center px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm cursor-pointer"
                >
                  <span v-if="passwordLoading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Actualizando...
                  </span>
                  <span v-else class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Actualizar Contraseña
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
    </Navbar>

</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import Navbar from '@/components/common/Navbar.vue'
import api from '@/services/api'
import { useToast } from '@/composables/useToast'

const authStore = useAuthStore()
const toast = useToast()

const profile = ref({ name: '', email: '' })
const profileError = ref('')
const profileSuccess = ref('')
const profileLoading = ref(false)

const password = ref({ current: '', new: '', confirm: '' })
const passwordError = ref('')
const passwordSuccess = ref('')
const passwordLoading = ref(false)

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

const passwordStrength = computed(() => {
  const pwd = password.value.new
  if (!pwd) return 0
  
  let strength = 0
  if (pwd.length >= 8) strength += 25
  if (pwd.length >= 12) strength += 25
  if (/[A-Z]/.test(pwd)) strength += 25
  if (/[0-9]/.test(pwd)) strength += 25
  
  return strength
})

const passwordStrengthColor = computed(() => {
  if (passwordStrength.value <= 25) return 'bg-red-500'
  if (passwordStrength.value <= 50) return 'bg-orange-500'
  if (passwordStrength.value <= 75) return 'bg-yellow-500'
  return 'bg-green-500'
})

const passwordStrengthText = computed(() => {
  if (passwordStrength.value <= 25) return 'Débil'
  if (passwordStrength.value <= 50) return 'Media'
  if (passwordStrength.value <= 75) return 'Buena'
  return 'Fuerte'
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long' })
}

onMounted(() => {
  profile.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || ''
  }
})

const handleUpdateProfile = async () => {
  profileLoading.value = true
  profileError.value = ''
  profileSuccess.value = ''
  
  try {
    const response = await api.patch('/user/profile', profile.value)
    authStore.user = response.data
    localStorage.setItem('user', JSON.stringify(response.data))

    toast.success('El perfil ha sido actualizado')

    setTimeout(() => {
      profileSuccess.value = ''
    }, 3000)
  } catch (err) {
    const message = err.response?.data?.message || 'Error al actualizar el perfil'
    toast.error(message)
    profileError.value = message
  } finally {
    profileLoading.value = false
  }
}

const handleUpdatePassword = async () => {
  if (password.value.new !== password.value.confirm) {
    const message = 'Las contraseñas no coinciden'
    toast.error(message)
    passwordError.value = message
    return
  }

  passwordLoading.value = true
  passwordError.value = ''
  passwordSuccess.value = ''
  
  try {
    await api.patch('/user/password', {
      current_password: password.value.current,
      password: password.value.new,
      password_confirmation: password.value.confirm
    })

    toast.success('La contraseña ha sido actualizada')
    password.value = { current: '', new: '', confirm: '' }

    setTimeout(() => {
      passwordSuccess.value = ''
    }, 3000)
  } catch (err) {
    const message = err.response?.data?.message || 'Error al actualizar la contraseña'
    toast.error(message)
    passwordError.value = message
  } finally {
    passwordLoading.value = false
  }
}

</script>