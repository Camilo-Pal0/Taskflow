import { createRouter, createMemoryHistory, createWebHashHistory } from "vue-router";
import { useAuthStore } from "../stores/authStore";

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/pages/Profile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: ()=> import('@/pages/Login.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/register',
        name: 'Register',
        component: ()=> import('@/pages/Register.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: ()=> import('@/pages/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/projects/:id',
        name: 'Project',
        component: ()=> import('@/pages/Project.vue'),
        meta: { requiresAuth: true }
    },
    {
    path: '/tasks',
    name: 'Tasks',
    component: () => import('@/pages/Tasks.vue'),
    meta: { requiresAuth: true }
    },
    {
    path: '/projects',
    name: 'Projects',
    component: () => import('@/pages/Projects.vue'),
    meta: { requiresAuth: true }
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login')
    } else if ((to.path === '/login' || to.path === '/register') && authStore.isAuthenticated) {
        next('/dashboard')
    } else {
        next()
    }
})

export default router; 