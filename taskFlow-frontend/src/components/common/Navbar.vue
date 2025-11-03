<template>
  <div class="flex h-screen overflow-hidden bg-gray-50">
    <!-- Overlay para cerrar sidebar en móvil -->
    <div 
      v-if="isMobileMenuOpen"
      @click="isMobileMenuOpen = false"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside :class="[
      'fixed lg:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0',
      isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
    ]">
      <!-- Header con Logo -->
      <div class="h-14 flex items-center justify-between px-4 border-b border-gray-200 flex-shrink-0">
        <router-link to="/dashboard" class="flex items-center space-x-2 group">
          <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition">TaskFlow</span>
        </router-link>

        <!-- Botón cerrar en móvil -->
        <button 
          @click="isMobileMenuOpen = false"
          class="lg:hidden p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="flex-1 overflow-y-auto py-4 px-2">
        <div class="space-y-1">
          <router-link 
            to="/dashboard"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </router-link>

          <router-link 
            to="/tasks"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Tareas
          </router-link>

          <router-link 
            to="/projects"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            Proyectos
          </router-link>

          <router-link 
            to="/calendar"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Calendario
          </router-link>
        </div>

        <!-- Separador -->
        <div class="my-4 border-t border-gray-200"></div>

        <!-- Sección adicional -->
        <div class="space-y-1">
          <div class="px-2 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Workspace
          </div>
          
          <router-link 
            to="/team"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Equipo
          </router-link>

          <router-link 
            to="/settings"
            @click="closeMobileMenu"
            class="flex items-center px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition group"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Configuración
          </router-link>
        </div>
      </nav>

      <!-- User Section en la parte inferior -->
      <div class="border-t border-gray-200 p-3 flex-shrink-0">
        <div class="flex items-center justify-between">
          <router-link 
            to="/profile"
            @click="closeMobileMenu"
            class="flex items-center flex-1 min-w-0 hover:bg-gray-100 rounded-lg p-1.5 transition"
          >
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-xs font-medium text-white">
                {{ authStore.user?.name?.charAt(0).toUpperCase() || 'U' }}
              </span>
            </div>
            <div class="ml-2 flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">
                {{ authStore.user?.name || 'Usuario' }}
              </p>
            </div>
          </router-link>
          
          <div class="flex items-center space-x-1 ml-2">
            <NotificationBell />
            <button
              @click="handleLogout"
              class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition cursor-pointer"
              title="Cerrar sesión"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header para móvil con menú hamburguesa -->
      <div class="lg:hidden h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 flex-shrink-0">
        <button 
          @click="isMobileMenuOpen = true"
          class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        
        <router-link to="/dashboard" class="flex items-center space-x-2">
          <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <span class="text-sm font-semibold text-gray-900">TaskFlow</span>
        </router-link>

        <div class="w-10"></div> <!-- Spacer para centrar el logo -->
      </div>

      <!-- Contenido principal con scroll -->
      <main class="flex-1 overflow-y-auto bg-gray-50">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import NotificationBell from './NotificationBell.vue'

const router = useRouter()
const authStore = useAuthStore()
const isMobileMenuOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}
</script>