<template>
  <div class="relative">
    <button 
      @click="togglePanel"
      class="relative p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition cursor-pointer"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <span 
        v-if="unreadCount > 0"
        class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center font-semibold"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <!-- Notifications Panel -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div 
        v-if="showPanel"
        class="fixed lg:absolute left-4 right-4 lg:left-0 lg:right-auto bottom-16 lg:bottom-full lg:mb-2 lg:w-96 bg-white rounded-xl shadow-xl border border-gray-200 z-50 max-h-[70vh] lg:max-h-[600px] overflow-hidden flex flex-col"
      >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
          <button 
            v-if="unreadCount > 0"
            @click="markAllAsRead"
            class="text-xs text-blue-600 hover:text-blue-700 font-medium transition"
          >
            Marcar todas
          </button>
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
          <div 
            v-for="notification in notifications" 
            :key="notification.id"
            :class="[
              'px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition group',
              !notification.read ? 'bg-blue-50/50' : ''
            ]"
            @click="handleNotificationClick(notification)"
          >
            <div class="flex items-start gap-3">
              <!-- Icon -->
              <div :class="[
                'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0',
                getNotificationColor(notification.type)
              ]">
                <component :is="getNotificationIcon(notification.type)" class="w-4 h-4 text-white" />
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-1">
                  <span class="text-sm font-medium text-gray-900 leading-snug">
                    {{ notification.title }}
                  </span>
                  <button 
                    @click.stop="deleteNotification(notification.id)"
                    class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition flex-shrink-0"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ notification.message }}</p>
                <div class="flex items-center gap-2">
                  <span class="text-xs text-gray-500">{{ formatTime(notification.created_at) }}</span>
                  <span v-if="!notification.read" class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="notifications.length === 0" class="py-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="text-sm text-gray-500">No hay notificaciones</p>
          </div>
        </div>

        <!-- Load More Footer -->
        <div v-if="hasMore" class="px-4 py-3 border-t border-gray-200">
          <button 
            @click="loadMore"
            class="w-full text-sm text-blue-600 hover:text-blue-700 font-medium transition"
          >
            Cargar más
          </button>
        </div>
      </div>
    </transition>

    <!-- Overlay -->
    <div 
      v-if="showPanel"
      @click="showPanel = false"
      class="fixed inset-0 z-40"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, h } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const showPanel = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
const currentPage = ref(1)
const hasMore = ref(false)
let pollInterval = null

const togglePanel = () => {
  showPanel.value = !showPanel.value
  if (showPanel.value) {
    loadNotifications()
  }
}

const loadNotifications = async () => {
  try {
    const response = await api.get(`/notifications?page=${currentPage.value}`)
    if (currentPage.value === 1) {
      notifications.value = response.data.data
    } else {
      notifications.value.push(...response.data.data)
    }
    hasMore.value = response.data.current_page < response.data.last_page
  } catch (error) {
    console.error('Error loading notifications:', error)
  }
}

const loadUnreadCount = async () => {
  try {
    const response = await api.get('/notifications/unread-count')
    unreadCount.value = response.data.count
  } catch (error) {
    console.error('Error loading unread count:', error)
  }
}

const markAllAsRead = async () => {
  try {
    await api.post('/notifications/mark-all-read')
    notifications.value.forEach(n => n.read = true)
    unreadCount.value = 0
  } catch (error) {
    console.error('Error marking all as read:', error)
  }
}

const handleNotificationClick = async (notification) => {
  if (!notification.read) {
    try {
      await api.patch(`/notifications/${notification.id}/read`)
      notification.read = true
      unreadCount.value = Math.max(0, unreadCount.value - 1)
    } catch (error) {
      console.error('Error marking as read:', error)
    }
  }

  showPanel.value = false

  // Navegar según el tipo de notificación
  if (notification.data?.project_id) {
    router.push(`/projects/${notification.data.project_id}`)
  }
}

const deleteNotification = async (id) => {
  try {
    await api.delete(`/notifications/${id}`)
    notifications.value = notifications.value.filter(n => n.id !== id)
    loadUnreadCount()
  } catch (error) {
    console.error('Error deleting notification:', error)
  }
}

const loadMore = () => {
  currentPage.value++
  loadNotifications()
}

const formatTime = (date) => {
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
  return past.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })
}

// Función para obtener el color según el tipo
const getNotificationColor = (type) => {
  const colors = {
    'task_assigned': 'bg-blue-500',
    'task_completed': 'bg-green-500',
    'project_update': 'bg-purple-500',
    'comment': 'bg-orange-500',
    'mention': 'bg-pink-500',
    'deadline': 'bg-red-500',
    'default': 'bg-gray-500'
  }
  return colors[type] || colors.default
}

// Función para obtener el icono según el tipo
const getNotificationIcon = (type) => {
  const icons = {
    'task_assigned': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' })
    ]),
    'task_completed': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' })
    ]),
    'project_update': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z' })
    ]),
    'comment': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' })
    ]),
    'mention': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207' })
    ]),
    'deadline': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' })
    ]),
    'default': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })
    ])
  }
  return icons[type] || icons.default
}

onMounted(() => {
  loadUnreadCount()
  // Polling cada 30 segundos
  pollInterval = setInterval(() => {
    loadUnreadCount()
  }, 30000)
})

onUnmounted(() => {
  if (pollInterval) {
    clearInterval(pollInterval)
  }
})
</script>