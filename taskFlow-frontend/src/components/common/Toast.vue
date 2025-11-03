<template>
    <transition-group name="toast" tag="div" class="fixed bottom-4 right-4 z-[100] space-y-2 max-w-sm w-full px-4">
        <div v-for="toast in toasts" :key="toast.id" :class="[
            'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg border bg-white',
            getBorderClass(toast.type)
        ]" role="alert">
            <!-- Icon -->
            <div :class="[
                'flex-shrink-0',
                getIconColorClass(toast.type)
            ]">
                <!-- Success Icon -->
                <svg v-if="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <!-- Error Icon -->
                <svg v-else-if="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <!-- Warning Icon -->
                <svg v-else-if="toast.type === 'warning'" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <!-- Info Icon -->
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">
                    {{ toast.message }}
                </p>
            </div>

            <!-- Close Button -->
            <button @click="removeToast(toast.id)"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors p-1" aria-label="Cerrar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </transition-group>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])

const getBorderClass = (type) => {
    const classes = {
        success: 'border-green-100',
        error: 'border-red-100',
        warning: 'border-yellow-100',
        info: 'border-blue-100'
    }
    return classes[type] || classes.info
}

const getIconColorClass = (type) => {
    const classes = {
        success: 'text-green-600',
        error: 'text-red-600',
        warning: 'text-yellow-600',
        info: 'text-blue-600'
    }
    return classes[type] || classes.info
}

const addToast = (message, type = 'info', duration = 3000) => {
    const id = Date.now()
    const toast = {
        id,
        message,
        type
    }

    toasts.value.push(toast)

    if (duration > 0) {
        setTimeout(() => {
            removeToast(id)
        }, duration)
    }
}

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
}

defineExpose({ addToast })
</script>

<style scoped>
.toast-move {
    transition: transform 0.3s ease;
}

.toast-enter-active {
    transition: all 0.3s ease-out;
}

.toast-leave-active {
    transition: all 0.2s ease-in;
    position: absolute;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>