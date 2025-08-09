<template>
  <AdminLayout page-title="Student Admission">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Student Admission</h2>
        <p class="text-muted">Enroll new students for {{ currentSchoolYear?.year_name || '2024-2025' }}</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="card border-0 bg-primary text-white">
          <div class="card-body py-2 px-3">
            <small class="d-block">Current School Year</small>
            <strong>{{ currentSchoolYear?.year_name || '2024-2025' }}</strong>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Admission Form -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-user-plus me-2"></i>
              Student Enrollment Form
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="submit">
              <!-- Personal Information Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h6 class="fw-bold text-secondary mb-3">
                    <i class="fas fa-user me-2"></i>
                    Personal Information
                  </h6>
                </div>
                
                <div class="col-md-4 mb-3">
                  <label for="student_id" class="form-label fw-semibold">Student ID/LRN <span class="text-danger">*</span></label>
                  <input
                    id="student_id"
                    v-model="form.student_id"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.student_id }"
                    placeholder="Enter student ID or LRN"
                    required
                  />
                  <div v-if="form.errors.student_id" class="invalid-feedback">
                    {{ form.errors.student_id }}
                  </div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                  <input
                    id="first_name"
                    v-model="form.first_name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.first_name }"
                    placeholder="Enter first name"
                    required
                  />
                  <div v-if="form.errors.first_name" class="invalid-feedback">
                    {{ form.errors.first_name }}
                  </div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                  <input
                    id="middle_name"
                    v-model="form.middle_name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.middle_name }"
                    placeholder="Enter middle name"
                  />
                  <div v-if="form.errors.middle_name" class="invalid-feedback">
                    {{ form.errors.middle_name }}
                  </div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                  <input
                    id="last_name"
                    v-model="form.last_name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.last_name }"
                    placeholder="Enter last name"
                    required
                  />
                  <div v-if="form.errors.last_name" class="invalid-feedback">
                    {{ form.errors.last_name }}
                  </div>
                </div>

                <div class="col-md-2 mb-3">
                  <label for="suffix" class="form-label fw-semibold">Suffix</label>
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
                  <div v-if="form.errors.suffix" class="invalid-feedback">
                    {{ form.errors.suffix }}
                  </div>
                </div>

                <div class="col-md-3 mb-3">
                  <label for="birth_date" class="form-label fw-semibold">Birth Date <span class="text-danger">*</span></label>
                  <input
                    id="birth_date"
                    v-model="form.birth_date"
                    type="date"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.birth_date }"
                    required
                  />
                  <div v-if="form.errors.birth_date" class="invalid-feedback">
                    {{ form.errors.birth_date }}
                  </div>
                </div>

                <div class="col-md-3 mb-3">
                  <label for="gender" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
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
                  <div v-if="form.errors.gender" class="invalid-feedback">
                    {{ form.errors.gender }}
                  </div>
                </div>

                <div class="col-md-12 mb-3">
                  <label for="address" class="form-label fw-semibold">Complete Address <span class="text-danger">*</span></label>
                  <textarea
                    id="address"
                    v-model="form.address"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.address }"
                    rows="3"
                    placeholder="Enter complete address"
                    required
                  ></textarea>
                  <div v-if="form.errors.address" class="invalid-feedback">
                    {{ form.errors.address }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="contact_number" class="form-label fw-semibold">Contact Number</label>
                  <input
                    id="contact_number"
                    v-model="form.contact_number"
                    type="tel"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.contact_number }"
                    placeholder="Enter contact number"
                  />
                  <div v-if="form.errors.contact_number" class="invalid-feedback">
                    {{ form.errors.contact_number }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label fw-semibold">Email Address</label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.email }"
                    placeholder="Enter email address"
                  />
                  <div v-if="form.errors.email" class="invalid-feedback">
                    {{ form.errors.email }}
                  </div>
                </div>
              </div>

              <!-- Parent/Guardian Information Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h6 class="fw-bold text-secondary mb-3">
                    <i class="fas fa-users me-2"></i>
                    Parent/Guardian Information
                  </h6>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="parent_guardian_name" class="form-label fw-semibold">Parent/Guardian Name <span class="text-danger">*</span></label>
                  <input
                    id="parent_guardian_name"
                    v-model="form.parent_guardian_name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.parent_guardian_name }"
                    placeholder="Enter parent/guardian name"
                    required
                  />
                  <div v-if="form.errors.parent_guardian_name" class="invalid-feedback">
                    {{ form.errors.parent_guardian_name }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="relationship_to_student" class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
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
                  <div v-if="form.errors.relationship_to_student" class="invalid-feedback">
                    {{ form.errors.relationship_to_student }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="parent_guardian_contact" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                  <input
                    id="parent_guardian_contact"
                    v-model="form.parent_guardian_contact"
                    type="tel"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.parent_guardian_contact }"
                    placeholder="Enter contact number"
                    required
                  />
                  <div v-if="form.errors.parent_guardian_contact" class="invalid-feedback">
                    {{ form.errors.parent_guardian_contact }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="parent_guardian_email" class="form-label fw-semibold">Email Address</label>
                  <input
                    id="parent_guardian_email"
                    v-model="form.parent_guardian_email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.parent_guardian_email }"
                    placeholder="Enter email address"
                  />
                  <div v-if="form.errors.parent_guardian_email" class="invalid-feedback">
                    {{ form.errors.parent_guardian_email }}
                  </div>
                </div>
              </div>

              <!-- Academic Information Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h6 class="fw-bold text-secondary mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Academic Information
                  </h6>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="grade_level" class="form-label fw-semibold">Grade Level <span class="text-danger">*</span></label>
                  <select
                    id="grade_level"
                    v-model="form.grade_level"
                    class="form-select"
                    :class="{ 'is-invalid': form.errors.grade_level }"
                    required
                    @change="updateAvailableSections"
                  >
                    <option value="">Select Grade Level</option>
                    <option v-for="grade in gradeLevels" :key="grade" :value="grade">{{ grade }}</option>
                  </select>
                  <div v-if="form.errors.grade_level" class="invalid-feedback">
                    {{ form.errors.grade_level }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="section_id" class="form-label fw-semibold">Section <span class="text-danger">*</span></label>
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
                      ({{ section.current_enrollment }}/{{ section.capacity }})
                      {{ section.current_enrollment >= section.capacity ? ' - FULL' : '' }}
                    </option>
                  </select>
                  <div v-if="form.errors.section_id" class="invalid-feedback">
                    {{ form.errors.section_id }}
                  </div>
                </div>
              </div>

              <!-- Additional Information Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h6 class="fw-bold text-secondary mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Additional Information
                  </h6>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="medical_conditions" class="form-label fw-semibold">Medical Conditions/Allergies</label>
                  <textarea
                    id="medical_conditions"
                    v-model="form.medical_conditions"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.medical_conditions }"
                    rows="3"
                    placeholder="Enter any medical conditions or allergies"
                  ></textarea>
                  <div v-if="form.errors.medical_conditions" class="invalid-feedback">
                    {{ form.errors.medical_conditions }}
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="emergency_contact" class="form-label fw-semibold">Emergency Contact</label>
                  <textarea
                    id="emergency_contact"
                    v-model="form.emergency_contact"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.emergency_contact }"
                    rows="3"
                    placeholder="Enter emergency contact information"
                  ></textarea>
                  <div v-if="form.errors.emergency_contact" class="invalid-feedback">
                    {{ form.errors.emergency_contact }}
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="row">
                <div class="col-12">
                  <div class="d-flex justify-content-end">
                    <button
                      type="submit"
                      class="btn btn-primary btn-lg"
                      :disabled="form.processing"
                    >
                      <LoadingSpinner
                        v-if="form.processing"
                        :show="true"
                        :size="20"
                        :border="3"
                        class="me-2 d-inline-block"
                      />
                      <i v-else class="fas fa-user-plus me-2"></i>
                      {{ form.processing ? 'Enrolling Student...' : 'Enroll Student' }}
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
import { useForm } from '@inertiajs/vue3'

export default {
  name: 'AdmissionIndex',
  components: {
    AdminLayout
  },
  props: {
    sections: Object,
    currentSchoolYear: Object,
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
  methods: {
    submit() {
      this.form.post('/admin/admission', {
        onStart: () => {
          // Form processing state is automatically handled by Inertia
        },
        onSuccess: () => {
          this.form.reset()
          this.availableSections = []
        },
        onError: () => {
          // Error handling
        }
      })
    },
    updateAvailableSections() {
      if (this.form.grade_level && this.sections[this.form.grade_level]) {
        this.availableSections = this.sections[this.form.grade_level]
      } else {
        this.availableSections = []
      }
      this.form.section_id = '' // Reset section selection
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

.btn-primary {
  background: linear-gradient(45deg, var(--primary-color), #1e3d6f);
  border: none;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #1e3d6f, var(--primary-color));
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card {
  transition: transform 0.2s ease-in-out;
}

h6.fw-bold {
  border-bottom: 2px solid var(--primary-color);
  padding-bottom: 0.5rem;
  margin-bottom: 1rem;
}

.text-secondary {
  color: var(--primary-color) !important;
}
</style>
