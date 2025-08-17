<template>
  <AdminLayout page-title="Student Admission">
    <div class="container-fluid py-3">
      <!-- Admission Form -->
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <form @submit.prevent="submit">
                <!-- Personal Information Section -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-user me-1"></i> Personal Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input
                          id="student_id"
                          v-model="form.student_id"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.student_id }"
                          placeholder="Student ID/LRN"
                          required
                        />
                        <label for="student_id" class="form-label">
                          Student ID/LRN <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.student_id" class="invalid-feedback">
                          {{ form.errors.student_id }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input
                          id="first_name"
                          v-model="form.first_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.first_name }"
                          placeholder="First Name"
                          required
                        />
                        <label for="first_name" class="form-label">
                          First Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.first_name" class="invalid-feedback">
                          {{ form.errors.first_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input
                          id="middle_name"
                          v-model="form.middle_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.middle_name }"
                          placeholder="Middle Name"
                        />
                        <label for="middle_name" class="form-label">
                          Middle Name
                        </label>
                        <div v-if="form.errors.middle_name" class="invalid-feedback">
                          {{ form.errors.middle_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input
                          id="last_name"
                          v-model="form.last_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.last_name }"
                          placeholder="Last Name"
                          required
                        />
                        <label for="last_name" class="form-label">
                          Last Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.last_name" class="invalid-feedback">
                          {{ form.errors.last_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <select
                          id="suffix"
                          v-model="form.suffix"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.suffix }"
                        >
                          <option value="">None</option>
                          <option value="Jr.">Jr.</option>
                          <option value="Sr.">Sr.</option>
                          <option value="II">II</option>
                          <option value="III">III</option>
                          <option value="IV">IV</option>
                        </select>
                        <label for="suffix" class="form-label">
                          Suffix
                        </label>
                        <div v-if="form.errors.suffix" class="invalid-feedback">
                          {{ form.errors.suffix }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <input
                          id="birth_date"
                          v-model="form.birth_date"
                          type="date"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.birth_date }"
                          placeholder="Birth Date"
                          required
                        />
                        <label for="birth_date" class="form-label">
                          Birth Date <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.birth_date" class="invalid-feedback">
                          {{ form.errors.birth_date }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <select
                          id="gender"
                          v-model="form.gender"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.gender }"
                          required
                        >
                          <option value="">Select Gender</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                        <label for="gender" class="form-label">
                          Gender <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.gender" class="invalid-feedback">
                          {{ form.errors.gender }}
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
                          placeholder="Complete Address"
                          style="height: 100px"
                          required
                        ></textarea>
                        <label for="address" class="form-label">
                          Complete Address <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.address" class="invalid-feedback">
                          {{ form.errors.address }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="contact_number"
                          v-model="form.contact_number"
                          type="tel"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.contact_number }"
                          placeholder="Contact Number"
                        />
                        <label for="contact_number" class="form-label">
                          Contact Number
                        </label>
                        <div v-if="form.errors.contact_number" class="invalid-feedback">
                          {{ form.errors.contact_number }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="email"
                          v-model="form.email"
                          type="email"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.email }"
                          placeholder="Email Address"
                        />
                        <label for="email" class="form-label">
                          Email Address
                        </label>
                        <div v-if="form.errors.email" class="invalid-feedback">
                          {{ form.errors.email }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Parent/Guardian Information Section -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-users me-1"></i> Parent/Guardian Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="parent_guardian_name"
                          v-model="form.parent_guardian_name"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.parent_guardian_name }"
                          placeholder="Parent/Guardian Name"
                          required
                        />
                        <label for="parent_guardian_name" class="form-label">
                          Parent/Guardian Name <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.parent_guardian_name" class="invalid-feedback">
                          {{ form.errors.parent_guardian_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="relationship_to_student"
                          v-model="form.relationship_to_student"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.relationship_to_student }"
                          required
                        >
                          <option value="">Select Relationship</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Guardian">Guardian</option>
                          <option value="Grandfather">Grandfather</option>
                          <option value="Grandmother">Grandmother</option>
                          <option value="Uncle">Uncle</option>
                          <option value="Aunt">Aunt</option>
                          <option value="Other">Other</option>
                        </select>
                        <label for="relationship_to_student" class="form-label">
                          Relationship <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.relationship_to_student" class="invalid-feedback">
                          {{ form.errors.relationship_to_student }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="parent_guardian_contact"
                          v-model="form.parent_guardian_contact"
                          type="tel"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.parent_guardian_contact }"
                          placeholder="Contact Number"
                          required
                        />
                        <label for="parent_guardian_contact" class="form-label">
                          Contact Number <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.parent_guardian_contact" class="invalid-feedback">
                          {{ form.errors.parent_guardian_contact }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="parent_guardian_email"
                          v-model="form.parent_guardian_email"
                          type="email"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.parent_guardian_email }"
                          placeholder="Email Address"
                        />
                        <label for="parent_guardian_email" class="form-label">
                          Email Address
                        </label>
                        <div v-if="form.errors.parent_guardian_email" class="invalid-feedback">
                          {{ form.errors.parent_guardian_email }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Academic Information Section -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-graduation-cap me-1"></i> Academic Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <div class="form-floating">
                        <select
                          id="school_year_id"
                          v-model="form.school_year_id"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.school_year_id }"
                          required
                        >
                          <option value="">Select School Year</option>
                          <option v-for="year in allSchoolYears" :key="year.id" :value="year.id">{{ year.year_name }}</option>
                        </select>
                        <label for="school_year_id" class="form-label">
                          School Year <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.school_year_id" class="invalid-feedback">
                          {{ form.errors.school_year_id }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <select
                          id="grade_level"
                          v-model="form.grade_level"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.grade_level }"
                          required
                          @change="updateAvailableSections"
                        >
                          <option value="">Select Grade Level</option>
                          <option value="0">Kindergarten</option>
                          <option value="1">Grade 1</option>
                          <option value="2">Grade 2</option>
                          <option value="3">Grade 3</option>
                          <option value="4">Grade 4</option>
                          <option value="5">Grade 5</option>
                          <option value="6">Grade 6</option>
                        </select>
                        <label for="grade_level" class="form-label">
                          Grade Level <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.grade_level" class="invalid-feedback">
                          {{ form.errors.grade_level }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <select
                          id="section_id"
                          v-model="form.section_id"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.section_id }"
                          required
                          :disabled="!form.grade_level"
                        >
                          <option value="">{{ form.grade_level ? 'Select Section' : 'Select Grade Level First' }}</option>
                          <option 
                            v-for="section in availableSections" 
                            :key="section.id" 
                            :value="section.id"
                            :disabled="section.current_enrollment >= section.capacity"
                          >
                            {{ section.section_name }} 
                            ({{ section.current_enrollment }}/{{ section.capacity }}{{ section.current_enrollment >= section.capacity ? ' - FULL' : '' }})
                          </option>
                        </select>
                        <label for="section_id" class="form-label">
                          Section <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.section_id" class="invalid-feedback">
                          {{ form.errors.section_id }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Information Section -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-info-circle me-1"></i> Additional Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <textarea
                          id="medical_conditions"
                          v-model="form.medical_conditions"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.medical_conditions }"
                          placeholder="Medical Conditions/Allergies"
                          style="height: 100px"
                        ></textarea>
                        <label for="medical_conditions" class="form-label">
                          Medical Conditions/Allergies
                        </label>
                        <div v-if="form.errors.medical_conditions" class="invalid-feedback">
                          {{ form.errors.medical_conditions }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <textarea
                          id="emergency_contact"
                          v-model="form.emergency_contact"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.emergency_contact }"
                          placeholder="Emergency Contact"
                          style="height: 100px"
                        ></textarea>
                        <label for="emergency_contact" class="form-label">
                          Emergency Contact
                        </label>
                        <div v-if="form.errors.emergency_contact" class="invalid-feedback">
                          {{ form.errors.emergency_contact }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/students" class="btn btn-primary-soft btn-md px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-md px-4"
                    :disabled="form.processing"
                    aria-label="Enroll student"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      {{ form.processing ? 'Enrolling...' : 'Enroll Student' }}
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
  name: 'AdmissionIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    sections: Object,
    currentSchoolYear: Object,
    allSchoolYears: Array,
    gradeLevels: Array
  },
  data() {
    return {
      availableSections: [],
      form: useForm({
        // Personal Information
        student_id: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: '',
        birth_date: '',
        // Set current school year as default
        school_year_id: this.currentSchoolYear?.id || '',
        gender: '',
        address: '',
        contact_number: '',
        email: '',
        
        // Parent/Guardian Information
        parent_guardian_name: '',
        parent_guardian_contact: '',
        parent_guardian_email: '',
        relationship_to_student: '',
        
        // Academic Information
        grade_level: '',
        section_id: '',
        
        // Additional Information
        medical_conditions: '',
        emergency_contact: '',
      })
    }
  },
  mounted() {
    // Check for flash messages
    if (this.$page.props.flash.success) {
      showSuccessToast('Success!', this.$page.props.flash.success)
    }
    if (this.$page.props.flash.error) {
      showErrorToast('Error', this.$page.props.flash.error)
    }
    
    // Set current school year as default if not already set
    if (!this.form.school_year_id && this.currentSchoolYear) {
      this.form.school_year_id = this.currentSchoolYear.id
    }
  },
  methods: {
    submit() {
      this.form.post('/admin/admission', {
        onSuccess: () => {
          showSuccessToast(
            'Student Enrolled!',
            `${this.form.first_name} ${this.form.last_name} has been enrolled successfully.`
          )
          this.form.reset()
          this.availableSections = []
          router.visit('/admin/enrollment')
        },
        onError: (errors) => {
          const firstError = Object.values(errors)[0]
          showErrorToast(
            'Enrollment Failed',
            firstError || 'Please check the form and try again.'
          )
        }
      })
    },
    updateAvailableSections() {
      // Use the grade_level directly as the key for sections lookup
      const gradeKey = this.form.grade_level;
      
      if (gradeKey && this.sections[gradeKey]) {
        this.availableSections = this.sections[gradeKey]
      } else {
        this.availableSections = []
      }
      this.form.section_id = '' // Reset section selection
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

.bg-gradient-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
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