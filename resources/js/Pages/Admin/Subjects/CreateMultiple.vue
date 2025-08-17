<template>
  <AdminLayout page-title="Bulk Add Subjects">
    <div class="container-fluid py-3">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <!-- Header -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h4 class="fw-bold text-primary mb-1">
                    Bulk Add Subjects
                  </h4>
                  <p class="text-muted mb-0">Add multiple subjects at once using the table format</p>
                </div>
                <Link href="/admin/subjects/create" class="btn btn-outline-secondary btn-md">
                  <i class="fas fa-plus me-1"></i> Single Add
                </Link>
              </div>

              <form @submit.prevent="submit">
                <!-- Common Settings -->
                <div class="row mb-4 p-3 bg-light rounded">
                  <div class="col-md-6">
                    <div class="form-floating">
                      <select
                        id="common_grade_levels"
                        v-model="commonSettings.grade_levels"
                        class="form-select"
                        @change="applyGradeLevelToAll"
                      >
                        <option value="">Select Grade Level</option>
                        <option :value="0">Kindergarten</option>
                        <option v-for="grade in 6" :key="grade" :value="grade">
                          Grade {{ grade }}
                        </option>
                      </select>
                      <label for="common_grade_levels" class="form-label">
                        Apply Grade Level to All
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input
                        id="common_curriculum_year"
                        v-model="commonSettings.curriculum_year"
                        type="number"
                        :min="new Date().getFullYear() - 10"
                        :max="new Date().getFullYear() + 5"
                        class="form-control"
                        @change="applyCurriculumYearToAll"
                      />
                      <label for="common_curriculum_year" class="form-label">
                        Apply Curriculum Year to All
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="bg-primary text-white">
                      <tr>
                        <th style="width: 120px;">Subject Code <span class="text-danger">*</span></th>
                        <th style="width: 200px;">Subject Name <span class="text-danger">*</span></th>
                        <th style="width: 250px;">Description</th>
                        <th style="width: 130px;">Grade Level <span class="text-danger">*</span></th>
                        <th style="width: 80px;">Units <span class="text-danger">*</span></th>
                        <th style="width: 120px;">Type <span class="text-danger">*</span></th>
                        <th style="width: 120px;">Curriculum Year</th>
                        <th style="width: 80px;">Active</th>
                        <th style="width: 60px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(subject, index) in form.subjects" :key="index" :class="{ 'table-danger': hasRowErrors(index) }">
                        <td>
                          <input
                            v-model="subject.subject_code"
                            type="text"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.subject_code`] }"
                            placeholder="e.g., ENG1"
                            required
                          />
                          <div v-if="form.errors[`subjects.${index}.subject_code`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.subject_code`] }}
                          </div>
                        </td>
                        <td>
                          <input
                            v-model="subject.subject_name"
                            type="text"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.subject_name`] }"
                            placeholder="e.g., English 1"
                            required
                          />
                          <div v-if="form.errors[`subjects.${index}.subject_name`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.subject_name`] }}
                          </div>
                        </td>
                        <td>
                          <textarea
                            v-model="subject.description"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.description`] }"
                            placeholder="Optional description"
                            rows="1"
                          ></textarea>
                          <div v-if="form.errors[`subjects.${index}.description`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.description`] }}
                          </div>
                        </td>
                        <td>
                          <select
                            v-model.number="subject.grade_levels"
                            class="form-select form-select-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.grade_levels`] }"
                            required
                          >
                            <option value="">Select</option>
                            <option :value="0">Kindergarten</option>
                            <option v-for="grade in 12" :key="grade" :value="grade">
                              Grade {{ grade }}
                            </option>
                          </select>
                          <div v-if="form.errors[`subjects.${index}.grade_levels`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.grade_levels`] }}
                          </div>
                        </td>
                        <td>
                          <input
                            v-model="subject.units"
                            type="number"
                            min="1"
                            max="10"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.units`] }"
                            required
                          />
                          <div v-if="form.errors[`subjects.${index}.units`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.units`] }}
                          </div>
                        </td>
                        <td>
                          <select
                            v-model="subject.subject_type"
                            class="form-select form-select-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.subject_type`] }"
                            required
                          >
                            <option value="">Select</option>
                            <option value="Core">Core</option>
                            <option value="Elective">Elective</option>
                            <option value="Special">Special</option>
                          </select>
                          <div v-if="form.errors[`subjects.${index}.subject_type`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.subject_type`] }}
                          </div>
                        </td>
                        <td>
                          <input
                            v-model="subject.curriculum_year"
                            type="number"
                            :min="new Date().getFullYear() - 10"
                            :max="new Date().getFullYear() + 5"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors[`subjects.${index}.curriculum_year`] }"
                            required
                          />
                          <div v-if="form.errors[`subjects.${index}.curriculum_year`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.curriculum_year`] }}
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-switch">
                            <input
                              v-model="subject.is_active"
                              type="checkbox"
                              class="form-check-input"
                              :class="{ 'is-invalid': form.errors[`subjects.${index}.is_active`] }"
                            />
                          </div>
                          <div v-if="form.errors[`subjects.${index}.is_active`]" class="invalid-feedback">
                            {{ form.errors[`subjects.${index}.is_active`] }}
                          </div>
                        </td>
                        <td>
                          <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            @click="removeSubject(index)"
                            :disabled="form.subjects.length === 1"
                            title="Remove subject"
                          >
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Add Row Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <small class="text-muted">{{ form.subjects.length }} subject(s) to be created</small>
                  <button
                    type="button"
                    class="btn btn-outline-primary btn-md"
                    @click="addSubject"
                  >
                    <i class="fas fa-plus me-1"></i> Add row
                  </button>
                </div>

                <!-- Quick Add Templates -->
                <div class="row mb-4">
                  <div class="col-12">
                    <h6 class="fw-bold text-primary mb-3">
                      <i class="fas fa-magic me-1"></i> Quick Templates
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                      <button
                        type="button"
                        class="btn btn-outline-info btn-sm"
                        @click="addCoreSubjectsTemplate(1)"
                      >
                        Add Grade 1 Core Subjects
                      </button>
                      <button
                        type="button"
                        class="btn btn-outline-info btn-sm"
                        @click="addCoreSubjectsTemplate(2)"
                      >
                        Add Grade 2 Core Subjects
                      </button>
                      <button
                        type="button"
                        class="btn btn-outline-info btn-sm"
                        @click="addCoreSubjectsTemplate(0)"
                      >
                        Add Kindergarten Core Subjects
                      </button>
                      <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        @click="clearAll"
                      >
                        Clear All
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Error Summary -->
                <div v-if="form.errors.subjects" class="alert alert-danger mb-4">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  {{ form.errors.subjects }}
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/subjects" class="btn btn-primary-soft btn-md px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-md px-4"
                    :disabled="form.processing || form.subjects.length === 0"
                    aria-label="Create subjects"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      {{ form.processing ? 'Creating...' : `Create ${form.subjects.length} Subject${form.subjects.length > 1 ? 's' : ''}` }}
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
  name: 'SubjectsBulkCreate',
  components: {
    AdminLayout,
    Link
  },
  data() {
    return {
      commonSettings: {
        grade_levels: '',
        curriculum_year: new Date().getFullYear()
      },
      form: useForm({
        subjects: [
          {
            subject_code: '',
            subject_name: '',
            description: '',
            grade_levels: '',
            units: 1,
            subject_type: '',
            curriculum_year: new Date().getFullYear(),
            is_active: true
          }
        ]
      }),
      coreSubjectsTemplates: {
        0: [ // Kindergarten
          { code: 'KIN-ENG', name: 'English for Kindergarten', units: 1, type: 'Core' },
          { code: 'KIN-FIL', name: 'Filipino for Kindergarten', units: 1, type: 'Core' },
          { code: 'KIN-MATH', name: 'Mathematics for Kindergarten', units: 1, type: 'Core' },
          { code: 'KIN-SCI', name: 'Science for Kindergarten', units: 1, type: 'Core' },
          { code: 'KIN-AP', name: 'Araling Panlipunan for Kindergarten', units: 1, type: 'Core' }
        ],
        1: [ // Grade 1
          { code: 'ENG1', name: 'English 1', units: 1, type: 'Core' },
          { code: 'FIL1', name: 'Filipino 1', units: 1, type: 'Core' },
          { code: 'MATH1', name: 'Mathematics 1', units: 1, type: 'Core' },
          { code: 'SCI1', name: 'Science 1', units: 1, type: 'Core' },
          { code: 'AP1', name: 'Araling Panlipunan 1', units: 1, type: 'Core' },
          { code: 'MAPEH1', name: 'MAPEH 1', units: 1, type: 'Core' }
        ],
        2: [ // Grade 2
          { code: 'ENG2', name: 'English 2', units: 1, type: 'Core' },
          { code: 'FIL2', name: 'Filipino 2', units: 1, type: 'Core' },
          { code: 'MATH2', name: 'Mathematics 2', units: 1, type: 'Core' },
          { code: 'SCI2', name: 'Science 2', units: 1, type: 'Core' },
          { code: 'AP2', name: 'Araling Panlipunan 2', units: 1, type: 'Core' },
          { code: 'MAPEH2', name: 'MAPEH 2', units: 1, type: 'Core' }
        ]
      }
    }
  },
  methods: {
    addSubject() {
      this.form.subjects.push({
        subject_code: '',
        subject_name: '',
        description: '',
        grade_levels: this.commonSettings.grade_levels || '',
        units: 1,
        subject_type: '',
        curriculum_year: this.commonSettings.curriculum_year || new Date().getFullYear(),
        is_active: true
      })
    },
    removeSubject(index) {
      if (this.form.subjects.length > 1) {
        this.form.subjects.splice(index, 1)
      }
    },
    applyGradeLevelToAll() {
      if (this.commonSettings.grade_levels !== '') {
        this.form.subjects.forEach(subject => {
          subject.grade_levels = this.commonSettings.grade_levels
        })
      }
    },
    applyCurriculumYearToAll() {
      if (this.commonSettings.curriculum_year) {
        this.form.subjects.forEach(subject => {
          subject.curriculum_year = this.commonSettings.curriculum_year
        })
      }
    },
    addCoreSubjectsTemplate(gradeLevel) {
      const template = this.coreSubjectsTemplates[gradeLevel]
      if (template) {
        // Clear existing subjects
        this.form.subjects = []
        
        // Add template subjects
        template.forEach(subject => {
          this.form.subjects.push({
            subject_code: subject.code,
            subject_name: subject.name,
            description: '',
            grade_levels: gradeLevel,
            units: subject.units,
            subject_type: subject.type,
            curriculum_year: new Date().getFullYear(),
            is_active: true
          })
        })
      }
    },
    clearAll() {
      this.form.subjects = [
        {
          subject_code: '',
          subject_name: '',
          description: '',
          grade_levels: '',
          units: 1,
          subject_type: '',
          curriculum_year: new Date().getFullYear(),
          is_active: true
        }
      ]
    },
    hasRowErrors(index) {
      const errorKeys = Object.keys(this.form.errors)
      return errorKeys.some(key => key.startsWith(`subjects.${index}.`))
    },
    submit() {
      this.form.post('/admin/subjects/bulk-store', {
        onSuccess: () => {
          showSuccessToast('Subjects Created!', `${this.form.subjects.length} subjects have been added successfully.`)
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

.card {
  border-radius: 8px;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.form-control, .form-select {
  border-radius: 4px;
  font-size: 0.875rem;
}

.form-control-sm, .form-select-sm {
  font-size: 0.8rem;
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

.btn-outline-secondary, .btn-outline-primary, .btn-outline-info, .btn-outline-danger {
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-outline-secondary:hover, .btn-outline-primary:hover, .btn-outline-info:hover {
  transform: translateY(-1px);
}

.table {
  font-size: 0.875rem;
}

.table th {
  background-color: rgba(44, 90, 160, 0.1);
  font-weight: 600;
  border-color: rgba(44, 90, 160, 0.2);
}

.table-hover tbody tr:hover {
  background-color: rgba(44, 90, 160, 0.05);
}

.table-danger {
  background-color: rgba(220, 53, 69, 0.1);
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

.table-responsive {
  overflow: hidden;
}

.bg-light {
  background-color: rgba(44, 90, 160, 0.05) !important;
}

@media (max-width: 768px) {
  .table-responsive {
    font-size: 0.75rem;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
  }
}
</style>