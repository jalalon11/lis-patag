<template>
  <AdminLayout page-title="Add New School Year">
    <div class="container-fluid py-3">
      <!-- Form Card -->
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <!-- Action Buttons -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-primary mb-0">
                  <i class="fas fa-calendar me-2"></i> Add New School Year
                </h4>
                <Link href="/admin/school-years" class="btn btn-outline-secondary btn-sm px-3">
                  <i class="fas fa-arrow-left me-1"></i> Back to School Years
                </Link>
              </div>

              <form @submit.prevent="submit">
                <!-- Basic Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-info-circle me-1"></i> Basic Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="year_name"
                          v-model="form.year_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': errors.year_name }"
                          placeholder="Year Name"
                          required
                        />
                        <label for="year_name" class="form-label">
                          Year Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="errors.year_name" class="invalid-feedback">
                          {{ errors.year_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="description"
                          v-model="form.description"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': errors.description }"
                          placeholder="Description"
                        />
                        <label for="description" class="form-label">
                          Description
                        </label>
                        <div v-if="errors.description" class="invalid-feedback">
                          {{ errors.description }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="start_date"
                          v-model="form.start_date"
                          type="date"
                          class="form-control"
                          :class="{ 'is-invalid': errors.start_date }"
                          placeholder="Start Date"
                          required
                        />
                        <label for="start_date" class="form-label">
                          Start Date <span class="text-danger">*</span>
                        </label>
                        <div v-if="errors.start_date" class="invalid-feedback">
                          {{ errors.start_date }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="end_date"
                          v-model="form.end_date"
                          type="date"
                          class="form-control"
                          :class="{ 'is-invalid': errors.end_date }"
                          placeholder="End Date"
                          required
                        />
                        <label for="end_date" class="form-label">
                          End Date <span class="text-danger">*</span>
                        </label>
                        <div v-if="errors.end_date" class="invalid-feedback">
                          {{ errors.end_date }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Status Details -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-toggle-on me-1"></i> Status
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6 d-flex align-items-center">
                      <div class="form-check form-switch">
                        <input
                          id="is_current"
                          v-model="form.is_current"
                          type="checkbox"
                          class="form-check-input"
                          :class="{ 'is-invalid': errors.is_current }"
                        />
                        <label for="is_current" class="form-check-label fw-semibold">
                          Current School Year
                        </label>
                        <small class="text-muted d-block">Set as the current school year (unsets others)</small>
                        <div v-if="errors.is_current" class="invalid-feedback">
                          {{ errors.is_current }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                      <div class="form-check form-switch">
                        <input
                          id="is_active"
                          v-model="form.is_active"
                          type="checkbox"
                          class="form-check-input"
                          :class="{ 'is-invalid': errors.is_active }"
                        />
                        <label for="is_active" class="form-check-label fw-semibold">
                          Active
                        </label>
                        <small class="text-muted d-block">Active school years are available for use</small>
                        <div v-if="errors.is_active" class="invalid-feedback">
                          {{ errors.is_active }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/school-years" class="btn btn-outline-secondary btn-sm px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-sm px-4"
                    :disabled="form.processing"
                    aria-label="Create school year"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      {{ form.processing ? 'Creating...' : 'Create School Year' }}
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

<script setup>
import { reactive } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js';

const form = useForm({
  year_name: '',
  start_date: '',
  end_date: '',
  is_current: false,
  is_active: true,
  description: ''
});

const errors = reactive({});

const submit = () => {
  form.post('/admin/school-years', {
    onSuccess: () => {
      showSuccessToast('School Year Created!', `${form.year_name} has been added successfully.`);
      Object.keys(errors).forEach(key => delete errors[key]);
    },
    onError: (err) => {
      Object.assign(errors, err);
      const firstError = Object.values(err)[0];
      showErrorToast('Creation Failed', firstError || 'Please check the form and try again.');
    }
  });
};
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
  animation: fadeIn 0.3s ease-in-out;
}

.form-control, .form-select {
  border-radius: 6px;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
}

.form-control:focus, .form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.form-floating > label {
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  color: var(--text-muted);
}

.form-floating > .form-control, .form-floating > .form-select {
  height: calc(3rem + 2px);
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
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
}
</style>