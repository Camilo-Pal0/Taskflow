<template>
  <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-3">
      <!-- Icon Container -->
      <div :class="[
        'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0',
        iconBgColor
      ]">
        <component :is="iconComponent" :class="['w-5 h-5', iconColor]" />
      </div>

      <!-- Content -->
      <div class="min-w-0 flex-1">
        <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-0.5">
          {{ title }}
        </p>
        <div class="flex items-baseline gap-2">
          <p class="text-2xl font-semibold text-gray-900">{{ value }}</p>
          <span v-if="subtitle" class="text-xs text-gray-500">{{ subtitle }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'default',
    validator: (value) => ['projects', 'tasks', 'completed', 'overdue', 'default'].includes(value)
  }
})

// Colores según el tipo
const iconBgColor = computed(() => {
  const colors = {
    'projects': 'bg-blue-100',
    'tasks': 'bg-purple-100',
    'completed': 'bg-green-100',
    'overdue': 'bg-red-100',
    'default': 'bg-gray-100'
  }
  return colors[props.type]
})

const iconColor = computed(() => {
  const colors = {
    'projects': 'text-blue-600',
    'tasks': 'text-purple-600',
    'completed': 'text-green-600',
    'overdue': 'text-red-600',
    'default': 'text-gray-600'
  }
  return colors[props.type]
})

// Iconos SVG según el tipo
const iconComponent = computed(() => {
  const icons = {
    'projects': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z' 
      })
    ]),
    'tasks': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' 
      })
    ]),
    'completed': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' 
      })
    ]),
    'overdue': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' 
      })
    ]),
    'default': (props) => h('svg', { ...props, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' 
      })
    ])
  }
  return icons[props.type] || icons.default
})
</script>