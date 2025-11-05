import axios from "axios";

const API_URL = 'https://taskflow-api-5e80.onrender.com/api';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Incluir token en cada request
api.interceptors.request.use((config)=>{
    const token = localStorage.getItem('token');
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
}, (error) => Promise.reject(error))

// Manejar errores globales
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401){
            localStorage.removeItem('token');
            window.location = '/login';
        };
        return Promise.reject(error);
    }
)

export default api