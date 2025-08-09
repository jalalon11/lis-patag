import { ref, reactive } from 'vue'

// Global loading state
const globalLoading = ref(false)
const loadingStates = reactive({})

export function useLoading() {
  // Show global loading
  const showGlobalLoading = (text = 'Loading...') => {
    globalLoading.value = { show: true, text }
  }

  // Hide global loading
  const hideGlobalLoading = () => {
    globalLoading.value = false
  }

  // Create a named loading state
  const createLoadingState = (name, initialState = false) => {
    loadingStates[name] = ref(initialState)
    return loadingStates[name]
  }

  // Get a named loading state
  const getLoadingState = (name) => {
    if (!loadingStates[name]) {
      loadingStates[name] = ref(false)
    }
    return loadingStates[name]
  }

  // Set a named loading state
  const setLoadingState = (name, value, text = '') => {
    if (!loadingStates[name]) {
      loadingStates[name] = ref(false)
    }
    loadingStates[name].value = value ? { show: true, text } : false
  }

  // Loading wrapper for async operations
  const withLoading = async (operation, loadingText = 'Processing...', stateName = null) => {
    try {
      if (stateName) {
        setLoadingState(stateName, true, loadingText)
      } else {
        showGlobalLoading(loadingText)
      }
      
      const result = await operation()
      return result
    } catch (error) {
      throw error
    } finally {
      if (stateName) {
        setLoadingState(stateName, false)
      } else {
        hideGlobalLoading()
      }
    }
  }

  // Loading wrapper for form submissions
  const withFormLoading = async (form, operation, loadingText = 'Submitting...') => {
    try {
      form.processing = true
      const result = await operation()
      return result
    } catch (error) {
      throw error
    } finally {
      form.processing = false
    }
  }

  return {
    // Global loading
    globalLoading,
    showGlobalLoading,
    hideGlobalLoading,
    
    // Named loading states
    loadingStates,
    createLoadingState,
    getLoadingState,
    setLoadingState,
    
    // Utility functions
    withLoading,
    withFormLoading
  }
}

// Export for direct access
export { globalLoading, loadingStates }
