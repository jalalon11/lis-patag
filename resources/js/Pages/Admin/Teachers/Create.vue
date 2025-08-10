<template>
  <AdminLayout page-title="Add New Teacher">
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
                    <i class="fas fa-user-circle me-1"></i> Basic Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="teacher_id"
                          v-model="form.teacher_id"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.teacher_id }"
                          placeholder="Teacher ID"
                          required
                        />
                        <label for="teacher_id" class="form-label">
                          Teacher ID <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.teacher_id" class="invalid-feedback">
                          {{ form.errors.teacher_id }}
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
                          required
                        />
                        <label for="email" class="form-label">
                          Email Address <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.email" class="invalid-feedback">
                          {{ form.errors.email }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-id-card me-1"></i> Personal Information
                  </h5>
                  <div class="row g-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-1">
                      <div class="form-floating">
                        <input
                          id="suffix"
                          v-model="form.suffix"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.suffix }"
                          placeholder="Suffix"
                        />
                        <label for="suffix" class="form-label">
                          Suffix
                        </label>
                        <div v-if="form.errors.suffix" class="invalid-feedback">
                          {{ form.errors.suffix }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="birth_date"
                          v-model="form.birth_date"
                          type="date"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.birth_date }"
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
                    <div class="col-md-6">
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
                          placeholder="Address"
                          style="height: 100px"
                          required
                        ></textarea>
                        <label for="address" class="form-label">
                          Address <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.address" class="invalid-feedback">
                          {{ form.errors.address }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-phone me-1"></i> Contact Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="contact_number"
                          v-model="form.contact_number"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.contact_number }"
                          placeholder="Contact Number"
                          required
                        />
                        <label for="contact_number" class="form-label">
                          Contact Number <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.contact_number" class="invalid-feedback">
                          {{ form.errors.contact_number }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="emergency_contact"
                          v-model="form.emergency_contact"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.emergency_contact }"
                          placeholder="Emergency Contact"
                          required
                        />
                        <label for="emergency_contact" class="form-label">
                          Emergency Contact <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.emergency_contact" class="invalid-feedback">
                          {{ form.errors.emergency_contact }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Employment Information -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-briefcase me-1"></i> Employment Information
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="position"
                          v-model="form.position"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.position }"
                          placeholder="Position"
                          required
                        />
                        <label for="position" class="form-label">
                          Position <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.position" class="invalid-feedback">
                          {{ form.errors.position }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="department"
                          v-model="form.department"
                          type="text"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.department }"
                          placeholder="Department"
                        />
                        <label for="department" class="form-label">
                          Department
                        </label>
                        <div v-if="form.errors.department" class="invalid-feedback">
                          {{ form.errors.department }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="hire_date"
                          v-model="form.hire_date"
                          type="date"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.hire_date }"
                          required
                        />
                        <label for="hire_date" class="form-label">
                          Hire Date <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.hire_date" class="invalid-feedback">
                          {{ form.errors.hire_date }}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select
                          id="employment_status"
                          v-model="form.employment_status"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.employment_status }"
                          required
                        >
                          <option value="">Select Status</option>
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                          <option value="Resigned">Resigned</option>
                          <option value="Terminated">Terminated</option>
                        </select>
                        <label for="employment_status" class="form-label">
                          Employment Status <span class="text-danger">*</span>
                        </label>
                        <div v-if="form.errors.employment_status" class="invalid-feedback">
                          {{ form.errors.employment_status }}
                        </div>
                      </div>
                    </div>
                    <!-- <div class="col-md-6">
                      <div class="form-floating">
                        <input
                          id="salary"
                          v-model="form.salary"
                          type="number"
                          step="0.01"
                          min="0"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.salary }"
                          placeholder="Salary"
                        />
                        <label for="salary" class="form-label">
                          Salary
                        </label>
                        <div v-if="form.errors.salary" class="invalid-feedback">
                          {{ form.errors.salary }}
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>

                <!-- Qualifications -->
                <div class="mb-4">
                  <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-graduation-cap me-1"></i> Qualifications
                  </h5>
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea
                          id="qualifications"
                          v-model="form.qualifications"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.qualifications }"
                          placeholder="Qualifications"
                          style="height: 100px"
                        ></textarea>
                        <label for="qualifications" class="form-label">
                          Qualifications
                        </label>
                        <div v-if="form.errors.qualifications" class="invalid-feedback">
                          {{ form.errors.qualifications }}
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea
                          id="certifications"
                          v-model="form.certifications"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.certifications }"
                          placeholder="Certifications"
                          style="height: 100px"
                        ></textarea>
                        <label for="certifications" class="form-label">
                          Certifications
                        </label>
                        <div v-if="form.errors.certifications" class="invalid-feedback">
                          {{ form.errors.certifications }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <Link href="/admin/teachers" class="btn btn-outline-secondary btn-sm px-3">
                    Cancel
                  </Link>
                  <button
                    type="submit"
                    class="btn btn-primary btn-sm px-4"
                    :disabled="form.processing"
                    aria-label="Create teacher"
                  >
                    <span class="d-flex align-items-center justify-content-center">
                      <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                      {{ form.processing ? 'Creating...' : 'Create Teacher' }}
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
  name: 'CreateTeacher',
  components: {
    AdminLayout,
    Link
  },
  props: {
    suggestedTeacherId: {
      type: String,
      default: 'T001'
    }
  },
  data() {
    return {
      form: useForm({
        teacher_id: this.suggestedTeacherId,
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: '',
        birth_date: '',
        gender: '',
        address: '',
        contact_number: '',
        email: '',
        emergency_contact: '',
        position: '',
        department: '',
        hire_date: '',
        employment_status: 'Active',
        salary: '',
        qualifications: '',
        certifications: '',
      })
    }
  },
  methods: {
    submit() {
      this.form.post('/admin/teachers', {
        onSuccess: () => {
          const fullName = `${this.form.first_name} ${this.form.last_name}`
          showSuccessToast('Teacher Created!', `${fullName} has been added successfully.`)
          router.visit('/admin/teachers')
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