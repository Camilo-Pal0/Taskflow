<template>
    <NavBar>
        <div class="min-h-screen bg-gray-50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                <!-- Header -->
                <div class="mb-6 lg:mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Proyectos</h1>
                            <p class="text-sm text-gray-600 mt-1">Gestiona todos tus proyectos</p>
                        </div>
                        <button @click="showCreateModal = true"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Proyecto
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 lg:mb-8">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Total</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ projectStore.projects.length }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Propietario
                                </p>
                                <p class="text-2xl font-semibold text-purple-600">{{ ownerProjectsCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Miembro</p>
                                <p class="text-2xl font-semibold text-green-600">{{ memberProjectsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input v-model="searchQuery" type="text" placeholder="Buscar proyectos..."
                                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div class="relative">
                            <select v-model="filterRole"
                                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer">
                                <option value="">Todos los roles</option>
                                <option value="owner">Propietario</option>
                                <option value="member">Miembro</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div class="relative">
                            <select v-model="sortBy"
                                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer">
                                <option value="recent">Más Recientes</option>
                                <option value="name">Nombre A-Z</option>
                                <option value="tasks">Más Tareas</option>
                                <option value="progress">Mayor Progreso</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="projectStore.loading"
                    class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
                    <div
                        class="inline-block w-8 h-8 border-3 border-gray-200 border-t-blue-600 rounded-full animate-spin mb-3">
                    </div>
                    <p class="text-sm text-gray-500">Cargando proyectos...</p>
                </div>

                <!-- Empty State -->
                <div v-else-if="filteredProjects.length === 0"
                    class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-900 font-semibold mb-2">No hay proyectos</p>
                    <p class="text-sm text-gray-500 mb-6">{{ searchQuery || filterRole ? 'Intenta ajustar los filtros' :
                        'Crea tu primer proyecto para comenzar' }}</p>
                    <button @click="showCreateModal = true"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear mi primer proyecto
                    </button>
                </div>

                <!-- Projects Grid -->
                <div v-else>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <ProjectCard v-for="project in paginatedProjects" :key="project.id" :project="project"
                            @edit="handleEditProject" @delete="handleDeleteProject" @leave="handleLeaveProject" />
                    </div>

                    <!-- Pagination -->
                    <div v-if="totalPages > 1"
                        class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-3 shadow-sm">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button @click="currentPage--" :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                Anterior
                            </button>
                            <button @click="currentPage++" :disabled="currentPage === totalPages"
                                class="relative ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                Siguiente
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Mostrando <span class="font-medium">{{ startItem }}</span> a <span
                                        class="font-medium">{{ endItem }}</span> de <span class="font-medium">{{
                                        filteredProjects.length }}</span> proyectos
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    <button @click="currentPage--" :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <template v-for="page in visiblePages" :key="page">
                                        <button v-if="page !== '...'" @click="currentPage = page" :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition',
                                            currentPage === page
                                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                        ]">
                                            {{ page }}
                                        </button>
                                        <span v-else
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                            ...
                                        </span>
                                    </template>

                                    <button @click="currentPage++" :disabled="currentPage === totalPages"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Project Modal -->
            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showCreateModal" @click.self="showCreateModal = false; editingProject = null"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <transition enter-active-class="transition ease-out duration-200"
                        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <div v-if="showCreateModal"
                            class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ editingProject ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
                                </h2>
                                <button @click="showCreateModal = false; editingProject = null"
                                    class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form @submit.prevent="handleCreateProject" class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del
                                        Proyecto</label>
                                    <input v-model="newProject.name" type="text" required
                                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                                        placeholder="Mi Proyecto Increíble" autofocus>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                    <textarea v-model="newProject.description" rows="4"
                                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition"
                                        placeholder="Describe tu proyecto..."></textarea>
                                </div>

                                <div v-if="error"
                                    class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ error }}</span>
                                </div>

                                <!-- Modal Footer -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
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
    </NavBar>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useProjectStore } from '@/stores/projectStore'
import { useAuthStore } from '@/stores/authStore'
import ProjectCard from '@/components/projects/ProjectCard.vue'
import NavBar from '../components/common/Navbar.vue'
import { useConfirm } from '@/composables/useConfirm'
import { useToast } from '@/composables/useToast'

const { confirm } = useConfirm()
const toast = useToast()


const projectStore = useProjectStore()
const authStore = useAuthStore()

const showCreateModal = ref(false)
const newProject = ref({ name: '', description: '' })
const editingProject = ref(null)
const error = ref('')
const loading = ref(false)

const searchQuery = ref('')
const filterRole = ref('')
const sortBy = ref('recent')

//Variables de paginación
const currentPage = ref(1)
const itemsPerPage = 9

onMounted(async () => {
    await projectStore.fetchProjects()
})

// Reset page when filters change
watch([searchQuery, filterRole, sortBy], () => {
    currentPage.value = 1
})

const ownerProjectsCount = computed(() => {
    return projectStore.projects.filter(p => {
        const isCreator = p.user_id === authStore.user?.id
        const hasOwnerRole = p.members?.some(m =>
            m.id === authStore.user?.id && m.pivot?.role === 'owner'
        )
        return isCreator || hasOwnerRole
    }).length
})

const memberProjectsCount = computed(() => {
    return projectStore.projects.length - ownerProjectsCount.value
})

const filteredProjects = computed(() => {
    let projects = [...projectStore.projects]

    if (searchQuery.value) {
        projects = projects.filter(p =>
            p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            p.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }

    if (filterRole.value) {
        projects = projects.filter(p => {
            const isCreator = p.user_id === authStore.user?.id
            const hasOwnerRole = p.members?.some(m =>
                m.id === authStore.user?.id && m.pivot?.role === 'owner'
            )
            const isOwner = isCreator || hasOwnerRole

            return filterRole.value === 'owner' ? isOwner : !isOwner
        })
    }

    if (sortBy.value === 'name') {
        projects.sort((a, b) => a.name.localeCompare(b.name))
    } else if (sortBy.value === 'tasks') {
        projects.sort((a, b) => (b.tasks?.length || 0) - (a.tasks?.length || 0))
    } else if (sortBy.value === 'progress') {
        projects.sort((a, b) => {
            const getProgress = (p) => {
                const tasks = p.tasks || []
                if (tasks.length === 0) return 0
                const completed = tasks.filter(t => t.status === 'done').length
                return (completed / tasks.length) * 100
            }
            return getProgress(b) - getProgress(a)
        })
    } else {
        projects.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
    }

    return projects
})

const totalPages = computed(() => {
    return Math.ceil(filteredProjects.value.length / itemsPerPage)
})

const paginatedProjects = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return filteredProjects.value.slice(start, end)
})

const startItem = computed(() => {
    return (currentPage.value - 1) * itemsPerPage + 1
})

const endItem = computed(() => {
    return Math.min(currentPage.value * itemsPerPage, filteredProjects.value.length)
})

const visiblePages = computed(() => {
    const pages = []
    const total = totalPages.value
    const current = currentPage.value

    if (total <= 7) {
        for (let i = 1; i <= total; i++) {
            pages.push(i)
        }
    } else {
        if (current <= 3) {
            for (let i = 1; i <= 5; i++) pages.push(i)
            pages.push('...')
            pages.push(total)
        } else if (current >= total - 2) {
            pages.push(1)
            pages.push('...')
            for (let i = total - 4; i <= total; i++) pages.push(i)
        } else {
            pages.push(1)
            pages.push('...')
            for (let i = current - 1; i <= current + 1; i++) pages.push(i)
            pages.push('...')
            pages.push(total)
        }
    }

    return pages
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
            toast.success('Proyecto actualizado exitosamente')
            editingProject.value = null
        } else {
            await projectStore.createProject(newProject.value.name, newProject.value.description)
            toast.success('Proyecto creado exitosamente')
        }

        showCreateModal.value = false
        newProject.value = { name: '', description: '' }
    } catch (err) {
        error.value = err.message || 'Error al guardar el proyecto'
        toast.error(err.message || 'Error al guardar el proyecto')
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
            toast.success('Proyecto eliminado exitosamente')
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