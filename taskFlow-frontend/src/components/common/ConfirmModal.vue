<template>
    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] p-4"
            @click.self="handleCancel">
            <transition enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-4 scale-95" enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-4 scale-95">
                <div v-if="show" class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <!-- Icon -->
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full"
                        :class="variant === 'danger' ? 'bg-red-100' : 'bg-yellow-100'">
                        <svg v-if="variant === 'danger'" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <svg v-else class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Title & Message -->
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ title }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ message }}
                        </p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="pt-3 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                        <!-- Cancelar -->
                        <button type="button" @click="handleCancel" :disabled="loading"
                            class="text-gray-500 hover:text-gray-700 text-sm font-medium transition transform ease-in-out duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ cancelText }}
                        </button>

                        <!-- Confirmar -->
                        <button type="button" @click="handleConfirm" :disabled="loading" :class="[
                            'w-full sm:w-auto px-5 py-2.5 text-white text-sm font-semibold rounded-lg transition transform ease-in-out duration-300 cursor-pointer flex items-center justify-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed',
                            variant === 'danger'
                                ? 'bg-red-600 hover:bg-red-700'
                                : 'bg-blue-600 hover:bg-blue-700'
                        ]">
                            <span v-if="loading" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Procesando...
                            </span>
                            <template v-else>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ confirmText }}
                            </template>
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue'

const show = ref(false)
const title = ref('¿Estás seguro?')
const message = ref('Esta acción no se puede deshacer')
const confirmText = ref('Confirmar')
const cancelText = ref('Cancelar')
const variant = ref('danger')

let resolvePromise = null

const open = (options = {}) => {
    console.log('Options recibidas:', options)
    // Actualizar props dinámicamente
    title.value = options.title || '¿Estás seguro?'
    message.value = options.message || 'Esta acción no se puede deshacer'
    confirmText.value = options.confirmText || 'Confirmar'
    cancelText.value = options.cancelText || 'Cancelar'
    variant.value = options.variant || 'danger'

    show.value = true
    return new Promise((resolve) => {
        resolvePromise = resolve
    })
}

const handleConfirm = () => {
    show.value = false
    if (resolvePromise) resolvePromise(true)
}

const handleCancel = () => {
    show.value = false
    if (resolvePromise) resolvePromise(false)
}

defineExpose({ open })
</script>