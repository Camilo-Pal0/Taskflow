<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
    <div 
      v-for="column in columns" 
      :key="column.status" 
      class="flex flex-col"
      @drop="onDrop($event, column.status)"
      @dragover.prevent
      @dragenter.prevent
    >
      <!-- Column Header -->
      <div class="flex items-center justify-between mb-4 px-1">
        <div class="flex items-center gap-2">
          <div 
            class="w-2 h-2 rounded-full"
            :class="column.color"
          ></div>
          <h3 class="font-semibold text-sm text-gray-900">
            {{ column.title }}
          </h3>
          <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
            {{ getTasksByStatus(column.status).length }}
          </span>
        </div>
      </div>

      <!-- Tasks Container -->
      <div class="space-y-2.5 min-h-[200px] lg:min-h-[400px] bg-gray-50 rounded-xl p-3 border border-gray-100">
        <TransitionGroup name="task-list">
          <div 
            v-for="task in getTasksByStatus(column.status)" 
            :key="task.id"
            draggable="true"
            @dragstart="onDragStart($event, task)"
            @dragend="onDragEnd"
            class="group relative bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer border border-gray-200 hover:border-gray-300"
            :class="{ 'opacity-50': draggedTask?.id === task.id }"
            @click="openTaskDetail(task)"
          >
            <!-- Dropdown Menu -->
            <div class="absolute top-3 right-3 z-10" @click.stop>
              <button 
                @click.stop="task.showMenu = !task.showMenu"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition lg:opacity-0 lg:group-hover:opacity-100 cursor-pointer"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
              </button>

              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div 
                  v-if="task.showMenu" 
                  class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1"
                >
                  
                  <!-- Mobile: Move to Status Options -->
                  <div class="lg:hidden border-b border-gray-100 mt-1 pt-1">
                    <button 
                      v-for="col in columns.filter(c => c.status !== column.status)"
                      :key="col.status"
                      @click.stop="moveTaskToStatus(task, col.status)"
                      class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition text-start gap-x-2"
                    >
                      <div 
                        class="w-2 h-2 rounded-full mr-2"
                        :class="col.color"
                      ></div>
                      <span>Mover a {{ col.title }}</span>
                    </button>
                  </div>

                  <button 
                    @click.stop="openTaskDetail(task)"
                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition cursor-pointer"
                  >
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                  </button>


                  <button 
                    @click.stop="deleteTask(task.id)"
                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 transition border-t border-gray-100 mt-1 pt-1 cursor-pointer"
                  >
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Eliminar
                  </button>
                </div>
              </transition>
            </div>

            <!-- Task Title -->
            <h4 class="font-medium text-gray-900 mb-2 text-sm leading-snug group-hover:text-blue-600 transition-colors pr-8">
              {{ task.title }}
            </h4>
            
            <!-- Task Description -->
            <p 
              v-if="task.description" 
              class="text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed"
            >
              {{ task.description }}
            </p>
            
            <!-- Task Footer -->
            <div class="flex items-center justify-between gap-2 text-xs mt-3 pt-3 border-t border-gray-100">
              <!-- Assignee -->
              <div class="flex items-center gap-2 flex-1 min-w-0">
                <div 
                  v-if="task.user"
                  class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 ring-2 ring-white"
                  :title="task.user.name"
                >
                  <span class="text-[10px] font-semibold text-white">
                    {{ task.user.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
                <span class="text-gray-600 truncate text-xs font-medium">
                  {{ task.user?.name || 'Sin asignar' }}
                </span>
              </div>

              <!-- Due Date Badge -->
              <div 
                v-if="task.due_date"
                :class="getDueDateClass(task.due_date)"
                class="px-2 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap flex items-center gap-1"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(task.due_date) }}
              </div>
            </div>

            <!-- Comments indicator -->
            <div 
              v-if="task.comments && task.comments.length > 0"
              class="flex items-center gap-1 mt-2 pt-2 border-t border-gray-100 text-gray-500"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <span class="text-xs font-medium">{{ task.comments.length }}</span>
            </div>

            <!-- Mobile: Quick Action Buttons (only show for non-completed tasks) -->
            <div 
              v-if="column.status !== 'done'"
              class="lg:hidden flex gap-2 mt-3 pt-3 border-t border-gray-100"
              @click.stop
            >
              <button
                v-if="column.status === 'todo'"
                @click.stop="moveTaskToStatus(task, 'in_progress')"
                class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100 transition flex items-center justify-center gap-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Iniciar
              </button>

              <button
                v-if="column.status === 'in_progress'"
                @click.stop="moveTaskToStatus(task, 'done')"
                class="flex-1 px-3 py-2 bg-green-50 text-green-600 rounded-lg text-xs font-medium hover:bg-green-100 transition flex items-center justify-center gap-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Completar
              </button>

              <button
                v-if="column.status === 'in_progress'"
                @click.stop="moveTaskToStatus(task, 'todo')"
                class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-200 transition flex items-center justify-center gap-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
              </button>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div 
          v-if="getTasksByStatus(column.status).length === 0"
          class="flex flex-col items-center justify-center py-8 lg:py-12 text-gray-400"
        >
          <svg class="w-10 h-10 lg:w-12 lg:h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-xs font-medium">Sin tareas</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Task Detail Modal -->
  <transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div 
      v-if="selectedTask" 
      @click.self="closeModal"
      class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <div 
          v-if="selectedTask"
          class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col"
        >
          <!-- Modal Header -->
          <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-shrink-0 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <h2 class="text-xl font-semibold text-gray-900">Detalles de la Tarea</h2>
            </div>
            <button 
              @click="closeModal" 
              class="text-gray-400 hover:text-gray-600 transition p-2 rounded-lg hover:bg-gray-100 cursor-pointer"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Modal Content -->
          <div class="flex-1 overflow-y-auto p-6 space-y-5">
            <!-- Title -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Título</label>
              <input 
                v-model="selectedTask.title"
                type="text"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Nombre de la tarea"
              >
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Descripción</label>
              <textarea 
                v-model="selectedTask.description"
                rows="4"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition"
                placeholder="Describe los detalles de la tarea..."
              ></textarea>
            </div>

            <!-- Status and Assignment Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <!-- Status -->
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Estado</label>
                <select 
                  v-model="selectedTask.status"
                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition appearance-none cursor-pointer"
                >
                  <option value="todo">Por Hacer</option>
                  <option value="in_progress">En Progreso</option>
                  <option value="done">Completada</option>
                </select>
              </div>

              <!-- Assigned to -->
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Asignado a</label>
                <select 
                  v-model="selectedTask.user_id"
                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition appearance-none cursor-pointer"
                >
                  <option 
                    v-for="member in members" 
                    :key="member.id"
                    :value="member.id"
                  >
                    {{ member.name }}
                  </option>
                </select>
              </div>

              <!-- Due Date -->
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Fecha límite</label>
                <input 
                  v-model="selectedTask.due_date"
                  type="date"
                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                >
              </div>
            </div>

            <!-- Comments Section -->
            <div class="border-t border-gray-200 pt-6">
              <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">
                  Comentarios
                  <span class="text-gray-500 font-normal ml-1">({{ selectedTask.comments?.length || 0 }})</span>
                </h3>
              </div>
              
              <!-- Comment Form -->
              <form @submit.prevent="addComment" class="mb-5">
                <div class="flex gap-3">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-white">
                      {{ getCurrentUserInitial() }}
                    </span>
                  </div>
                  <div class="flex-1">
                    <textarea 
                      v-model="newComment"
                      rows="2"
                      placeholder="Escribe un comentario..."
                      class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition mb-2"
                    ></textarea>
                    <button 
                      type="submit"
                      :disabled="!newComment.trim()"
                      class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                    >
                      Comentar
                    </button>
                  </div>
                </div>
              </form>

              <!-- Comments List -->
              <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                <TransitionGroup name="comment-list">
                  <div 
                    v-for="comment in selectedTask.comments" 
                    :key="comment.id"
                    class="flex gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 transition"
                  >
                    <!-- Avatar -->
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center flex-shrink-0">
                      <span class="text-xs font-semibold text-white">
                        {{ comment.user?.name?.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                      <!-- Header -->
                      <div class="flex items-start justify-between mb-1 sm:mb-1.5 gap-2">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-0.5 sm:gap-2 min-w-0">
                          <span class="font-semibold text-sm text-gray-900 truncate">
                            {{ comment.user?.name }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ formatCommentTime(comment.created_at) }}
                          </span>
                        </div>
                        
                        <!-- Delete Button -->
                        <button 
                          v-if="comment.user_id === currentUserId"
                          @click="deleteComment(comment.id)"
                          class="text-gray-400 hover:text-red-500 transition transform ease-in-out duration-300 flex items-center justify-center flex-shrink-0 p-1 cursor-pointer"
                          aria-label="Eliminar comentario"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </div>
                      
                      <!-- Comment Text -->
                      <p class="text-sm text-gray-700 leading-relaxed break-words">
                        {{ comment.content }}
                      </p>
                    </div>
                  </div>
                </TransitionGroup>
                
                <!-- Empty State -->
                <div 
                  v-if="!selectedTask.comments || selectedTask.comments.length === 0" 
                  class="text-center py-8 text-gray-400"
                >
                  <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <p class="text-sm font-medium">No hay comentarios aún</p>
                  <p class="text-xs mt-1">Sé el primero en comentar</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="px-6 py-4 border border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
            <!-- Cancelar -->
            <button 
              @click="closeModal"
              class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
            >
              Cancelar
            </button>

            <!-- Guardar Cambios -->
            <button 
              @click="saveTask"
              class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Guardar Cambios
            </button>
          </div>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import api from '@/services/api'

const props = defineProps({
  tasks: {
    type: Array,
    required: true
  },
  members: {
    type: Array,
    default: () => []
  },
  currentUserId: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['update-task', 'delete-task'])

const columns = [
  { 
    status: 'todo', 
    title: 'Por Hacer',
    color: 'bg-gray-400'
  },
  { 
    status: 'in_progress', 
    title: 'En Progreso',
    color: 'bg-blue-500'
  },
  { 
    status: 'done', 
    title: 'Completadas',
    color: 'bg-green-500'
  }
]

const selectedTask = ref(null)
const draggedTask = ref(null)
const newComment = ref('')

const getTasksByStatus = (status) => {
  return props.tasks.filter(task => task.status === status)
}

const openTaskDetail = async (task) => {
  try {
    const response = await api.get(`/tasks/${task.id}`)
    selectedTask.value = response.data
  } catch (error) {
    console.error('Error loading task details:', error)
    selectedTask.value = { ...task, comments: task.comments || [] }
  }
}

const closeModal = () => {
  selectedTask.value = null
  newComment.value = ''
}

const onDragStart = (event, task) => {
  draggedTask.value = task
  event.dataTransfer.effectAllowed = 'move'
}

const onDragEnd = () => {
  draggedTask.value = null
}

const onDrop = async (event, newStatus) => {
  if (!draggedTask.value) return
  
  if (draggedTask.value.status !== newStatus) {
    try {
      await emit('update-task', draggedTask.value.id, { status: newStatus })
    } catch (error) {
      console.error('Error updating task status:', error)
    }
  }
  
  draggedTask.value = null
}

const saveTask = async () => {
  try {
    await emit('update-task', selectedTask.value.id, {
      title: selectedTask.value.title,
      description: selectedTask.value.description,
      status: selectedTask.value.status,
      user_id: selectedTask.value.user_id,
      due_date: selectedTask.value.due_date
    })
    closeModal()
  } catch (error) {
    console.error('Error saving task:', error)
    alert('Error al guardar la tarea')
  }
}

const deleteTask = async (taskId) => {
  if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
    try {
      await emit('delete-task', taskId)
      closeModal()
    } catch (error) {
      console.error('Error deleting task:', error)
      alert('Error al eliminar la tarea')
    }
  }
}

const addComment = async () => {
  if (!newComment.value.trim()) return
  
  try {
    const response = await api.post(`/tasks/${selectedTask.value.id}/comments`, {
      content: newComment.value
    })
    
    if (!selectedTask.value.comments) {
      selectedTask.value.comments = []
    }
    selectedTask.value.comments.unshift(response.data)
    newComment.value = ''
  } catch (error) {
    console.error('Error adding comment:', error)
    alert('Error al agregar comentario')
  }
}

const deleteComment = async (commentId) => {
  if (!confirm('¿Eliminar este comentario?')) return
  
  try {
    await api.delete(`/comments/${commentId}`)
    selectedTask.value.comments = selectedTask.value.comments.filter(c => c.id !== commentId)
  } catch (error) {
    console.error('Error deleting comment:', error)
    alert('Error al eliminar comentario')
  }
}

const getCurrentUserInitial = () => {
  const member = props.members.find(m => m.id === props.currentUserId)
  return member?.name?.charAt(0).toUpperCase() || 'U'
}

const formatDate = (date) => {
  if (!date) return ''
  const d = new Date(date)
  const today = new Date()
  const tomorrow = new Date(today)
  tomorrow.setDate(tomorrow.getDate() + 1)
  
  today.setHours(0, 0, 0, 0)
  tomorrow.setHours(0, 0, 0, 0)
  d.setHours(0, 0, 0, 0)
  
  if (d.getTime() === today.getTime()) return 'Hoy'
  if (d.getTime() === tomorrow.getTime()) return 'Mañana'
  
  return d.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' })
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
  if (diffDays === 1) return 'bg-yellow-100 text-yellow-700 border border-yellow-200'
  return 'bg-blue-100 text-blue-700 border border-blue-200'
}

const formatCommentTime = (date) => {
  if (!date) return ''
  const now = new Date()
  const past = new Date(date)
  const diffMs = now - past
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMs / 3600000)
  const diffDays = Math.floor(diffMs / 86400000)
  
  if (diffMins < 1) return 'Ahora'
  if (diffMins < 60) return `Hace ${diffMins}m`
  if (diffHours < 24) return `Hace ${diffHours}h`
  if (diffDays < 7) return `Hace ${diffDays}d`
  return past.toLocaleDateString('es-ES')
}

const moveTaskToStatus = async (task, newStatus) => {
  task.showMenu = false
  
  try {
    await emit('update-task', task.id, { status: newStatus })
  } catch (error) {
    console.error('Error updating task status:', error)
    alert('Error al actualizar el estado de la tarea')
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.task-list-move,
.task-list-enter-active,
.task-list-leave-active {
  transition: all 0.3s ease;
}

.task-list-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.task-list-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.task-list-leave-active {
  position: absolute;
}

.comment-list-move,
.comment-list-enter-active,
.comment-list-leave-active {
  transition: all 0.3s ease;
}

.comment-list-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}

.comment-list-leave-to {
  opacity: 0;
  transform: translateX(20px);
}

::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>