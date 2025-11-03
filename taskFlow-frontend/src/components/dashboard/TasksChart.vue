<template>
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <!-- Header -->
    <div class="px-4 lg:px-6 py-3.5 border-b border-gray-200 bg-gray-50">
      <h3 class="text-sm font-semibold text-gray-900">Mis Tareas</h3>
    </div>

    <!-- Chart Content -->
    <div class="p-6">
      <div v-if="hasData" class="flex flex-col lg:flex-row items-center gap-6">
        <!-- Chart -->
        <div class="w-full lg:w-1/2 max-w-[280px]">
          <canvas ref="chartCanvas"></canvas>
        </div>

        <!-- Stats -->
        <div class="flex-1 w-full space-y-3">
          <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 border border-blue-100">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-blue-500"></div>
              <span class="text-sm font-medium text-gray-700">Por Hacer</span>
            </div>
            <span class="text-lg font-semibold text-blue-600">{{ data.todo }}</span>
          </div>

          <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-50 border border-yellow-100">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
              <span class="text-sm font-medium text-gray-700">En Progreso</span>
            </div>
            <span class="text-lg font-semibold text-yellow-600">{{ data.in_progress }}</span>
          </div>

          <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 border border-green-100">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-green-500"></div>
              <span class="text-sm font-medium text-gray-700">Completadas</span>
            </div>
            <span class="text-lg font-semibold text-green-600">{{ data.done }}</span>
          </div>

          <!-- Total -->
          <div class="pt-3 mt-3 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-900">Total de Tareas</span>
              <span class="text-xl font-bold text-gray-900 pr-3">{{ totalTasks }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="py-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">No hay tareas asignadas</p>
        <p class="text-xs text-gray-500">Las tareas aparecerán aquí cuando sean asignadas</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  data: {
    type: Object,
    required: true,
    default: () => ({ todo: 0, in_progress: 0, done: 0 })
  }
})

const chartCanvas = ref(null)
let chartInstance = null

const totalTasks = computed(() => {
  return props.data.todo + props.data.in_progress + props.data.done
})

const hasData = computed(() => {
  return totalTasks.value > 0
})

const createChart = () => {
  if (!hasData.value || !chartCanvas.value) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  const ctx = chartCanvas.value.getContext('2d')
  chartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Por Hacer', 'En Progreso', 'Completadas'],
      datasets: [{
        data: [props.data.todo, props.data.in_progress, props.data.done],
        backgroundColor: [
          'rgb(59, 130, 246)',
          'rgb(251, 191, 36)',
          'rgb(34, 197, 94)'
        ],
        borderWidth: 0,
        hoverOffset: 8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      cutout: '70%',
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: {
            size: 13,
            weight: '600'
          },
          bodyFont: {
            size: 14,
            weight: '700'
          },
          displayColors: false,
          callbacks: {
            label: function(context) {
              const percentage = ((context.parsed / totalTasks.value) * 100).toFixed(1)
              return `${context.parsed} tareas (${percentage}%)`
            }
          }
        }
      },
      animation: {
        animateRotate: true,
        animateScale: true,
        duration: 800,
        easing: 'easeInOutQuart'
      }
    }
  })
}

onMounted(() => {
  if (hasData.value) {
    createChart()
  }
})

watch(() => props.data, () => {
  createChart()
}, { deep: true })
</script>