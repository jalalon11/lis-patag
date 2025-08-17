<template>
  <TeacherLayout page-title="Student Enrollment Management">
    <div class="container-fluid py-3">
      <!-- Header Section -->
      <div class="row mb-4">
        <div class="col-md-8">
          <h2 class="fw-bold text-primary mb-2">
            <i class="fas fa-user-plus me-3"></i>
            Student Enrollment Management
          </h2>
          <p class="text-muted mb-0">
            Manage student enrollment for your advised sections
          </p>
        </div>
        <div class="col-md-4 text-end">
          <button
            class="btn btn-primary btn-sm px-3 shadow-sm"
            @click="showEnrollmentForm = true"
            v-if="advisedSections.length > 0"
          >
            <i class="fas fa-plus me-1"></i>
            Enroll New Student
          </button>
        </div>
      </div>

      <!-- Alert Messages -->
      <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $page.props.flash.success }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $page.props.flash.error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <!-- No Advised Sections Message -->
      <div v-if="advisedSections.length === 0" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 text-center">
          <div class="py-5">
            <i class="fas fa-users-slash fa-4x text-muted mb-4"></i>
            <h4 class="fw-bold text-primary mb-2">No Advised Sections Found</h4>
            <p class="text-muted mb-0">
              You are not currently assigned as an adviser to any section. Please contact the admin to be assigned as a section adviser.
            </p>
          </div>
        </div>
      </div>

      <!-- Section Cards -->
      <div v-else class="row">
        <div v-for="section in advisedSections" :key="section.id" class="col-md-6 mb-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-gradient-primary text-white">
              <div class="d-flex align-items-center">
                <div>
                  <h5 class="mb-0">{{ section.grade_level }} - {{ section.section_name }}</h5>
                  <small class="opacity-75">{{ currentSchoolYear ? currentSchoolYear.school_year : 'Current School Year' }}</small>
                </div>
                <div class="ms-auto">
                  <span class="badge bg-white text-primary">
                    {{ section.current_enrollment }} / {{ section.capacity }} Students
                  </span>
                </div>
              </div>
            </div>
            <div class="card-body p-0">
              <!-- Enrollment Progress -->
              <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Enrollment Progress</small>
                  <small class="text-primary fw-bold">{{ Math.round((section.current_enrollment / section.capacity) * 100) }}%</small>
                </div>
                <div class="progress" style="height: 8px;">
                  <div
                    class="progress-bar bg-primary"
                    role="progressbar"
                    :style="{ width: `${(section.current_enrollment / section.capacity) * 100}%` }"
                    :aria-valuenow="section.current_enrollment"
                    :aria-valuemin="0"
                    :aria-valuemax="section.capacity"
                  ></div>
                </div>
              </div>

              <!-- Student List -->
              <div class="p-3">
                <h6 class="fw-bold mb-3">
                  <i class="fas fa-user-graduate me-2"></i>
                  Enrolled Students
                </h6>

                <div v-if="section.enrollments && section.enrollments.length > 0" class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="enrollment in section.enrollments" :key="enrollment.id">
                        <td>
                          <span class="badge bg-primary-soft text-primary">
                            {{ enrollment.student.student_id }}
                          </span>
                        </td>
                        <td>
                          {{ enrollment.student.first_name }} 
                          {{ enrollment.student.middle_name ? enrollment.student.middle_name.charAt(0) + '.' : '' }} 
                          {{ enrollment.student.last_name }}
                          {{ enrollment.student.suffix ? enrollment.student.suffix : '' }}
                        </td>
                        <td>
                          <span 
                            class="badge" 
                            :class="{
                              'bg-info-soft text-info': enrollment.student.gender === 'Male',
                              'bg-danger-soft text-danger': enrollment.student.gender === 'Female'
                            }"
                          >
                            <i 
                              class="fas" 
                              :class="{
                                'fa-mars': enrollment.student.gender === 'Male',
                                'fa-venus': enrollment.student.gender === 'Female'
                              }"
                            ></i>
                            {{ enrollment.student.gender }}
                          </span>
                        </td>
                        <td>
                          <button 
                            class="btn btn-sm btn-outline-danger" 
                            @click="confirmRemoveStudent(enrollment)"
                            title="Remove from section"
                          >
                            <i class="fas fa-user-minus"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-else class="text-center py-4">
                  <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                  <p class="text-muted mb-0">No students enrolled in this section yet.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enrollment Form Modal -->
    <div class="modal fade" id="enrollmentFormModal" tabindex="-1" aria-labelledby="enrollmentFormModalLabel" aria-hidden="true" ref="enrollmentModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="enrollmentFormModalLabel">
              <i class="fas fa-user-plus me-2"></i>
              Enroll New Student
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEnrollment">
              <!-- Section Selection -->
              <div class="mb-3">
                <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                <select 
                  id="section_id" 
                  v-model="form.section_id" 
                  class="form-select"
                  :class="{ 'is-invalid': form.errors.section_id }"
                  required
                >
                  <option value="">Select Section</option>
                  <option 
                    v-for="section in availableSections" 
                    :key="section.id" 
                    :value="section.id"
                    :disabled="section.current_enrollment >= section.capacity"
                  >
                    {{ section.grade_level }} - {{ section.section_name }}
                    ({{ section.current_enrollment }}/{{ section.capacity }})
                    {{ section.current_enrollment >= section.capacity ? '- FULL' : '' }}
                  </option>
                </select>
                <div v-if="form.errors.section_id" class="invalid-feedback">
                  {{ form.errors.section_id }}
                </div>
              </div>

              <!-- Student Information -->
              <div class="card mb-3">
                <div class="card-header bg-light">
                  <h6 class="mb-0">Student Information</h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <!-- Student ID -->
                    <div class="col-md-6">
                      <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                      <input 
                        type="text" 
                        id="student_id" 
                        v-model="form.student_id" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.student_id }"
                        required
                      >
                      <div v-if="form.errors.student_id" class="invalid-feedback">
                        {{ form.errors.student_id }}
                      </div>
                    </div>

                    <!-- Grade Level -->
                    <div class="col-md-6">
                      <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                      <select 
                        id="grade_level" 
                        v-model="form.grade_level" 
                        class="form-select"
                        :class="{ 'is-invalid': form.errors.grade_level }"
                        required
                      >
                        <option value="">Select Grade Level</option>
                        <option value="Grade 1">Grade 1</option>
                        <option value="Grade 2">Grade 2</option>
                        <option value="Grade 3">Grade 3</option>
                        <option value="Grade 4">Grade 4</option>
                        <option value="Grade 5">Grade 5</option>
                        <option value="Grade 6">Grade 6</option>
                      </select>
                      <div v-if="form.errors.grade_level" class="invalid-feedback">
                        {{ form.errors.grade_level }}
                      </div>
                    </div>

                    <!-- First Name -->
                    <div class="col-md-6">
                      <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                      <input 
                        type="text" 
                        id="first_name" 
                        v-model="form.first_name" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.first_name }"
                        required
                      >
                      <div v-if="form.errors.first_name" class="invalid-feedback">
                        {{ form.errors.first_name }}
                      </div>
                    </div>

                    <!-- Middle Name -->
                    <div class="col-md-6">
                      <label for="middle_name" class="form-label">Middle Name</label>
                      <input 
                        type="text" 
                        id="middle_name" 
                        v-model="form.middle_name" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.middle_name }"
                      >
                      <div v-if="form.errors.middle_name" class="invalid-feedback">
                        {{ form.errors.middle_name }}
                      </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6">
                      <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                      <input 
                        type="text" 
                        id="last_name" 
                        v-model="form.last_name" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.last_name }"
                        required
                      >
                      <div v-if="form.errors.last_name" class="invalid-feedback">
                        {{ form.errors.last_name }}
                      </div>
                    </div>

                    <!-- Suffix -->
                    <div class="col-md-6">
                      <label for="suffix" class="form-label">Suffix</label>
                      <input 
                        type="text" 
                        id="suffix" 
                        v-model="form.suffix" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.suffix }"
                        placeholder="Jr., Sr., III, etc."
                      >
                      <div v-if="form.errors.suffix" class="invalid-feedback">
                        {{ form.errors.suffix }}
                      </div>
                    </div>

                    <!-- Birth Date -->
                    <div class="col-md-6">
                      <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                      <input 
                        type="date" 
                        id="birth_date" 
                        v-model="form.birth_date" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.birth_date }"
                        required
                      >
                      <div v-if="form.errors.birth_date" class="invalid-feedback">
                        {{ form.errors.birth_date }}
                      </div>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                      <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
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

                    <!-- Address -->
                    <div class="col-12">
                      <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                      <textarea 
                        id="address" 
                        v-model="form.address" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.address }"
                        rows="2"
                        required
                      ></textarea>
                      <div v-if="form.errors.address" class="invalid-feedback">
                        {{ form.errors.address }}
                      </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="col-md-6">
                      <label for="contact_number" class="form-label">Contact Number</label>
                      <input 
                        type="text" 
                        id="contact_number" 
                        v-model="form.contact_number" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.contact_number }"
                      >
                      <div v-if="form.errors.contact_number" class="invalid-feedback">
                        {{ form.errors.contact_number }}
                      </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                      <label for="email" class="form-label">Email</label>
                      <input 
                        type="email" 
                        id="email" 
                        v-model="form.email" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.email }"
                      >
                      <div v-if="form.errors.email" class="invalid-feedback">
                        {{ form.errors.email }}
                      </div>
                    </div>

                    <!-- Medical Conditions -->
                    <div class="col-12">
                      <label for="medical_conditions" class="form-label">Medical Conditions</label>
                      <textarea 
                        id="medical_conditions" 
                        v-model="form.medical_conditions" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.medical_conditions }"
                        rows="2"
                        placeholder="List any medical conditions, allergies, or special needs"
                      ></textarea>
                      <div v-if="form.errors.medical_conditions" class="invalid-feedback">
                        {{ form.errors.medical_conditions }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Parent/Guardian Information -->
              <div class="card mb-3">
                <div class="card-header bg-light">
                  <h6 class="mb-0">Parent/Guardian Information</h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <!-- Parent/Guardian Name -->
                    <div class="col-md-6">
                      <label for="parent_guardian_name" class="form-label">Name <span class="text-danger">*</span></label>
                      <input 
                        type="text" 
                        id="parent_guardian_name" 
                        v-model="form.parent_guardian_name" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.parent_guardian_name }"
                        required
                      >
                      <div v-if="form.errors.parent_guardian_name" class="invalid-feedback">
                        {{ form.errors.parent_guardian_name }}
                      </div>
                    </div>

                    <!-- Relationship to Student -->
                    <div class="col-md-6">
                      <label for="relationship_to_student" class="form-label">Relationship <span class="text-danger">*</span></label>
                      <select 
                        id="relationship_to_student" 
                        v-model="form.relationship_to_student" 
                        class="form-select"
                        :class="{ 'is-invalid': form.errors.relationship_to_student }"
                        required
                      >
                        <option value="">Select Relationship</option>
                        <option value="Mother">Mother</option>
                        <option value="Father">Father</option>
                        <option value="Grandmother">Grandmother</option>
                        <option value="Grandfather">Grandfather</option>
                        <option value="Aunt">Aunt</option>
                        <option value="Uncle">Uncle</option>
                        <option value="Guardian">Guardian</option>
                        <option value="Other">Other</option>
                      </select>
                      <div v-if="form.errors.relationship_to_student" class="invalid-feedback">
                        {{ form.errors.relationship_to_student }}
                      </div>
                    </div>

                    <!-- Parent/Guardian Contact -->
                    <div class="col-md-6">
                      <label for="parent_guardian_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                      <input 
                        type="text" 
                        id="parent_guardian_contact" 
                        v-model="form.parent_guardian_contact" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.parent_guardian_contact }"
                        required
                      >
                      <div v-if="form.errors.parent_guardian_contact" class="invalid-feedback">
                        {{ form.errors.parent_guardian_contact }}
                      </div>
                    </div>

                    <!-- Parent/Guardian Email -->
                    <div class="col-md-6">
                      <label for="parent_guardian_email" class="form-label">Email</label>
                      <input 
                        type="email" 
                        id="parent_guardian_email" 
                        v-model="form.parent_guardian_email" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.parent_guardian_email }"
                      >
                      <div v-if="form.errors.parent_guardian_email" class="invalid-feedback">
                        {{ form.errors.parent_guardian_email }}
                      </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="col-md-6">
                      <label for="emergency_contact" class="form-label">Emergency Contact</label>
                      <input 
                        type="text" 
                        id="emergency_contact" 
                        v-model="form.emergency_contact" 
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.emergency_contact }"
                      >
                      <div v-if="form.errors.emergency_contact" class="invalid-feedback">
                        {{ form.errors.emergency_contact }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" :disabled="processing">
                  <span v-if="processing" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                  Enroll Student
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Remove Student Confirmation Modal -->
    <div class="modal fade" id="removeStudentModal" tabindex="-1" aria-labelledby="removeStudentModalLabel" aria-hidden="true" ref="removeModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="removeStudentModalLabel">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Confirm Removal
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" v-if="enrollmentToRemove">
            <p>Are you sure you want to remove the following student from this section?</p>
            <div class="alert alert-warning">
              <strong>
                {{ enrollmentToRemove.student?.first_name }} 
                {{ enrollmentToRemove.student?.middle_name ? enrollmentToRemove.student?.middle_name.charAt(0) + '.' : '' }} 
                {{ enrollmentToRemove.student?.last_name }}
                {{ enrollmentToRemove.student?.suffix ? enrollmentToRemove.student?.suffix : '' }}
              </strong>
              <br>
              <small>Student ID: {{ enrollmentToRemove.student?.student_id }}</small>
            </div>
            <p class="text-danger"><small>This action cannot be undone.</small></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="removeStudent" :disabled="processing">
              <span v-if="processing" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
              Remove Student
            </button>
          </div>
        </div>
      </div>
    </div>
  </TeacherLayout>
</template>

<script>
import { ref, computed, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TeacherLayout from '@/Layouts/TeacherLayout.vue';

export default {
  components: {
    TeacherLayout,
  },
  props: {
    advisedSections: Array,
    currentSchoolYear: Object,
  },
  setup(props) {
    // Bootstrap modal instances
    const enrollmentModal = ref(null);
    const removeModal = ref(null);
    let bsEnrollmentModal = null;
    let bsRemoveModal = null;

    // State
    const showEnrollmentForm = ref(false);
    const processing = ref(false);
    const enrollmentToRemove = ref(null);

    // Form for student enrollment
    const form = useForm({
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
      parent_guardian_name: '',
      parent_guardian_contact: '',
      parent_guardian_email: '',
      relationship_to_student: '',
      grade_level: '',
      section_id: '',
      medical_conditions: '',
      emergency_contact: '',
    });

    // Computed properties
    const availableSections = computed(() => {
      return props.advisedSections;
    });

    // Watch for changes to showEnrollmentForm
    watch(showEnrollmentForm, (newValue) => {
      if (newValue && bsEnrollmentModal) {
        bsEnrollmentModal.show();
      }
    });

    // Methods
    const submitEnrollment = () => {
      processing.value = true;
      form.post('/teacher/enrollment', {
        preserveScroll: true,
        onSuccess: () => {
          if (bsEnrollmentModal) {
            bsEnrollmentModal.hide();
          }
          resetForm();
          processing.value = false;
        },
        onError: () => {
          processing.value = false;
        },
      });
    };

    const resetForm = () => {
      form.reset();
      form.clearErrors();
    };

    const confirmRemoveStudent = (enrollment) => {
      enrollmentToRemove.value = enrollment;
      if (bsRemoveModal) {
        bsRemoveModal.show();
      }
    };

    const removeStudent = () => {
      if (!enrollmentToRemove.value) return;
      
      processing.value = true;
      
      // Use Inertia to send a DELETE request
      form.delete(`/teacher/enrollment/${enrollmentToRemove.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          if (bsRemoveModal) {
            bsRemoveModal.hide();
          }
          enrollmentToRemove.value = null;
          processing.value = false;
        },
        onError: () => {
          processing.value = false;
        },
      });
    };

    // Initialize Bootstrap modals after component is mounted
    onMounted(() => {
      // Import Bootstrap modal dynamically to avoid SSR issues
      import('bootstrap/js/dist/modal').then(({ Modal }) => {
        if (enrollmentModal.value) {
          bsEnrollmentModal = new Modal(enrollmentModal.value);
        }
        if (removeModal.value) {
          bsRemoveModal = new Modal(removeModal.value);
        }

        // Handle modal hidden events to reset state
        enrollmentModal.value?.addEventListener('hidden.bs.modal', () => {
          showEnrollmentForm.value = false;
          resetForm();
        });

        removeModal.value?.addEventListener('hidden.bs.modal', () => {
          enrollmentToRemove.value = null;
        });
      });
    });

    return {
      showEnrollmentForm,
      processing,
      form,
      availableSections,
      enrollmentToRemove,
      enrollmentModal,
      removeModal,
      submitEnrollment,
      confirmRemoveStudent,
      removeStudent,
    };
  },
};
</script>

<style scoped>
.teacher-avatar-large {
  width: 80px;
  height: 80px;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.rotate-180 {
  transform: rotate(180deg);
  transition: transform 0.3s ease;
}

.bg-primary-soft {
  background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-soft {
  background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
}

.bg-danger-soft {
  background-color: rgba(220, 53, 69, 0.1);
}

.bg-info-soft {
  background-color: rgba(13, 202, 240, 0.1);
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-gradient-success {
  background: linear-gradient(135deg, #198754 0%, #146c43 100%);
}
</style>