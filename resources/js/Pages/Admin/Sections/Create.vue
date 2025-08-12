<template>
  <AdminLayout page-title="Add New Section">
    <div class="container-fluid py-3">
      <!-- Header Section -->
      <div class="row mb-4">
        <div class="col-md-8">
          <h2 class="fw-bold text-primary mb-2">
            <i class="fas fa-layer-group me-3"></i>
            Add New Section
          </h2>
          <p class="text-muted mb-0">Create a new class section for students</p>
        </div>
        <div class="col-md-4 text-end">
          <Link href="/admin/sections" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Sections
          </Link>
        </div>
      </div>

      <!-- Form Card -->
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
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
                          id="section_name"
                          v-model="form.section_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.section_name }"
                          placeholder="Section Name"
                          required
                        />
                        <label for="section_name" class="form-label">
                          Section Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.section_name" class="invalid-feedback">
                          {{ form.errors.section_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="grade_level"
                          v-model="form.grade_level"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.grade_level }"
                          required
                        >
                          <option value="">Select Grade Level</option>
                          <option value="Kindergarten">Kindergarten</option>
                          <option value="Grade 1">Grade 1</option>
                          <option value="Grade 2">Grade 2</option>
                          <option value="Grade 3">Grade 3</option>
                          <option value="Grade 4">Grade 4</option>
                          <option value="Grade 5">Grade 5</option>
                          <option value="Grade 6">Grade 6</option>
                        </select>
                        <label for="grade_level" class="form-label">
                          Grade Level <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.grade_level" class="invalid-feedback">
                          {{ form.errors.grade_level }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Section Details -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-cogs me-1"></i> Section Details
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="capacity"
                          v-model="form.capacity"
                          type="number"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.capacity }"
                          placeholder="Capacity"
                          min="1"
                          max="50"
                          required
                        />
                        <label for="capacity" class="form-label">
                          Capacity <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.capacity" class="invalid-feedback">
                          {{ form.errors.capacity }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="room_number"
                          v-model="form.room_number"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.room_number }"
                          placeholder="Room Number"
                        />
                        <label for="room_number" class="form-label">
                          Room Number
                        </label>
                        <div v-if="form.errors.room_number" class="invalid-feedback">
                          {{ form.errors.room_number }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Assignment Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-users me-1"></i> Assignment Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="school_year_id"
                          v-model="form.school_year_id"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.school_year_id }"
                          required
                        >
                          <option value="">Select School Year</option>
                          <option v-for="schoolYear in schoolYears" :key="schoolYear.id" :value="schoolYear.id">
                            {{ schoolYear.year_name }}
                          </option>
                        </select>
                        <label for="school_year_id" class="form-label">
                          School Year <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.school_year_id" class="invalid-feedback">
                          {{ form.errors.school_year_id }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="adviser_id"
                          v-model="form.adviser_id"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.adviser_id }"
                        >
                          <option value="">Select Adviser (Optional)</option>
                          <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                            {{ teacher.full_name }}
                          </option>
                        </select>
                        <label for="adviser_id" class="form-label">
                          Adviser
                        </label>
                        <div v-if="form.errors.adviser_id" class="invalid-feedback">
                          {{ form.errors.adviser_id }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-file-alt me-1"></i> Additional Information
                  </h5>
                  <div class="row g-3">
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

                <!-- Status -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-toggle-on me-1"></i> Status
                  </h5>
                  <div class="form-check form-switch">
                    <input
                      id="is_active"
                      v-model="form.is_active"
                      class="form-check-input"
                      type="checkbox"
                      role="switch"
                    />
                    <label class="form-check-label" for="is_active">
                      Active Section
                    </label>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                  <Link href="/admin/sections" class="btn btn-outline-secondary">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="form.processing"
                  >
                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status"></span>
                    <i v-else class="fas fa-save me-2"></i>
                    {{ form.processing ? 'Creating...' : 'Create Section' }}
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
  name: 'CreateSection',
  components: {
    AdminLayout,
    Link
  },
  props: {
    schoolYears: Array,
    teachers: Array
  },
  data() {
    return {
      form: useForm({
        section_name: '',
        grade_level: '',
        capacity: 40,
        room_number: '',
        school_year_id: '',
        adviser_id: '',
        description: '',
        is_active: true
      })
    }
  },
  methods: {
    submit() {
      this.form.post('/admin/sections', {
        onSuccess: () => {
          const sectionName = `${this.form.grade_level} - ${this.form.section_name}`
          showSuccessToast('Section Created!', `${sectionName} has been added successfully.`)
          router.visit('/admin/sections')
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

.form-control {
  border-radius: 6px;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.form-select {
  border-radius: 6px;
  font-size: 0.9rem;
}

.form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.btn-primary {
  background: var(--primary-color);
  border: none;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-primary:hover {
  background: var(--primary-hover);
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(44, 90, 160, 0.2);
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

.form-floating > label {
  color: var(--text-muted);
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select ~ label {
  color: var(--primary-color);
}

.text-primary {
  color: var(--primary-color) !important;
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.form-check-input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25);
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}

@media (max-width: 768px) {
  .container-fluid {
    padding: 1rem;
  }

  .card-body {
    padding: 1.5rem !important;
  }

  .btn {
    font-size: 0.875rem;
  }
}
</style>
