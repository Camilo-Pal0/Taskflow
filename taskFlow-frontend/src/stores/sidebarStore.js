// @/stores/sidebarStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSidebarStore = defineStore('sidebar', () => {
  const isCollapsed = ref(false)
  
  const toggle = () => {
    isCollapsed.value = !isCollapsed.value
  }
  
  return { isCollapsed, toggle }
})