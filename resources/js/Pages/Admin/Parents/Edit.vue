<template>
  <AdminLayout page-title="Edit Parent/Guardian">
    <div class="container-fluid py-3">
      <!-- Form Card -->
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <form @submit.prevent="submit">
                <!-- Personal Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-user-circle me-1"></i> Personal Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-floating">
                        <input
                          id="name"
                          v-model="form.name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.name }"
                          placeholder="Full Name"
                          required
                          aria-describedby="nameHelp"
                        />
                        <label for="name" class="form-label">
                          Full Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.name" class="invalid-feedback">
                          {{ form.errors.name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <input
                          id="email"
                          v-model="form.email"
                          type="email"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.email }"
                          placeholder="Email Address"
                          required
                          aria-describedby="emailHelp"
                        />
                        <label for="email" class="form-label">
                          Email Address <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.email" class="invalid-feedback">
                          {{ form.errors.email }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <input
                          id="phone"
                          v-model="form.phone"
                          type="tel"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.phone }"
                          placeholder="Phone Number"
                          aria-describedby="phoneHelp"
                        />
                        <label for="phone" class="form-label">
                          Phone Number
                        </label>
                        <div v-if="form.errors.phone" class="invalid-feedback">
                          {{ form.errors.phone }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <select
                          id="relationship"
                          v-model="form.relationship"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.relationship }"
                          aria-describedby="relationshipHelp"
                        >
                          <option value="">Select relationship</option>
                          <option value="father">Father</option>
                          <option value="mother">Mother</option>
                          <option value="guardian">Guardian</option>
                          <option value="grandfather">Grandfather</option>
                          <option value="grandmother">Grandmother</option>
                          <option value="uncle">Uncle</option>
                          <option value="aunt">Aunt</option>
                          <option value="other">Other</option>
                        </select>
                        <label for="relationship" class="form-label">
                          Relationship
                        </label>
                        <div v-if="form.errors.relationship" class="invalid-feedback">
                          {{ form.errors.relationship }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea
                          id="address"
                          v-model="form.address"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.address }"
                          placeholder="Address"
                          rows="3"
                          aria-describedby="addressHelp"
                        ></textarea>
                        <label for="address" class="form-label">
                          Address
                        </label>
                        <div v-if="form.errors.address" class="invalid-feedback">
                          {{ form.errors.address }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Security Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-shield-alt me-1"></i> Security Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating position-relative">
                        <input
                          id="password"
                          v-model="form.password"
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.password }"
                          placeholder="New Password"
                          aria-describedby="passwordHelp"
                        />
                        <label for="password" class="form-label">
                          New Password <small class="text-muted">(Leave blank to keep current)</small>
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
                      <small class="text-muted mt-1 d-block small">
                        Minimum 8 characters (if updating)
                      </small>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="password_confirmation"
                          v-model="form.password_confirmation"
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.password_confirmation }"
                          placeholder="Confirm New Password"
                          aria-describedby="passwordConfirmHelp"
                        />
                        <label for="password_confirmation" class="form-label">
                          Confirm New Password
                        </label>
                        <div v-if="form.errors.password_confirmation" class="invalid-feedback">
                          {{ form.errors.password_confirmation }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Information Alert -->
                <div class="alert alert-info alert-dismissible fade show small mb-4" role="alert">
                  <i class="fas fa-info-circle me-2"></i>
                  Updated password (if provided) will be sent via email. The parent/guardian can change their password after login.
                  <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/parents" class="btn btn-outline-secondary btn-sm px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-sm px-4"
                    :disabled="form.processing"
                    aria-label="Update parent/guardian"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      <i v-else class="fas fa-save me-2"></i>
                      {{ form.processing ? 'Updating...' : 'Update Parent/Guardian' }}
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js'

export default {
  name: 'EditParent',
  components: {
    AdminLayout,
    Link
  },
  props: {
    parent: Object
  },
  data() {
    return {
      showPassword: false,
      form: useForm({
        name: this.parent.name || '',
        email: this.parent.email || '',
        phone: this.parent.phone || '',
        relationship: this.parent.relationship || '',
        address: this.parent.address || '',
        password: '',
        password_confirmation: ''
      })
    }
  },
  methods: {
    submit() {
      this.form.put(`/admin/parents/${this.parent.id}`, {
        onSuccess: () => {
          showSuccessToast('Parent Updated!', `${this.form.name} has been updated successfully.`)
          router.visit('/admin/parents')
        },
        onError: (errors) => {
          const firstError = Object.values(errors)[0]
          showErrorToast('Update Failed', firstError || 'Please check the form and try again.')
        },
        onFinish: () => {
          this.form.reset('password', 'password_confirmation')
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

.card {
  border-radius: 8px;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
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

.btn-outline-secondary, .btn-outline-info {
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-outline-secondary:hover, .btn-outline-info:hover {
  transform: translateY(-1px);
}

.alert-info {
  background-color: rgba(13, 202, 240, 0.1);
  border-radius: 6px;
  font-size: 0.85rem;
  padding: 0.75rem 1rem;
}

.breadcrumb {
  font-size: 0.85rem;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: "›";
  color: var(--text-muted);
  font-weight: 600;
}

.breadcrumb-item.active {
  color: var(--primary-color);
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

.card {
  animation: fadeIn 0.3s ease-in-out;
}

@media (max-width: 576px) {
  .card-body {
    padding: 1.5rem;
  }

  .btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
  }
}
</style>