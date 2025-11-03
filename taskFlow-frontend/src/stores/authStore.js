import { defineStore } from "pinia";
import { ref, computed } from "vue";
import api from '@/services/api';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(JSON.parse(localStorage.getItem('user')) || null)
    const token = ref(localStorage.getItem('token') || null)
    const isAuthenticated = computed(()=> !!token.value);

    const register = async (name, email, password, passwordConfirmation) => {
    try {
        const response = await api.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation
        })
        token.value = response.data.token
        user.value = response.data.user
        localStorage.setItem('token', token.value)
        localStorage.setItem('user', JSON.stringify(user.value)) // Guardar usuario
        return response.data
    } catch (error) {
        throw error.response?.data || error
    }
    }

    const login = async (email, password) => {
    try {
        const response = await api.post('/login', { email, password })
        token.value = response.data.token
        user.value = response.data.user
        localStorage.setItem('token', token.value)
        localStorage.setItem('user', JSON.stringify(user.value)) // Guardar usuario
        return response.data
    } catch (error) {
        throw error.response?.data || error
    }
    }

    const logout = async () => {
        try {
            await api.post('/logout')
        } catch (error) {
            console.error('Error logging out:', error)
        } finally {
            token.value = null
            user.value = null
            localStorage.removeItem('token')
            localStorage.removeItem('user') // Limpiar usuario también
        }
        }

    return {
        user,
        token,
        isAuthenticated,
        register,
        login,
        logout
    }
})

