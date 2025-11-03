<template>
    <NavBar>
        <div class="min-h-screen bg-gray-50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                <!-- Header -->
                <div class="mb-6 lg:mb-8">
                    <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Mis Tareas</h1>
                    <p class="text-sm text-gray-600 mt-1">Todas las tareas asignadas a ti</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6 lg:mb-8">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Total</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.total }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Por Hacer
                                </p>
                                <p class="text-2xl font-semibold text-blue-600">{{ stats.todo }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">En Progreso
                                </p>
                                <p class="text-2xl font-semibold text-yellow-600">{{ stats.in_progress }}</p>
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
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">Completadas
                                </p>
                                <p class="text-2xl font-semibold text-green-600">{{ stats.done }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input v-model="searchQuery" type="text" placeholder="Buscar tareas..."
                                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div class="relative">
                            <select v-model="filterStatus"
                                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer">
                                <option value="">Todos los estados</option>
                                <option value="todo">Por Hacer</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="done">Completadas</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div class="relative">
                            <select v-model="filterDue"
                                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer">
                                <option value="">Todas las fechas</option>
                                <option value="overdue">Vencidas</option>
                                <option value="today">Hoy</option>
                                <option value="week">Esta semana</option>
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
                <div v-if="loading" class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
                    <div
                        class="inline-block w-8 h-8 border-3 border-gray-200 border-t-blue-600 rounded-full animate-spin mb-3">
                    </div>
                    <p class="text-sm text-gray-500">Cargando tareas...</p>
                </div>

                <!-- Empty State -->
                <div v-else-if="filteredTasks.length === 0"
                    class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-gray-900 font-semibold mb-1">No hay tareas</p>
                    <p class="text-sm text-gray-500">{{ searchQuery || filterStatus || filterDue ? 'Intenta ajustar los filtros' : 'Todas las tareas están completas' }}</p>
                </div>

                <!-- Tasks List -->
                <div v-else>
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm mb-6">
                        <div class="divide-y divide-gray-100">
                            <div v-for="task in paginatedTasks" :key="task.id" @click="openTaskDetail(task)"
                                class="p-4 hover:bg-gray-50 cursor-pointer transition group">
                                <div class="flex items-start gap-4">
                                    <!-- Checkbox -->
                                    <button @click.stop="toggleTaskStatus(task)" class="mt-0.5 flex-shrink-0">
                                        <div v-if="task.status === 'done'"
                                            class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center hover:bg-green-600 transition">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div v-else
                                            class="w-5 h-5 rounded-full border-2 border-gray-300 hover:border-blue-500 transition">
                                        </div>
                                    </button>

                                    <!-- Task Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h3 class="text-base font-semibold text-gray-900 group-hover:text-blue-600 transition"
                                                :class="{ 'line-through text-gray-500': task.status === 'done' }">
                                                {{ task.title }}
                                            </h3>
                                            <span :class="getStatusClass(task.status)"
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium flex-shrink-0">
                                                {{ getStatusLabel(task.status) }}
                                            </span>
                                        </div>

                                        <p v-if="task.description" class="text-sm text-gray-600 mb-3 line-clamp-1">
                                            {{ task.description }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                                {{ task.project?.name }}
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Asignada {{ formatTimeAgo(task.created_at) }}
                                            </span>
                                            <span v-if="task.due_date" :class="getDueDateClass(task.due_date)"
                                                class="flex items-center gap-1.5 px-2 py-1 rounded-md font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ formatDate(task.due_date) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <p class="text-sm text-gray-600">
                                    Mostrando <span class="font-medium">{{ startItem }}</span> a <span
                                        class="font-medium">{{ endItem }}</span> de <span class="font-medium">{{
                                        filteredTasks.length }}</span> tareas
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px gap-x-1"
                                    aria-label="Pagination">
                                    <button @click="currentPage--" :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-lg border border-gray-100 bg-white text-sm font-medium text-blue-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
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
                                                ? 'z-10 bg-blue-500 border-gray-50 text-white rounded-lg cursor-pointer'
                                                : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50 rounded-lg cursor-pointer'
                                        ]">
                                            {{ page }}
                                        </button>
                                        <span v-else
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                            ...
                                        </span>
                                    </template>

                                    <button @click="currentPage++" :disabled="currentPage === totalPages"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-lg border border-gray-100 bg-white text-sm font-medium text-blue-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition cursor-pointer">
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

            <!-- Task Detail Modal -->
            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="selectedTask" @click.self="selectedTask = null"
                    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                    <transition enter-active-class="transition ease-out duration-200"
                        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <div v-if="selectedTask"
                            class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh]">
                            <!-- Modal Header -->
                            <div
                                class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-shrink-0 bg-gradient-to-r from-gray-50 to-white">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-900">Detalles de la Tarea</h2>
                                </div>
                                <button @click="selectedTask = null"
                                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Content -->
                            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                                <!-- Title -->
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ selectedTask.title }}</h3>
                                    <span :class="getStatusClass(selectedTask.status)"
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium">
                                        {{ getStatusLabel(selectedTask.status) }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <div v-if="selectedTask.description"
                                    class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ selectedTask.description }}</p>
                                </div>

                                <!-- Info Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                            <span
                                                class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Proyecto</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">{{ selectedTask.project?.name }}
                                        </p>
                                    </div>

                                    <div v-if="selectedTask.due_date"
                                        class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Fecha
                                                límite</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">{{
                                            formatFullDate(selectedTask.due_date) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-5 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                                <!-- Cerrar -->
                                <button 
                                    @click="selectedTask = null"
                                    class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
                                >
                                    Cancelar
                                </button>

                                <!-- Ver Proyecto -->
                                <router-link 
                                    :to="`/projects/${selectedTask.project_id}`" 
                                    @click="selectedTask = null"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Ver Proyecto
                                </router-link>
                            </div>
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
import NavBar from '../components/common/NavBar.vue'

const projectStore = useProjectStore()
const authStore = useAuthStore()

const loading = ref(true)
const searchQuery = ref('')
const filterStatus = ref('')
const filterDue = ref('')
const selectedTask = ref(null)

// Variables de paginación
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Computed properties para la paginación
const totalPages = computed(() => {
    return Math.ceil(filteredTasks.value.length / itemsPerPage.value)
})

const paginatedTasks = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredTasks.value.slice(start, end)
})

const startItem = computed(() => {
    return (currentPage.value - 1) * itemsPerPage.value + 1
})

const endItem = computed(() => {
    const end = currentPage.value * itemsPerPage.value
    return end > filteredTasks.value.length ? filteredTasks.value.length : end
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
            pages.push(1, 2, 3, 4, '...', total)
        } else if (current >= total - 2) {
            pages.push(1, '...', total - 3, total - 2, total - 1, total)
        } else {
            pages.push(1, '...', current - 1, current, current + 1, '...', total)
        }
    }

    return pages
})

// Watch para resetear la página cuando cambien los filtros
watch([searchQuery, filterStatus, filterDue], () => {
    currentPage.value = 1
})

onMounted(async () => {
    await projectStore.fetchProjects()
    loading.value = false
})

const myTasks = computed(() => {
    const projects = projectStore.projects
    const allTasks = projects.flatMap(p => (p.tasks || []).map(t => ({ ...t, project: p })))
    return allTasks.filter(t => t.user_id === authStore.user?.id)
})

const filteredTasks = computed(() => {
    let tasks = [...myTasks.value]

    if (searchQuery.value) {
        tasks = tasks.filter(t =>
            t.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            t.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }

    if (filterStatus.value) {
        tasks = tasks.filter(t => t.status === filterStatus.value)
    }

    if (filterDue.value) {
        const today = new Date()
        today.setHours(0, 0, 0, 0)

        tasks = tasks.filter(t => {
            if (!t.due_date) return false
            const due = new Date(t.due_date)
            due.setHours(0, 0, 0, 0)

            if (filterDue.value === 'overdue') {
                return due < today && t.status !== 'done'
            } else if (filterDue.value === 'today') {
                return due.getTime() === today.getTime()
            } else if (filterDue.value === 'week') {
                const weekLater = new Date(today)
                weekLater.setDate(weekLater.getDate() + 7)
                return due >= today && due <= weekLater
            }
            return true
        })
    }

    tasks.sort((a, b) => {
        if (!a.due_date) return 1
        if (!b.due_date) return -1
        return new Date(a.due_date) - new Date(b.due_date)
    })

    return tasks
})

const stats = computed(() => {
    const tasks = myTasks.value
    return {
        total: tasks.length,
        todo: tasks.filter(t => t.status === 'todo').length,
        in_progress: tasks.filter(t => t.status === 'in_progress').length,
        done: tasks.filter(t => t.status === 'done').length
    }
})

const getStatusLabel = (status) => {
    const labels = {
        'todo': 'Por Hacer',
        'in_progress': 'En Progreso',
        'done': 'Completada'
    }
    return labels[status] || status
}

const getStatusClass = (status) => {
    const classes = {
        'todo': 'bg-blue-100 text-blue-700 border border-blue-200',
        'in_progress': 'bg-yellow-100 text-yellow-700 border border-yellow-200',
        'done': 'bg-green-100 text-green-700 border border-green-200'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getDueDateClass = (dueDate) => {
    if (!dueDate) return ''
    const due = new Date(dueDate)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    due.setHours(0, 0, 0, 0)

    const diffTime = due.getTime() - today.getTime()
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

    if (diffDays < 0) return 'bg-red-100 text-red-700 border border-red-200'
    if (diffDays === 0) return 'bg-orange-100 text-orange-700 border border-orange-200'
    if (diffDays <= 3) return 'bg-yellow-100 text-yellow-700 border border-yellow-200'
    return 'bg-gray-100 text-gray-600'
}

const formatDate = (date) => {
    if (!date) return ''
    const d = new Date(date)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    d.setHours(0, 0, 0, 0)

    if (d.getTime() === today.getTime()) return 'Hoy'

    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)
    if (d.getTime() === tomorrow.getTime()) return 'Mañana'

    return d.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })
}

const formatFullDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const openTaskDetail = (task) => {
    selectedTask.value = task
}

const toggleTaskStatus = async (task) => {
    const newStatus = task.status === 'done' ? 'todo' : 'done'
    try {
        await projectStore.updateTask(task.id, { status: newStatus })
        await projectStore.fetchProjects()
    } catch (error) {
        console.error('Error updating task:', error)
    }
}

const formatTimeAgo = (date) => {
    if (!date) return ''
    const now = new Date()
    const past = new Date(date)
    const diffMs = now - past
    const diffMins = Math.floor(diffMs / 60000)
    const diffHours = Math.floor(diffMs / 3600000)
    const diffDays = Math.floor(diffMs / 86400000)
    const diffWeeks = Math.floor(diffMs / 604800000)
    const diffMonths = Math.floor(diffMs / 2592000000)

    if (diffMins < 1) return 'hace un momento'
    if (diffMins < 60) return `hace ${diffMins}m`
    if (diffHours < 24) return `hace ${diffHours}h`
    if (diffDays < 7) return `hace ${diffDays}d`
    if (diffWeeks < 4) return `hace ${diffWeeks} semana${diffWeeks > 1 ? 's' : ''}`
    if (diffMonths < 12) return `hace ${diffMonths} mes${diffMonths > 1 ? 'es' : ''}`

    const years = Math.floor(diffMonths / 12)
    return `hace ${years} año${years > 1 ? 's' : ''}`
}
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>