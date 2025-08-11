<template>
  <AdminLayout page-title="Add New Subject">
    <div class="container-fluid py-3">
      <!-- Form Card -->
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <form @submit.prevent="submit">
                <!-- Basic Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-book me-1"></i> Basic Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="subject_code"
                          v-model="form.subject_code"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.subject_code }"
                          placeholder="Subject Code"
                          required
                        />
                        <label for="subject_code" class="form-label">
                          Subject Code <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.subject_code" class="invalid-feedback">
                          {{ form.errors.subject_code }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="subject_name"
                          v-model="form.subject_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.subject_name }"
                          placeholder="Subject Name"
                          required
                        />
                        <label for="subject_name" class="form-label">
                          Subject Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.subject_name" class="invalid-feedback">
                          {{ form.errors.subject_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea
                          id="description"
                          v-model="form.description"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.description }"
                          placeholder="Description"
                          style="height: 100px"
                        ></textarea>
                        <label for="description" class="form-label">
                          Description
                        </label>
                        <div v-if="form.errors.description" class="invalid-feedback">
                          {{ form.errors.description }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Subject Details -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-graduation-cap me-1"></i> Subject Details
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="units"
                          v-model="form.units"
                          type="number"
                          min="1"
                          max="10"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.units }"
                          placeholder="Units"
                          required
                        />
                        <label for="units" class="form-label">
                          Units <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.units" class="invalid-feedback">
                          {{ form.errors.units }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="subject_type"
                          v-model="form.subject_type"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.subject_type }"
                          required
                        >
                          <option value="">Select Type</option>
                          <option value="Core">Core</option>
                          <option value="Elective">Elective</option>
                          <option value="Special">Special</option>
                        </select>
                        <label for="subject_type" class="form-label">
                          Subject Type <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.subject_type" class="invalid-feedback">
                          {{ form.errors.subject_type }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label class="fw-semibold mb-2">Grade Levels <span class="text-danger">*</span></label>
                      <div class="border rounded p-3" :class="{ 'border-danger': form.errors.grade_levels }">
                        <div class="row">
                          <div v-for="grade in gradeLevels" :key="grade" class="col-md-6 mb-2">
                            <div class="form-check">
                              <input
                                :id="`grade_${grade.replace(' ', '_')}`"
                                v-model="form.grade_levels"
                                :value="grade"
                                type="checkbox"
                                class="form-check-input"
                                :class="{ 'is-invalid': form.errors.grade_levels }"
                              />
                              <label :for="`grade_${grade.replace(' ', '_')}`" class="form-check-label">
                                {{ grade }}
                              </label>
                            </div>
                          </div>
                        </div>
                        <div v-if="form.errors.grade_levels" class="invalid-feedback d-block">
                          {{ form.errors.grade_levels }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-check form-switch">
                        <input
                          id="is_active"
                          v-model="form.is_active"
                          type="checkbox"
                          class="form-check-input"
                          :class="{ 'is-invalid': form.errors.is_active }"
                        />
                        <label for="is_active" class="form-check-label fw-semibold">
                          Active Subject
                        </label>
                        <small class="text-muted d-block">Active subjects are available for assignment to sections</small>
                        <div v-if="form.errors.is_active" class="invalid-feedback">
                          {{ form.errors.is_active }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/subjects" class="btn btn-outline-secondary btn-sm px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-sm px-4"
                    :disabled="form.processing"
                    aria-label="Create subject"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      {{ form.processing ? 'Creating...' : 'Create Subject' }}
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
import { Link, useForm } from '@inertiajs/vue3'
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js'

export default {
  name: 'SubjectsCreate',
  components: {
    AdminLayout,
    Link
  },
  data() {
    return {
      gradeLevels: ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
      form: useForm({
        subject_code: '',
        subject_name: '',
        description: '',
        grade_levels: [],
        units: 1,
        subject_type: '',
        is_active: true
      })
    }
  },
  methods: {
    submit() {
      this.form.post('/admin/subjects', {
        onSuccess: () => {
          showSuccessToast('Subject Created!', `${this.form.subject_name} has been added successfully.`)
          router.visit('/admin/subjects')
        },
        onError: (errors) => {
          const firstError = Object.values(errors)[0]
          showErrorToast('Creation Failed', firstError || 'Please check the form and try again.')
        }
      })
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

.border-danger {
  border-color: #dc3545 !important;
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