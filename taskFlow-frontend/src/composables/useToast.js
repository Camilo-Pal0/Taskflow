import { getCurrentInstance } from 'vue'

export function useToast() {
    const instance = getCurrentInstance()
    const toast = instance?.appContext.config.globalProperties.$toast

    return {
        success: (message, duration = 3000) => toast?.addToast(message, 'success', duration),
        error: (message, duration = 3000) => toast?.addToast(message, 'error', duration),
        warning: (message, duration = 3000) => toast?.addToast(message, 'warning', duration),
        info: (message, duration = 3000) => toast?.addToast(message, 'info', duration),
    }
}