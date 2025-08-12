<template>
  <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="row justify-content-center w-100">
      <div class="col-lg-4 col-md-6 col-sm-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <!-- Header -->
            <div class="text-center mb-4">
              <div class="logo mb-3">
                <i class="fas fa-school text-primary" style="font-size: 2.5rem;"></i>
              </div>
              <h1 class="h4 fw-bold text-dark">Patal Elementary School</h1>
              <p class="text-muted small">Please sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit">
              <!-- Email Field -->
              <div class="mb-3">
                <div class="form-floating">
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.email }"
                    placeholder="Email Address"
                    required
                    autofocus
                  />
                  <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1 text-primary"></i>
                    Email Address <span class="text-danger">*</span>
                  </label>
                  <div v-if="form.errors.email" class="invalid-feedback">
                    {{ form.errors.email }}
                  </div>
                </div>
              </div>

              <!-- Password Field -->
              <div class="mb-3">
                <div class="form-floating position-relative">
                  <input
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.password }"
                    placeholder="Password"
                    required
                  />
                  <label for="password" class="form-label">
                    <i class="fas fa-lock me-1 text-primary"></i>
                    Password <span class="text-danger">*</span>
                  </label>
                  <button
                    type="button"
                    class="btn btn-text-secondary border-0 btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                    @click="togglePasswordVisibility"
                    aria-label="Toggle password visibility"
                  >
                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                  </button>
                  <div v-if="form.errors.password" class="invalid-feedback">
                    {{ form.errors.password }}
                  </div>
                </div>
              </div>

              <!-- Remember Me and Forgot Password -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input
                    id="remember"
                    v-model="form.remember"
                    type="checkbox"
                    class="form-check-input"
                  />
                  <label class="form-check-label small text-muted" for="remember">
                    Remember me
                  </label>
                </div>
                <Link
                  v-if="canResetPassword"
                  href="/forgot-password"
                  class="text-primary small text-decoration-none"
                >
                  Forgot Password?
                </Link>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button
                  type="submit"
                  class="btn btn-primary btn-md px-4"
                  :disabled="form.processing"
                >
                  <span class="d-flex align-items-center justify-content-center">
                    <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                    {{ form.processing ? 'Signing In...' : 'Sign In' }}
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3'
import { showErrorToast } from '@/Components/SweetAlert.js'

export default {
  name: 'Login',
  components: {
    Link
  },
  props: {
    canResetPassword: Boolean,
    status: String,
  },
  data() {
    return {
      showPassword: false,
      form: useForm({
        email: '',
        password: '',
        remember: false,
      })
    }
  },
  methods: {
    submit() {
      this.form.post('/login', {
        onError: (errors) => {
          const firstError = Object.values(errors)[0] || 'Invalid credentials. Please try again.'
          showErrorToast('Login Failed', firstError)
        },
        onFinish: () => {
          this.form.reset('password')
        }
      })
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    }
  }
}
</script>

<style scoped>
:root {
  --primary-color: #0d6efd;
  --primary-hover: #0b5ed7;
  --text-dark: #212529;
  --text-muted: #6c757d;
}

.min-vh-100 {
  min-height: 100vh;
}

.bg-light {
  background-color: #f8f9fa;
}

.card {
  border-radius: 12px;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  animation: fadeIn 0.4s ease-out;
}

.form-control {
  border-radius: 8px;
  font-size: 0.95rem;
  padding: 0.75rem 1rem;
  border: 1.5px solid #dee2e6;
  transition: all 0.2s ease-in-out;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
  transform: translateY(-1px);
}

.form-floating > label {
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  color: var(--text-muted);
}

.form-floating > .form-control {
  height: calc(3.2rem + 2px);
}

.btn-primary {
  padding: 0.6rem 1rem;
  font-weight: 500;
}


.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.btn-text-secondary {
  color: var(--text-muted);
  transition: color 0.2s ease-in-out;
}

.btn-text-secondary:hover {
  color: var(--primary-color);
}

.logo {
  transition: transform 0.3s ease-in-out;
}

.logo:hover {
  transform: scale(1.05);
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.text-primary {
  color: var(--primary-color) !important;
}

.text-primary:hover {
  color: var(--primary-hover) !important;
}

.fa-spinner.fa-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(20px) scale(0.95);
  }
  to { 
    opacity: 1; 
    transform: translateY(0) scale(1);
  }
}

@media (max-width: 576px) {
  .card-body {
    padding: 2rem 1.5rem;
  }

  .logo i {
    font-size: 2rem !important;
  }

  .h4 {
    font-size: 1.3rem;
  }
}

/* Focus states for accessibility */
.form-control:focus,
.form-check-input:focus,
.btn:focus {
  outline: none;
}

/* Invalid state styling */
.form-control.is-invalid {
  border-color: #dc3545;
}

.form-control.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
}
</style>