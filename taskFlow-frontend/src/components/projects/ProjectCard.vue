<!-- Card del proyecto -->

<template>
  <div class="group bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all duration-200 overflow-hidden relative">
    <!-- Menu de opciones -->
    <div class="absolute top-3 right-3 z-10">
      <button 
        @click.stop="showMenu = !showMenu"
        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition lg:opacity-0 lg:group-hover:opacity-100 cursor-pointer"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
      </button>

      <!-- Dropdown Menu -->
      <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div 
          v-if="showMenu" 
          @click.stop
          class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-20"
        >
          <button 
            v-if="isOwner"
            @click.stop="handleEdit"
            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition cursor-pointer"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
          </button>
          <button 
            v-if="isOwner"
            @click.stop="handleDelete"
            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 transition border-t border-gray-100 cursor-pointer"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Eliminar
          </button>
          <button 
            @click.stop="handleLeave"
            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition border-t border-gray-100 cursor-pointer"
          >
            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Abandonar Proyecto
          </button>
        </div>
      </transition>
    </div>

    <!-- Card Content -->
    <router-link 
      :to="`/projects/${project.id}`" 
      class="block p-6"
    >
      <!-- Header with badge -->
      <div class="mb-3">
        <div class="flex items-center gap-2 mb-1 pr-8">
          <h3 class="text-lg font-semibold text-gray-900 truncate group-hover:text-blue-600 transition">
            {{ project.name }}
          </h3>
          <span 
            v-if="isOwner" 
            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0"
          >
            Propietario
          </span>
        </div>
      </div>

      <!-- Description -->
      <p class="text-sm text-gray-600 mb-4 line-clamp-2 min-h-[40px]">
        {{ project.description || 'Sin descripción' }}
      </p>

      <!-- Stats -->
      <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
        <div class="flex items-center text-sm text-gray-600">
          <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <span class="font-medium">{{ project.tasks?.length || 0 }}</span>
          <span class="ml-1 text-gray-500">{{ project.tasks?.length === 1 ? 'tarea' : 'tareas' }}</span>
        </div>

        <div class="flex items-center text-sm text-gray-600">
          <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="font-medium">{{ project.members?.length || 0 }}</span>
          <span class="ml-1 text-gray-500">{{ project.members?.length === 1 ? 'miembro' : 'miembros' }}</span>
        </div>
      </div>

      <!-- Progress bar (si hay tareas) -->
      <div v-if="project.tasks && project.tasks.length > 0" class="mt-4">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-xs font-medium text-gray-600">Progreso</span>
          <span class="text-xs font-semibold text-gray-900">{{ completionPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1.5">
          <div 
            class="h-1.5 rounded-full transition-all duration-500"
            :class="completionPercentage === 100 ? 'bg-green-500' : 'bg-blue-500'"
            :style="{ width: completionPercentage + '%' }"
          ></div>
        </div>
      </div>
    </router-link>

    <!-- Overlay para cerrar el menu al hacer click fuera -->
    <div 
      v-if="showMenu" 
      @click.stop="showMenu = false"
      class="fixed inset-0 z-0"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete', 'leave'])

const authStore = useAuthStore()
const showMenu = ref(false)

const isOwner = computed(() => {
  const user = authStore.user
  if (!user) return false
  
  // Es owner si es el creador O tiene rol 'owner' en members
  const isCreator = props.project.user_id === user.id
  const hasOwnerRole = props.project.members?.some(m => 
    m.id === user.id && m.pivot?.role === 'owner'
  )
  
  return isCreator || hasOwnerRole
})

const completionPercentage = computed(() => {
  const tasks = props.project.tasks || []
  if (tasks.length === 0) return 0
  
  const completedTasks = tasks.filter(task => task.status === 'done').length
  return Math.round((completedTasks / tasks.length) * 100)
})

const handleEdit = () => {
  showMenu.value = false
  emit('edit', props.project)
}

const handleDelete = () => {
  showMenu.value = false
  emit('delete', props.project.id)
}

const handleLeave = () => {
  showMenu.value = false
  emit('leave', props.project.id)
}
</script>