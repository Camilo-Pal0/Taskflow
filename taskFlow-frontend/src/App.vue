<template>
  <router-view />
  <Toast ref="toastRef" />
  <ConfirmModal ref="confirmRef" />
</template>

<script setup>
import { ref, onMounted, getCurrentInstance } from 'vue'
import Toast from './components/common/Toast.vue'
import ConfirmModal from './components/common/ConfirmModal.vue'

const toastRef = ref(null)
const confirmRef = ref(null)
const instance = getCurrentInstance()

onMounted(() => {
  if (instance) {
    if (toastRef.value) {
      instance.appContext.config.globalProperties.$toast = toastRef.value
    }
    if (confirmRef.value) {
      instance.appContext.config.globalProperties.$confirm = confirmRef.value
    }
  }
})
</script>