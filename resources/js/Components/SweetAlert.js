import Swal from 'sweetalert2'

// Toast configuration
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  },
  customClass: {
    popup: 'swal-toast-popup',
    title: 'swal-toast-title',
    content: 'swal-toast-content'
  }
})

// Success toast
export const showSuccessToast = (title, message = '') => {
  return Toast.fire({
    icon: 'success',
    title: title,
    text: message,
    background: '#f0f9ff',
    color: '#1e40af',
    iconColor: '#10b981'
  })
}

// Error toast
export const showErrorToast = (title, message = '') => {
  return Toast.fire({
    icon: 'error',
    title: title,
    text: message,
    background: '#fef2f2',
    color: '#dc2626',
    iconColor: '#ef4444',
    timer: 6000 // Longer for errors
  })
}

// Warning toast
export const showWarningToast = (title, message = '') => {
  return Toast.fire({
    icon: 'warning',
    title: title,
    text: message,
    background: '#fffbeb',
    color: '#d97706',
    iconColor: '#f59e0b'
  })
}

// Info toast
export const showInfoToast = (title, message = '') => {
  return Toast.fire({
    icon: 'info',
    title: title,
    text: message,
    background: '#f0f9ff',
    color: '#2563eb',
    iconColor: '#3b82f6'
  })
}

// Confirmation dialog
export const showConfirmDialog = (options = {}) => {
  const defaultOptions = {
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    reverseButtons: true,
    customClass: {
      popup: 'swal-confirm-popup',
      title: 'swal-confirm-title',
      content: 'swal-confirm-content',
      confirmButton: 'swal-confirm-button',
      cancelButton: 'swal-cancel-button'
    }
  }

  return Swal.fire({
    ...defaultOptions,
    ...options
  })
}

// Loading dialog
export const showLoadingDialog = (title = 'Processing...', message = 'Please wait') => {
  return Swal.fire({
    title: title,
    text: message,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading()
    },
    customClass: {
      popup: 'swal-loading-popup'
    }
  })
}

// Close any open SweetAlert
export const closeSweetAlert = () => {
  Swal.close()
}

// Success confirmation after action
export const showActionSuccess = (title, message = '', options = {}) => {
  return Swal.fire({
    icon: 'success',
    title: title,
    text: message,
    confirmButtonColor: '#10b981',
    confirmButtonText: 'Great!',
    timer: 3000,
    timerProgressBar: true,
    customClass: {
      popup: 'swal-success-popup',
      title: 'swal-success-title',
      confirmButton: 'swal-success-button'
    },
    ...options
  })
}

// Error confirmation after action
export const showActionError = (title, message = '', options = {}) => {
  return Swal.fire({
    icon: 'error',
    title: title,
    text: message,
    confirmButtonColor: '#dc2626',
    confirmButtonText: 'OK',
    customClass: {
      popup: 'swal-error-popup',
      title: 'swal-error-title',
      confirmButton: 'swal-error-button'
    },
    ...options
  })
}

export default {
  showSuccessToast,
  showErrorToast,
  showWarningToast,
  showInfoToast,
  showConfirmDialog,
  showLoadingDialog,
  closeSweetAlert,
  showActionSuccess,
  showActionError
}
