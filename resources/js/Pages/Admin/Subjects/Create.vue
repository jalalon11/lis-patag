<template>
  <AdminLayout page-title="Add New Subject">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Add New Subject</h2>
        <p class="text-muted">Create a new subject for the curriculum</p>
      </div>
      <div class="col-md-4 text-end">
        <Link 
          href="/admin/subjects" 
          class="btn btn-outline-secondary"
        >
          <i class="fas fa-arrow-left me-2"></i>
          Back to Subjects
        </Link>
      </div>
    </div>

    <!-- Create Form -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-plus me-2"></i>
              Subject Information
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="submit">
              <div class="row">
                <!-- Subject Code -->
                <div class="col-md-4 mb-3">
                  <label for="subject_code" class="form-label fw-semibold">Subject Code <span class="text-danger">*</span></label>
                  <input
                    id="subject_code"
                    v-model="form.subject_code"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.subject_code }"
                    placeholder="e.g., MATH101"
                    required
                  />
                  <div v-if="form.errors.subject_code" class="invalid-feedback">
                    {{ form.errors.subject_code }}
                  </div>
                </div>

                <!-- Subject Name -->
                <div class="col-md-8 mb-3">
                  <label for="subject_name" class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
                  <input
                    id="subject_name"
                    v-model="form.subject_name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.subject_name }"
                    placeholder="e.g., Mathematics"
                    required
                  />
                  <div v-if="form.errors.subject_name" class="invalid-feedback">
                    {{ form.errors.subject_name }}
                  </div>
                </div>

                <!-- Description -->
                <div class="col-12 mb-3">
                  <label for="description" class="form-label fw-semibold">Description</label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.description }"
                    rows="3"
                    placeholder="Enter subject description..."
                  ></textarea>
                  <div v-if="form.errors.description" class="invalid-feedback">
                    {{ form.errors.description }}
                  </div>
                </div>

                <!-- Grade Levels -->
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Grade Levels <span class="text-danger">*</span></label>
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
                          />
                          <label :for="`grade_${grade.replace(' ', '_')}`" class="form-check-label">
                            {{ grade }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="form.errors.grade_levels" class="text-danger small mt-1">
                    {{ form.errors.grade_levels }}
                  </div>
                </div>

                <!-- Units and Type -->
                <div class="col-md-6 mb-3">
                  <div class="row">
                    <div class="col-6">
                      <label for="units" class="form-label fw-semibold">Units <span class="text-danger">*</span></label>
                      <input
                        id="units"
                        v-model="form.units"
                        type="number"
                        min="1"
                        max="10"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.units }"
                        required
                      />
                      <div v-if="form.errors.units" class="invalid-feedback">
                        {{ form.errors.units }}
                      </div>
                    </div>
                    <div class="col-6">
                      <label for="subject_type" class="form-label fw-semibold">Subject Type <span class="text-danger">*</span></label>
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
                      <div v-if="form.errors.subject_type" class="invalid-feedback">
                        {{ form.errors.subject_type }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Status -->
                <div class="col-12 mb-4">
                  <div class="form-check form-switch">
                    <input
                      id="is_active"
                      v-model="form.is_active"
                      type="checkbox"
                      class="form-check-input"
                    />
                    <label for="is_active" class="form-check-label fw-semibold">
                      Active Subject
                    </label>
                    <small class="text-muted d-block">Active subjects are available for assignment to sections</small>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="row">
                <div class="col-12">
                  <div class="d-flex justify-content-end gap-2">
                    <Link 
                      href="/admin/subjects" 
                      class="btn btn-outline-secondary"
                    >
                      Cancel
                    </Link>
                    <button
                      type="submit"
                      class="btn btn-primary"
                      :disabled="form.processing"
                    >
                      <LoadingSpinner
                        v-if="form.processing"
                        :show="true"
                        :size="16"
                        :border="2"
                        class="me-2 d-inline-block"
                      />
                      <i v-else class="fas fa-save me-2"></i>
                      {{ form.processing ? 'Creating...' : 'Create Subject' }}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

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
      this.form.post('/admin/subjects')
    }
  }
}
</script>

<style scoped>
.form-label {
  color: var(--text-dark);
}

.form-control:focus,
.form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.btn-primary {
  background: linear-gradient(45deg, var(--primary-color), #1e3d6f);
  border: none;
  font-weight: 600;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #1e3d6f, var(--primary-color));
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card {
  transition: transform 0.2s ease-in-out;
}

.border-danger {
  border-color: #dc3545 !important;
}
</style>
