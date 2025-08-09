<template>
  <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="row justify-content-center w-100">
      <div class="col-lg-4 col-md-6 col-sm-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <!-- Header -->
            <div class="text-center mb-4">
              <div class="school-logo mb-3">
                <i class="fas fa-school text-primary" style="font-size: 2.5rem;"></i>
              </div>
              <h1 class="h4 fw-bold text-dark">Patag Elementary LIS</h1>
              <p class="text-muted small">Sign in to your administrator account</p>
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
                    aria-describedby="emailHelp"
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
                    aria-describedby="passwordHelp"
                  />
                  <label for="password" class="form-label">
                    <i class="fas fa-lock me-1 text-primary"></i>
                    Password <span class="text-danger">*</span>
                  </label>
                  <button
                    type="button"
                    class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
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
                    aria-label="Remember me"
                  />
                  <label class="form-check-label small text-muted" for="remember">
                    Remember me
                  </label>
                </div>
                <Link
                  v-if="canResetPassword"
                  href="/forgot-password"
                  class="text-primary small text-decoration-none"
                  aria-label="Forgot your password?"
                >
                  Forgot Password?
                </Link>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button
                  type="submit"
                  class="btn btn-primary btn-sm px-4"
                  :disabled="form.processing"
                  aria-label="Sign in"
                >
                  <span class="d-flex align-items-center justify-content-center">
                    <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                    {{ form.processing ? 'Signing In...' : 'Sign In' }}
                  </span>
                </button>
              </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-3 pt-3 border-top">
              <small class="text-muted">
                © {{ new Date().getFullYear() }} Patag Elementary School
              </small>
            </div>
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
          const firstError = Object.values(errors)[0] || 'Invalid email or password. Please try again.'
          showErrorToast('Login Failed', firstError)
          this.form.errors.general = firstError
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
  --primary-color: #2c5aa0;
  --primary-hover: #3b77c9;
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
  border-radius: 8px;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  animation: fadeIn 0.3s ease-in-out;
}

.form-control {
  border-radius: 6px;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.form-floating > label {
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  color: var(--text-muted);
}

.form-floating > .form-control {
  height: calc(3rem + 2px);
}

.btn-primary {
  background: var(--primary-color);
  border: none;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-primary:disabled {
  background: var(--primary-color);
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-outline-secondary {
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-outline-secondary:hover {
  transform: translateY(-1px);
}

.alert-danger {
  background-color: rgba(220, 53, 69, 0.1);
  border-radius: 6px;
  font-size: 0.85rem;
  padding: 0.75rem 1rem;
}

.school-logo {
  transition: transform 0.3s ease-in-out;
}

.school-logo:hover {
  transform: scale(1.1);
}

.form-check-label {
  font-size: 0.85rem;
}

.border-top {
  border-color: rgba(0, 0, 0, 0.1) !important;
}

.fa-spinner.fa-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 576px) {
  .card-body {
    padding: 1.5rem;
  }

  .btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
  }

  .school-logo i {
    font-size: 2rem;
  }
}
</style>