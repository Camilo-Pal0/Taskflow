import { defineStore } from 'pinia';
import { ref } from 'vue'
import api from '@/services/api'

export const useProjectStore = defineStore('project', () => {
    const projects = ref([])
    const currentProject = ref(null)
    const tasks = ref([])
    const loading = ref(false)

    const fetchProjects = async () => {
        loading.value = true
        try {
            const response = await api.get('/projects')
            projects.value = response.data
        } catch (error) {
            console.error('Error al cargar proyectos', error)
        } finally {
            loading.value = false
        }
    }

    const createProject = async (name, description) => {
        try {
            const response = await api.post('/projects', { name, description })
            projects.value.push(response.data)
            return response.data
        } catch (error) {
            throw error.response?.data || error 
        }
    }

    const getProject = async (id) => {
        try{
            const response = await api.get(`/projects/${id}`)
            currentProject.value = response.data
            return response.data
        } catch (error) {
            console.error('Error cargando el proyecto: ', error)
        }
    }

    const updateProject = async (id, name, description) => {
        try {
            const response = await api.patch(`/projects/${id}`, { name, description })
            const index = projects.value.findIndex(p => p.id === id)
            if (index !== -1) projects.value[index] = response.data
            currentProject.value = response.data
            return response.data
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const deleteProject = async (id) => {
        try {
            const response = await api.delete(`/projects/${id}`)
            projects.value = projects.value.filter(p => p.id !== id)
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const createTask = async (title, description, projectId, status = 'todo', assignedTo = null, dueDate = null) => {
        try {
            const response = await api.post('/tasks', {
            title,
            description,
            project_id: projectId,
            status,
            user_id: assignedTo,
            due_date: dueDate
            })
            tasks.value.push(response.data)
            return response.data
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const updateTask = async (id, updates) => {
        try {
            const response = await api.patch(`/tasks/${id}`, updates)
            const index = tasks.value.findIndex(t => t.id === id)
            if(index !== -1) tasks.value[index] = response.data
            return response.data
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const deleteTask = async (id) => {
        try {
            await api.delete(`/tasks/${id}`)
            tasks.value = tasks.value.filter(t => t.id !== id)
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const addMember = async (projectId, email, role = 'member') => {
        try {
            const response = await api.post(`/projects/${projectId}/members`, { email, role })
            return response.data
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const removeMember = async (projectId, userId) => {
        try {
            await api.delete(`/projects/${projectId}/members/${userId}`)
        } catch (error) {
            throw error.response?.data || error
        }
    }

    const leaveProject = async (projectId) => {
        try {
            await api.post(`/projects/${projectId}/leave`)
        } catch (error) {
            throw error.response?.data || error
        }
    }

    return {
        projects,
        currentProject,
        tasks,
        loading,
        fetchProjects,
        createProject,
        getProject,
        updateProject,
        deleteProject,
        createTask,
        updateTask,
        deleteTask,
        addMember,
        removeMember, 
        leaveProject
    }

})