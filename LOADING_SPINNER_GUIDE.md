# 🎨 Loading Spinner System Guide

This guide explains how to use the custom loading spinner system implemented throughout the Patag Elementary School LIS.

## 🌟 Features

- **Customizable**: Size, color, speed, and border width
- **Flexible**: Can be used as overlay, inline, or in buttons
- **Accessible**: Includes ARIA attributes for screen readers
- **Responsive**: Works on all screen sizes
- **Professional**: Modern conic gradient design
- **Dark Theme Support**: Automatically adapts to dark themes

## 📦 Components

### 1. LoadingSpinner.vue
Main loading spinner component with full customization options.

### 2. LoadingPage.vue
Pre-configured loading component for full-page loading states.

### 3. useLoading.js
Composable for managing loading states throughout the application.

## 🚀 Usage Examples

### Basic Usage

```vue
<template>
  <!-- Simple loading spinner -->
  <LoadingSpinner :show="true" />
  
  <!-- With custom text -->
  <LoadingSpinner :show="loading" text="Loading data..." />
  
  <!-- As overlay -->
  <LoadingSpinner :show="loading" overlay text="Processing..." />
</template>
```

### Button Loading States

```vue
<template>
  <button type="submit" :disabled="form.processing">
    <!-- Small spinner for buttons -->
    <LoadingSpinner 
      v-if="form.processing" 
      :show="true" 
      :size="16" 
      :border="2"
      class="me-2 d-inline-block"
    />
    <i v-else class="fas fa-save me-2"></i>
    {{ form.processing ? 'Saving...' : 'Save' }}
  </button>
</template>
```

### Full Page Loading

```vue
<template>
  <div>
    <!-- Your content -->
    <div v-if="!loading">
      <!-- Page content here -->
    </div>
    
    <!-- Loading page -->
    <LoadingPage v-else text="Loading dashboard..." />
  </div>
</template>
```

### Using the Composable

```vue
<script>
import { useLoading } from '@/Composables/useLoading'

export default {
  setup() {
    const { 
      showGlobalLoading, 
      hideGlobalLoading, 
      withLoading,
      getLoadingState,
      setLoadingState 
    } = useLoading()
    
    // Show global loading
    const loadData = async () => {
      showGlobalLoading('Fetching data...')
      try {
        await fetchData()
      } finally {
        hideGlobalLoading()
      }
    }
    
    // Use with async wrapper
    const saveData = async () => {
      await withLoading(async () => {
        await saveToServer()
      }, 'Saving data...')
    }
    
    // Named loading states
    const specificLoading = getLoadingState('dataFetch')
    
    const fetchSpecificData = async () => {
      setLoadingState('dataFetch', true, 'Loading...')
      try {
        await fetchData()
      } finally {
        setLoadingState('dataFetch', false)
      }
    }
    
    return {
      loadData,
      saveData,
      specificLoading,
      fetchSpecificData
    }
  }
}
</script>
```

## ⚙️ Configuration Options

### LoadingSpinner Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `show` | Boolean | `true` | Whether to show the spinner |
| `overlay` | Boolean | `false` | Show as full-screen overlay |
| `size` | Number | `60` | Spinner size in pixels |
| `border` | Number | `6` | Border width in pixels |
| `hue` | Number | `210` | Color hue (0-360) |
| `speed` | Number | `1` | Animation speed in seconds |
| `text` | String | `''` | Loading text to display |

### Color Customization

```vue
<!-- Blue spinner (default) -->
<LoadingSpinner :hue="210" />

<!-- Green spinner -->
<LoadingSpinner :hue="120" />

<!-- Red spinner -->
<LoadingSpinner :hue="0" />

<!-- Purple spinner -->
<LoadingSpinner :hue="270" />
```

### Size Variations

```vue
<!-- Small (for buttons) -->
<LoadingSpinner :size="20" :border="3" />

<!-- Medium (default) -->
<LoadingSpinner :size="60" :border="6" />

<!-- Large (for pages) -->
<LoadingSpinner :size="100" :border="10" />
```

## 🎯 Best Practices

### 1. Form Submissions
```vue
<template>
  <form @submit.prevent="submit">
    <!-- Form fields -->
    
    <button type="submit" :disabled="form.processing">
      <LoadingSpinner 
        v-if="form.processing" 
        :show="true" 
        :size="16" 
        :border="2"
        class="me-2 d-inline-block"
      />
      <i v-else class="fas fa-save me-2"></i>
      {{ form.processing ? 'Submitting...' : 'Submit' }}
    </button>
  </form>
</template>
```

### 2. Data Loading
```vue
<template>
  <div>
    <div v-if="!loading" class="data-container">
      <!-- Your data display -->
    </div>
    
    <LoadingSpinner 
      v-else 
      :show="true" 
      text="Loading data..." 
      class="my-5"
    />
  </div>
</template>
```

### 3. Global Loading States
```vue
<script>
export default {
  methods: {
    async performAction() {
      this.$loading.show('Processing request...')
      try {
        await this.apiCall()
      } finally {
        this.$loading.hide()
      }
    }
  }
}
</script>
```

## 🎨 Styling

### Custom CSS Classes

```css
/* Custom spinner container */
.custom-spinner-container {
  padding: 2rem;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 0.5rem;
  backdrop-filter: blur(4px);
}

/* Inline spinner for text */
.inline-spinner {
  display: inline-block;
  vertical-align: middle;
  margin: 0 0.5rem;
}

/* Button spinner */
.btn-spinner {
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
}
```

### Theme Integration

The spinner automatically adapts to your theme:

```css
/* Light theme (default) */
.loader {
  background: white;
}

.loader::before {
  background: white;
}

/* Dark theme */
@media (prefers-color-scheme: dark) {
  .loader {
    background: #333;
  }
  
  .loader::before {
    background: #333;
  }
}
```

## 🔧 Advanced Usage

### Conditional Loading States

```vue
<template>
  <div>
    <!-- Multiple loading states -->
    <LoadingSpinner 
      v-if="loadingUsers" 
      text="Loading users..." 
    />
    <LoadingSpinner 
      v-else-if="loadingReports" 
      text="Generating reports..." 
      :hue="120"
    />
    <div v-else>
      <!-- Content -->
    </div>
  </div>
</template>
```

### Progress Indication

```vue
<template>
  <div class="loading-container">
    <LoadingSpinner :show="true" />
    <div class="progress-text">
      Step {{ currentStep }} of {{ totalSteps }}
    </div>
  </div>
</template>
```

## 📱 Mobile Considerations

The spinner is fully responsive and works well on mobile devices:

```vue
<!-- Mobile-optimized sizes -->
<LoadingSpinner 
  :size="isMobile ? 40 : 60" 
  :border="isMobile ? 4 : 6"
  :text="isMobile ? 'Loading...' : 'Loading data, please wait...'"
/>
```

## ♿ Accessibility

The spinner includes proper ARIA attributes:

```html
<div aria-live="assertive" role="alert" class="loader"></div>
```

- `aria-live="assertive"`: Announces loading state changes
- `role="alert"`: Identifies as an important status update
- Screen reader friendly text descriptions

## 🚀 Performance Tips

1. **Lazy Loading**: Only show spinners when actually needed
2. **Debouncing**: Avoid showing spinners for very quick operations
3. **Cleanup**: Always hide spinners in error handlers
4. **Memory**: Use the composable for better state management

```javascript
// Good: Debounced loading
const debouncedLoad = debounce(async () => {
  showLoading()
  await loadData()
  hideLoading()
}, 300)

// Good: Proper cleanup
try {
  showLoading()
  await operation()
} catch (error) {
  handleError(error)
} finally {
  hideLoading() // Always hide
}
```

---

This loading spinner system provides a consistent, professional, and accessible loading experience throughout the Patag Elementary School LIS. 🎉
