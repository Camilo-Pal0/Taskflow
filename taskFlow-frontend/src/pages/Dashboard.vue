<template>
    <Navbar>
        <div class="min-h-screen bg-gray-50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
              <!-- Header -->
              <div class="mb-6 lg:mb-8">
                  <div class="flex items-center justify-between">
                    <div>
    <!--                     <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Dashboard</h1> -->
                        <p class="text-sm text-gray-600 mt-1">Bienvenido de nuevo, {{ authStore.user?.name }}</p>
                    </div>
                    <button 
                        @click="showCreateModal = true"
                        class="hidden sm:inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 shadow-sm cursor-pointer"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Proyecto
                    </button>
                  </div>
              </div>

              <!-- Stats Overview -->
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6 lg:mb-8">
                  <!-- Total Projects -->
                  <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                  <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                      </svg>
                      </div>
                      <div class="min-w-0 flex-1">
                      <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Proyectos</p>
                      <p class="text-2xl font-semibold text-gray-900">{{ stats.totalProjects }}</p>
                      </div>
                  </div>
                  </div>

                  <!-- Total Tasks -->
                  <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                  <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                      <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                      </div>
                      <div class="min-w-0 flex-1">
                      <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Tareas</p>
                      <p class="text-2xl font-semibold text-gray-900">{{ stats.totalTasks }}</p>
                      </div>
                  </div>
                  </div>

                  <!-- Completed Tasks -->
                  <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                  <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      </div>
                      <div class="min-w-0 flex-1">
                      <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Completadas</p>
                      <div class="flex items-baseline gap-2">
                          <p class="text-2xl font-semibold text-green-600">{{ stats.completedTasks }}</p>
                          <span class="text-xs text-gray-500">{{ stats.completionRate }}%</span>
                      </div>
                      </div>
                  </div>
                  </div>

                  <!-- Overdue Tasks -->
                  <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                  <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                      <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                      </div>
                      <div class="min-w-0 flex-1">
                      <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Vencidas</p>
                      <p class="text-2xl font-semibold text-red-600">{{ stats.overdueTasks }}</p>
                      </div>
                  </div>
                  </div>
              </div>

              <!-- Charts and Activity Row -->
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                  <!-- Tasks Chart - 2 columns -->
                  <div class="lg:col-span-2">
                  <TasksChart :data="chartData" />
                  </div>
                  
                  <!-- Recent Activity - 1 column -->
                  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                  <div class="px-4 py-3.5 border-b border-gray-200 bg-gray-50">
                      <h3 class="text-sm font-semibold text-gray-900">Actividad Reciente</h3>
                  </div>
                  <div class="divide-y divide-gray-100">
                      <div 
                      v-for="activity in recentActivity" 
                      :key="activity.id"
                      class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                      >
                      <div class="flex items-start gap-3">
                          <div :class="[
                          'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0',
                          activity.status === 'done' ? 'bg-green-100' : activity.status === 'in_progress' ? 'bg-blue-100' : 'bg-gray-100'
                          ]">
                          <svg v-if="activity.status === 'done'" class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                          <svg v-else-if="activity.status === 'in_progress'" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                          </svg>
                          <svg v-else class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                          </svg>
                          </div>
                          <div class="flex-1 min-w-0">
                          <p class="text-sm text-gray-900 font-medium truncate">{{ activity.title }}</p>
                          <p class="text-xs text-gray-500 mt-0.5">{{ activity.statusText }} • {{ activity.time }}</p>
                          </div>
                      </div>
                      </div>
                      <div v-if="recentActivity.length === 0" class="px-4 py-8 text-center">
                      <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <p class="text-sm text-gray-500">No hay actividad reciente</p>
                      </div>
                  </div>
                  </div>
              </div>

              <!-- Projects Section -->
              <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <!-- Projects Header -->
                <div class="px-4 lg:px-6 py-4 border-b border-gray-200 bg-gray-50">
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                      <h2 class="text-lg font-semibold text-gray-900">Mis Proyectos</h2>
                      <p class="text-xs text-gray-500 mt-0.5">Vista rápida de tus proyectos recientes</p>
                    </div>
                    <button 
                      @click="showCreateModal = true"
                      class="sm:hidden inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      Nuevo Proyecto
                    </button>
                  </div>
                </div>

                <!-- Projects Content -->
                <div class="p-4 lg:p-6">
                  <div v-if="projectStore.loading" class="text-center py-12">
                    <div class="inline-block w-8 h-8 border-3 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
                    <p class="text-sm text-gray-500 mt-3">Cargando proyectos...</p>
                  </div>

                  <div v-else-if="projectStore.projects.length === 0" class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                      </svg>
                    </div>
                    <p class="text-gray-900 font-semibold mb-2">No hay proyectos aún</p>
                    <p class="text-sm text-gray-500 mb-6">Crea tu primer proyecto para comenzar a organizar tus tareas</p>
                    <button 
                      @click="showCreateModal = true"
                      class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      Crear Primer Proyecto
                    </button>
                  </div>

                  <div v-else>
                    <!-- Projects Grid (Max 3) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                      <ProjectCard 
                        v-for="project in limitedProjects" 
                        :key="project.id" 
                        :project="project"
                        @edit="handleEditProject"
                        @delete="handleDeleteProject"
                        @leave="handleLeaveProject"
                      />
                    </div>

                    <!-- View All Button -->
                    <div v-if="projectStore.projects.length > 3" class="text-center pt-4 flex ">
                      <router-link 
                        to="/projects"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 shadow-sm cursor-pointer"
                      >
                        Ver todos los proyectos
                        <span class="ml-1.5">({{ projectStore.projects.length }})</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <!-- Create/Edit Project Modal -->
            <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            >
            <div v-if="showCreateModal" @click.self="showCreateModal = false; editingProject = null" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
                >
                <div v-if="showCreateModal" class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ editingProject ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
                    </h2>
                    <button 
                        @click="showCreateModal = false; editingProject = null"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <form @submit.prevent="handleCreateProject" class="p-6 space-y-5">
                      <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">
                          Nombre del Proyecto
                          </label>
                          <input 
                          v-model="newProject.name" 
                          type="text" 
                          required
                          class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                          placeholder="Ej: Rediseño de la web"
                          autofocus
                          >
                      </div>

                      <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                          </label>
                          <textarea 
                          v-model="newProject.description" 
                          rows="4"
                          class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition"
                          placeholder="Describe de qué trata este proyecto..."
                          ></textarea>
                      </div>

                      <div v-if="error" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                          <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                          </svg>
                          <span>{{ error }}</span>
                      </div>

                      <!-- Modal Footer -->
                      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 ">
                        <!-- Cancelar -->
                        <button 
                          type="button" 
                          @click="showCreateModal = false; editingProject = null"
                          class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
                        >
                          Cancelar
                        </button>

                        <!-- Guardar/Actualizar -->
                        <button 
                          type="submit"
                          :disabled="loading"
                          class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                          <span v-if="loading" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                          </span>
                          <template v-else>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ editingProject ? 'Actualizar' : 'Crear Proyecto' }}
                          </template>
                        </button>
                      </div>
                    </form>
                </div>
                </transition>
            </div>
            </transition>
        </div>    
    </Navbar>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useProjectStore } from '@/stores/projectStore'
import { useAuthStore } from '@/stores/authStore'
import Navbar from '@/components/common/Navbar.vue'
import ProjectCard from '@/components/projects/ProjectCard.vue'
import TasksChart from '@/components/dashboard/TasksChart.vue'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'

const projectStore = useProjectStore()
const authStore = useAuthStore()
const toast = useToast()
const { confirm } = useConfirm()

const showCreateModal = ref(false)
const newProject = ref({ name: '', description: '' })
const editingProject = ref(null)
const error = ref('')
const loading = ref(false)
const searchQuery = ref('')
const sortBy = ref('recent')

const filteredProjects = computed(() => {
  let projects = [...projectStore.projects]
  
  if (searchQuery.value) {
    projects = projects.filter(p => 
      p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      p.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (sortBy.value === 'name') {
    projects.sort((a, b) => a.name.localeCompare(b.name))
  } else if (sortBy.value === 'tasks') {
    projects.sort((a, b) => (b.tasks?.length || 0) - (a.tasks?.length || 0))
  } else {
    projects.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
  }
  
  return projects
})

const stats = computed(() => {
  const projects = projectStore.projects
  const allTasks = projects.flatMap(p => p.tasks || [])
  
  const totalTasks = allTasks.length
  const completedTasks = allTasks.filter(t => t.status === 'done').length
  const completionRate = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0
  
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  const overdueTasks = allTasks.filter(t => {
    if (!t.due_date || t.status === 'done') return false
    const due = new Date(t.due_date)
    due.setHours(0, 0, 0, 0)
    return due < today
  }).length
  
  return {
    totalProjects: projects.length,
    totalTasks,
    completedTasks,
    completionRate,
    overdueTasks
  }
})

const chartData = computed(() => {
  const projects = projectStore.projects
  const allTasks = projects.flatMap(p => p.tasks || [])
  
  const myTasks = allTasks.filter(t => t.user_id === authStore.user?.id)
  
  return {
    todo: myTasks.filter(t => t.status === 'todo').length,
    in_progress: myTasks.filter(t => t.status === 'in_progress').length,
    done: myTasks.filter(t => t.status === 'done').length
  }
})

const recentActivity = computed(() => {
  const projects = projectStore.projects
  const allTasks = projects.flatMap(p => p.tasks || [])
  
  return allTasks
    .sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
    .slice(0, 5)
    .map(task => ({
      id: task.id,
      title: task.title,
      status: task.status,
      statusText: task.status === 'done' ? 'Completada' : task.status === 'in_progress' ? 'En progreso' : 'Por hacer',
      time: formatTimeAgo(task.updated_at)
    }))
})

const limitedProjects = computed(() => {
  // Si estás en la vista del dashboard, limita a 3
  return filteredProjects.value.slice(0, 3)
})

const formatTimeAgo = (date) => {
  const now = new Date()
  const past = new Date(date)
  const diffMs = now - past
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMs / 3600000)
  const diffDays = Math.floor(diffMs / 86400000)
  
  if (diffMins < 1) return 'Ahora'
  if (diffMins < 60) return `Hace ${diffMins}m`
  if (diffHours < 24) return `Hace ${diffHours}h`
  return `Hace ${diffDays}d`
}

onMounted(async () => {
  await projectStore.fetchProjects()
})

const handleCreateProject = async () => {
  loading.value = true
  error.value = ''
  
  try {
    if (editingProject.value) {
      await projectStore.updateProject(
        editingProject.value.id, 
        newProject.value.name, 
        newProject.value.description
      )
      toast.success('El proyecto ha sido actualizado')
      editingProject.value = null
    } else {
      await projectStore.createProject(newProject.value.name, newProject.value.description)
      toast.success('Se creó el proyecto correctamente')
    }
    
    showCreateModal.value = false
    newProject.value = { name: '', description: '' }
  } catch (err) {
    error.value = err.message || 'Error al guardar el proyecto'
    toast.error('Failed to save project')
  } finally {
    loading.value = false
  }
}

const handleEditProject = (project) => {
  editingProject.value = { ...project }
  newProject.value = { name: project.name, description: project.description }
  showCreateModal.value = true
}

const handleDeleteProject = async (projectId) => {
  const confirmed = await confirm({
    title: '¿Eliminar proyecto?',
    message: 'Esta acción no se puede deshacer. Se eliminarán todas las tareas asociadas.',
    confirmText: 'Eliminar',
    cancelText: 'Cancelar',
    variant: 'danger'
  })
  
  if (confirmed) {
    try {
      await projectStore.deleteProject(projectId)
      toast.success('El proyecto ha sido eliminado')
    } catch (error) {
      toast.error('Error al eliminar el proyecto')
    }
  }
}

const handleLeaveProject = async (projectId) => {
  const confirmed = await confirm({
    title: '¿Abandonar proyecto?',
    message: 'Ya no tendrás acceso a este proyecto ni a sus tareas.',
    confirmText: 'Abandonar',
    cancelText: 'Cancelar',
    variant: 'warning'
  })

  if (confirmed) {
    try {
      await projectStore.leaveProject(projectId)
      await projectStore.fetchProjects()
      toast.success('Has abandonado el proyecto')
    } catch (error) {
      toast.error(error.message || 'Error al abandonar el proyecto')
    }
  }
}
</script>