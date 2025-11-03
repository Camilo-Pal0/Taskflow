<template>
  <Navbar>
    <div class="min-h-screen bg-gray-50">
      <!-- Loading State -->
      <div v-if="loading" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center py-12">
          <div class="inline-block w-8 h-8 border-3 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
          <p class="text-sm text-gray-500 mt-3">Cargando proyecto...</p>
        </div>
      </div>

      <!-- Main Content -->
      <div v-else-if="projectStore.currentProject" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Project Header -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm">
          <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex-1 min-w-0">
              <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900 mb-2">
                {{ projectStore.currentProject.name }}
              </h1>
              <p class="text-gray-600 mb-4">
                {{ projectStore.currentProject.description || 'Sin descripción' }}
              </p>
              <div class="flex items-center gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <span>{{ projectStore.currentProject.members?.length || 0 }} miembros</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <span>{{ getTasks().length }} tareas</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
              <button 
                v-if="isProjectOwner"
                @click="showEditProjectModal = true"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition transform ease-in-out duration-300 shadow-sm cursor-pointer"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
              </button>
              <button 
                @click="showMembersModal = true"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition transform ease-in-out duration-300 shadow-sm cursor-pointer"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Miembros
              </button>
              <button 
                @click="showTaskModal = true"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 shadow-sm cursor-pointer"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Tarea
              </button>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input 
                v-model="taskSearch"
                type="text"
                placeholder="Buscar tareas..."
                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
              >
            </div>
            <div class="relative">
              <select 
                v-model="filterAssignee"
                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer"
              >
                <option value="">Todos los miembros</option>
                <option 
                  v-for="member in projectStore.currentProject?.members" 
                  :key="member.id"
                  :value="member.id"
                >
                  {{ member.name }}
                </option>
              </select>
              <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <div class="relative">
              <select 
                v-model="filterStatus"
                class="w-full sm:w-auto appearance-none px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition cursor-pointer"
              >
                <option value="">Todos los estados</option>
                <option value="todo">Por Hacer</option>
                <option value="in_progress">En Progreso</option>
                <option value="done">Completadas</option>
              </select>
              <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Task Board -->
        <TaskBoard 
          :tasks="getTasks()" 
          :members="projectStore.currentProject?.members || []"
          :current-user-id="authStore.user?.id"
          @update-task="handleUpdateTask" 
          @delete-task="handleDeleteTask" 
        />
      </div>

      <!-- Create Task Modal -->
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showTaskModal" @click.self="showTaskModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <div v-if="showTaskModal" class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
              <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Nueva Tarea</h2>
                <button @click="showTaskModal = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <form @submit.prevent="handleCreateTask" class="p-6 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                  <input 
                    v-model="newTask.title" 
                    type="text" 
                    required
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Título de la tarea"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                  <textarea 
                    v-model="newTask.description" 
                    rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition"
                    placeholder="Describe la tarea..."
                  ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select 
                      v-model="newTask.status"
                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition"
                    >
                      <option value="todo">Por Hacer</option>
                      <option value="in_progress">En Progreso</option>
                      <option value="done">Completada</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a</label>
                    <select 
                      v-model="newTask.assigned_to"
                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition"
                    >
                      <option :value="null">Sin asignar</option>
                      <option 
                        v-for="member in projectStore.currentProject?.members" 
                        :key="member.id"
                        :value="member.id"
                      >
                        {{ member.name }}
                      </option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Fecha límite</label>
                  <input 
                    v-model="newTask.due_date"
                    type="date"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                  >
                </div>

                <div v-if="error" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ error }}</span>
                </div>

                <!-- Modal Footer -->
                <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                  <!-- Cancelar -->
                  <button 
                    type="button" 
                    @click="showTaskModal = false"
                    class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
                  >
                    Cancelar
                  </button>

                  <!-- Crear Tarea -->
                  <button 
                    type="submit"
                    :disabled="taskLoading"
                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="taskLoading" class="flex items-center justify-center gap-2">
                      <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Creando...
                    </span>
                    <template v-else>
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                      Crear Tarea
                    </template>
                  </button>
                </div>
              </form>
            </div>
          </transition>
        </div>
      </transition>

      <!-- Members Modal -->
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showMembersModal" @click.self="showMembersModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <div v-if="showMembersModal" class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden max-h-[90vh] flex flex-col">
              <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                <h2 class="text-lg font-semibold text-gray-900">Miembros del Proyecto</h2>
                <button @click="showMembersModal = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <div class="flex-1 overflow-y-auto p-6">
                <div class="mb-6">
                  <h3 class="text-sm font-semibold text-gray-700 mb-3">Miembros del Proyecto</h3>
                  
                  <!-- Owners Section -->
                  <div v-if="getOwners().length > 0" class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                      </svg>
                      Propietarios
                    </p>
                    <div class="space-y-2">
                      <div 
                        v-for="member in getOwners()" 
                        :key="member.id"
                        class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200"
                      >
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 ring-2 ring-white shadow-md">
                            <span class="text-sm font-medium text-white">
                              {{ member.name.charAt(0).toUpperCase() }}
                            </span>
                          </div>
                          <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ member.name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ member.email }}</p>
                          </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                          <span class="text-xs font-semibold bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 py-1 rounded-full shadow-sm">
                            Propietario
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Regular Members Section -->
                  <div v-if="getRegularMembers().length > 0">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      Miembros
                    </p>
                    <div class="space-y-2">
                      <div 
                        v-for="member in getRegularMembers()" 
                        :key="member.id"
                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition"
                      >
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-medium text-white">
                              {{ member.name.charAt(0).toUpperCase() }}
                            </span>
                          </div>
                          <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ member.name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ member.email }}</p>
                          </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                          <span class="text-xs font-medium bg-gray-200 text-gray-700 px-2 py-1 rounded-full">
                            Miembro
                          </span>
                          <button 
                            v-if="isProjectOwner"
                            @click="handleRemoveMember(member.id)"
                            class="p-1 text-gray-500 hover:text-gray-800 transition cursor-pointer"
                            title="Eliminar miembro"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Empty state -->
                  <div v-if="getRegularMembers().length === 0 && getOwners().length <= 1" class="text-center py-6 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-sm font-medium">Agrega miembros al proyecto</p>
                  </div>
                </div>

                <!-- Form para agregar miembros (solo visible para owners) -->
                <form 
                  v-if="isProjectOwner"
                  @submit.prevent="handleAddMember" 
                  class="space-y-4 border-t border-gray-200 pt-6"
                >
                  <h3 class="text-sm font-semibold text-gray-700">Agregar Miembro</h3>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                      v-model="newMember.email" 
                      type="email" 
                      required
                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                      placeholder="usuario@ejemplo.com"
                    >
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                    <select 
                      v-model="newMember.role"
                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white transition"
                    >
                      <option value="member">Miembro</option>
                      <option value="owner">Propietario</option>
                    </select>
                  </div>

                  <!-- Modal Footer -->
                  <div class="pt-1 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                    <!-- Cerrar -->
                    <button 
                      type="button" 
                      @click="showMembersModal = false"
                      class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
                    >
                      Cerrar
                    </button>

                    <!-- Agregar -->
                    <button 
                      type="submit"
                      :disabled="memberLoading"
                      class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span v-if="memberLoading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Agregando...
                      </span>
                      <template v-else>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Agregar
                      </template>
                    </button>
                  </div>
                </form>

                <!-- Mensaje alternativo para miembros regulares -->
                <div v-else class="border-t border-gray-200 pt-6 text-center">
                  <p class="text-sm text-gray-500">Solo los propietarios pueden agregar miembros</p>
                </div>

              </div>
            </div>
          </transition>
        </div>
      </transition>      

      <!-- Edit Project Modal -->
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showEditProjectModal" @click.self="showEditProjectModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <div v-if="showEditProjectModal" class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
              <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Editar Proyecto</h2>
                <button @click="showEditProjectModal = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <form @submit.prevent="handleUpdateProject" class="p-6 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Proyecto</label>
                  <input 
                    v-model="editProject.name" 
                    type="text" 
                    required
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                  <textarea 
                    v-model="editProject.description" 
                    rows="4"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg outline-0 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none transition"
                  ></textarea>
                </div>

                <div v-if="error" class="flex items-start gap-2 text-red-600 text-sm bg-red-50 px-3 py-2.5 rounded-lg border border-red-100">
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ error }}</span>
                </div>

                <!-- Modal Footer -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                  <!-- Cancelar -->
                  <button 
                    type="button" 
                    @click="showEditProjectModal = false"
                    class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer"
                  >
                    Cancelar
                  </button>

                  <!-- Actualizar -->
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
                      Actualizando...
                    </span>
                    <template v-else>
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Actualizar
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
import { ref, onMounted, computed, watch, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useProjectStore } from '@/stores/projectStore'
import Navbar from '@/components/common/Navbar.vue'
import TaskBoard from '@/components/projects/TaskBoard.vue'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'

const route = useRoute()
const projectStore = useProjectStore()
const authStore = useAuthStore()
const toast = useToast()
const { confirm } = useConfirm()

const loading = ref(true)
const showTaskModal = ref(false)
const newTask = ref({ 
  title: '', 
  description: '', 
  status: 'todo', 
  assigned_to: null,
  due_date: null 
})
const error = ref('')
const taskLoading = ref(false)
const showMembersModal = ref(false)
const newMember = ref({ email: '', role: 'member' })
const memberError = ref('')
const memberLoading = ref(false)
const showEditProjectModal = ref(false)
const editProject = ref({ name: '', description: '' })
const taskSearch = ref('')
const filterAssignee = ref('')
const filterStatus = ref('')

let refreshInterval = null

onMounted(async () => {
  await projectStore.getProject(route.params.id)
  loading.value = false
  
  // Auto-refresh cada 10 segundos
  refreshInterval = setInterval(async () => {
    await projectStore.getProject(route.params.id)
  }, 10000)
})

onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval)
  }
})

const getTasks = () => {
  let tasks = projectStore.currentProject?.tasks || []
  
  // Búsqueda
  if (taskSearch.value) {
    tasks = tasks.filter(t => 
      t.title.toLowerCase().includes(taskSearch.value.toLowerCase()) ||
      t.description?.toLowerCase().includes(taskSearch.value.toLowerCase())
    )
  }
  
  // Filtro por asignado
  if (filterAssignee.value) {
    tasks = tasks.filter(t => t.user_id === parseInt(filterAssignee.value))
  }
  
  // Filtro por status
  if (filterStatus.value) {
    tasks = tasks.filter(t => t.status === filterStatus.value)
  }
  
  return tasks
}

const handleCreateTask = async () => {
  taskLoading.value = true
  error.value = ''
  
  try {
    const assignedTo = newTask.value.assigned_to || authStore.user.id
    
    await projectStore.createTask(
      newTask.value.title, 
      newTask.value.description, 
      route.params.id,
      newTask.value.status,
      assignedTo,
      newTask.value.due_date
    )
    await projectStore.getProject(route.params.id)
    toast.success('Tarea creada exitosamente')
    showTaskModal.value = false
    newTask.value = { title: '', description: '', status: 'todo', assigned_to: null, due_date: null }
  } catch (err) {
    error.value = err.message || 'Error al crear la tarea'
    toast.error(err.message || 'Error al crear la tarea')
  } finally {
    taskLoading.value = false
  }
}

const handleUpdateTask = async (taskId, updates) => {
  try {
    await projectStore.updateTask(taskId, updates)
    await projectStore.getProject(route.params.id)
    toast.success('Tarea actualizada')
  } catch (error) {
    toast.error('Error al actualizar la tarea')
  }
}

const handleDeleteTask = async (taskId) => {
  const confirmed = await confirm({
    title: '¿Eliminar tarea?',
    message: 'Esta acción no se puede deshacer.',
    confirmText: 'Eliminar',
    cancelText: 'Cancelar',
    variant: 'danger'
  })

  if (confirmed) {
    try {
      await projectStore.deleteTask(taskId)
      await projectStore.getProject(route.params.id)
      toast.success('Tarea eliminada')
    } catch (error) {
      toast.error('Error al eliminar la tarea')
    }
  }
}

const handleAddMember = async () => {
  memberLoading.value = true
  memberError.value = ''
  
  try {
    await projectStore.addMember(route.params.id, newMember.value.email, newMember.value.role)
    await projectStore.getProject(route.params.id)
    toast.success('Miembro agregado exitosamente')
    newMember.value = { email: '', role: 'member' }
    memberError.value = ''
  } catch (err) {
    memberError.value = err.message || 'Error al agregar miembro'
    toast.error(err.message || 'Error al agregar miembro')
  } finally {
    memberLoading.value = false
  }
}

const isProjectOwner = computed(() => {
  const project = projectStore.currentProject
  if (!project) return false
  
  const user = authStore.user
  // Es owner si es creador O tiene rol 'owner'
  const hasOwnerRole = project.members?.some(m => 
    m.id === user?.id && m.pivot?.role === 'owner'
  )
  
  return project.user_id === user?.id || hasOwnerRole
})

const handleRemoveMember = async (userId) => {
  console.log('userId recibido:', userId) // <-- Agrega esto
  
  const confirmed = await confirm({
    title: '¿Eliminar miembro?',
    message: 'Este miembro ya no tendrá acceso al proyecto.',
    confirmText: 'Eliminar',
    cancelText: 'Cancelar',
    variant: 'danger'
  })

  if (confirmed) {
    try {
      await projectStore.removeMember(route.params.id, userId)
      await projectStore.getProject(route.params.id)
      toast.success('Miembro eliminado del proyecto')
    } catch (error) {
      toast.error('Error al eliminar miembro')
    }
  }
}

watch(() => projectStore.currentProject, (project) => {
  if (project) {
    editProject.value = {
      name: project.name,
      description: project.description
    }
  }
})

const handleUpdateProject = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await projectStore.updateProject(
      route.params.id,
      editProject.value.name,
      editProject.value.description
    )
    await projectStore.getProject(route.params.id)
    toast.success('Proyecto actualizado exitosamente')
    showEditProjectModal.value = false
  } catch (err) {
    error.value = err.message || 'Error al actualizar el proyecto'
    toast.error(err.message || 'Error al actualizar el proyecto')
  } finally {
    loading.value = false
  }
}

const getOwners = () => {
  const members = projectStore.currentProject?.members || []
  return members.filter(member => 
    member.pivot?.role === 'owner' || 
    member.id === projectStore.currentProject?.user_id
  ).sort((a, b) => a.name.localeCompare(b.name))
}

const getRegularMembers = () => {
  const members = projectStore.currentProject?.members || []
  return members.filter(member => 
    member.pivot?.role !== 'owner' && 
    member.id !== projectStore.currentProject?.user_id
  ).sort((a, b) => a.name.localeCompare(b.name))
}

</script>