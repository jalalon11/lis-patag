<template>
  <div class="grade-weighted-components">
    <!-- Ultra Compact Header with Weight Distribution -->
    <div class="d-flex justify-content-between align-items-center mb-1">
      <div class="d-flex align-items-center">
        <span class="small fw-bold text-secondary me-2">Weight:</span>
        <div class="weight-indicator d-flex align-items-center">
          <div class="progress me-1" style="width: 40px; height: 4px;">
            <div 
              class="progress-bar" 
              :class="{
                'bg-success': totalWeight === 100,
                'bg-warning': totalWeight < 100,
                'bg-danger': totalWeight > 100
              }"
              :style="{ width: `${Math.min(totalWeight, 100)}%` }"
              role="progressbar"
            ></div>
          </div>
          <span 
            class="badge rounded-pill fs-micro" 
            :class="{
              'bg-success': totalWeight === 100,
              'bg-warning': totalWeight < 100,
              'bg-danger': totalWeight > 100
            }"
          >
            {{ totalWeight }}%
          </span>
        </div>
      </div>
      <button 
        type="button" 
        class="btn btn-xs btn-outline-primary py-0 px-1" 
        @click="addComponent"
        :disabled="totalWeight === 100"
        title="Add component"
      >
        <i class="fas fa-plus fa-xs"></i>
      </button>
    </div>

    <!-- Compact Components List -->
    <div class="components-list">
      <div 
        v-for="(component, index) in modelValue" 
        :key="component.id"
        class="component-item py-1 border-bottom border-light"
      >
        <div class="d-flex align-items-center gap-1">
          <!-- Component Name -->
          <div class="flex-grow-1">
            <input 
              type="text" 
              class="form-control form-control-sm py-1 px-2" 
              v-model="component.name"
              @input="updateComponents"
              placeholder="Component name"
            >
          </div>
          
          <!-- Weight Percentage -->
          <div class="weight-input">
            <div class="input-group input-group-sm">
              <input 
                type="number" 
                class="form-control py-1 px-2" 
                v-model.number="component.weight"
                @input="updateComponents"
                min="0"
                max="100"
                step="5"
                style="width: 60px"
              >
              <span class="input-group-text py-1">%</span>
            </div>
          </div>
          
          <!-- Actions -->
          <div>
            <button 
              type="button" 
              class="btn btn-xs btn-link text-danger p-0"
              @click="removeComponent(index)"
              :disabled="modelValue.length <= 1"
              title="Remove component"
            >
              <i class="fas fa-times fa-xs"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="modelValue.length === 0" class="text-center py-1 bg-light rounded small fs-micro">
      <i class="fas fa-clipboard-list text-muted me-1"></i>
      <span>No components added</span>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'GradeWeightedComponents',
  props: {
    modelValue: {
      type: Array,
      required: true
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const totalWeight = computed(() => {
      return props.modelValue.reduce((sum, component) => sum + (component.weight || 0), 0)
    })

    const updateComponents = () => {
      emit('update:modelValue', [...props.modelValue])
    }

    const addComponent = () => {
      const newComponents = [...props.modelValue]
      newComponents.push({
        id: `component_${Date.now()}`,
        name: '',
        weight: 0
      })
      emit('update:modelValue', newComponents)
    }

    const removeComponent = (index) => {
      if (props.modelValue.length <= 1) return
      
      const newComponents = [...props.modelValue]
      newComponents.splice(index, 1)
      emit('update:modelValue', newComponents)
    }

    return {
      totalWeight,
      updateComponents,
      addComponent,
      removeComponent
    }
  }
}
</script>

<style scoped>
.grade-weighted-components {
  font-size: 0.9rem;
}

.component-item {
  transition: all 0.2s ease;
}

.component-item:last-child {
  border-bottom: none !important;
}

.form-control:focus, .form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.1rem rgba(44, 90, 160, 0.1);
}

.progress-bar {
  transition: width 0.3s ease;
}

.badge {
  padding: 0.15rem 0.35rem;
}

.fs-micro {
  font-size: 0.65rem;
}

.btn-xs {
  font-size: 0.7rem;
  line-height: 1;
}

.weight-indicator {
  background-color: rgba(0,0,0,0.03);
  border-radius: 10px;
  padding: 2px 4px;
}

.form-control {
  font-size: 0.85rem;
}

.input-group-text {
  font-size: 0.75rem;
}
</style>