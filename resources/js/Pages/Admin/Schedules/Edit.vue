<template>
  <AdminLayout page-title="Edit Schedule">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Edit Schedule
        </h2>
        <p class="text-muted mb-0">Update schedule information</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/schedules"
          class="btn btn-outline-secondary btn-sm px-3"
        >
          <i class="fas fa-arrow-left me-1"></i>
          Back to Schedules
        </Link>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row g-3">
            <!-- Subject Selection -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark">Subject <span class="text-danger">*</span></label>
              <select
                v-model="form.subject_id"
                class="form-select"
                :class="{ 'is-invalid': form.errors.subject_id }"
                required
              >
                <option value="">Select Subject</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                  {{ subject.subject_name }} ({{ subject.subject_code }})
                </option>
              </select>
              <div v-if="form.errors.subject_id" class="invalid-feedback">
                {{ form.errors.subject_id }}
              </div>
            </div>

            <!-- Teacher Selection -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark">Teacher <span class="text-danger">*</span></label>
              <select
                v-model="form.teacher_id"
                class="form-select"
                :class="{ 'is-invalid': form.errors.teacher_id }"
                required
              >
                <option value="">Select Teacher</option>
                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                  {{ teacher.first_name }} {{ teacher.last_name }}
                </option>
              </select>
              <div v-if="form.errors.teacher_id" class="invalid-feedback">
                {{ form.errors.teacher_id }}
              </div>
            </div>

            <!-- Grade Level -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">Grade Level <span class="text-danger">*</span></label>
              <select
                v-model="form.grade_level"
                class="form-select"
                :class="{ 'is-invalid': form.errors.grade_level }"
                required
              >
                <option value="">Select Grade</option>
                <option v-for="grade in [1,2,3,4,5,6,7,8,9,10,11,12]" :key="grade" :value="grade">
                  Grade {{ grade }}
                </option>
              </select>
              <div v-if="form.errors.grade_level" class="invalid-feedback">
                {{ form.errors.grade_level }}
              </div>
            </div>

            <!-- Section -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">Section <span class="text-danger">*</span></label>
              <input
                v-model="form.section"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.section }"
                placeholder="e.g., A, B, C"
                maxlength="10"
                required
              >
              <div v-if="form.errors.section" class="invalid-feedback">
                {{ form.errors.section }}
              </div>
            </div>

            <!-- Room -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">Room <span class="text-danger">*</span></label>
              <input
                v-model="form.room"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.room }"
                placeholder="e.g., Room 101, Lab A"
                maxlength="50"
                required
              >
              <div v-if="form.errors.room" class="invalid-feedback">
                {{ form.errors.room }}
              </div>
            </div>

            <!-- Day of Week -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">Day <span class="text-danger">*</span></label>
              <select
                v-model="form.day_of_week"
                class="form-select"
                :class="{ 'is-invalid': form.errors.day_of_week }"
                required
              >
                <option value="">Select Day</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
              </select>
              <div v-if="form.errors.day_of_week" class="invalid-feedback">
                {{ form.errors.day_of_week }}
              </div>
            </div>

            <!-- Start Time -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">Start Time <span class="text-danger">*</span></label>
              <input
                v-model="form.start_time"
                type="time"
                class="form-control"
                :class="{ 'is-invalid': form.errors.start_time }"
                required
              >
              <div v-if="form.errors.start_time" class="invalid-feedback">
                {{ form.errors.start_time }}
              </div>
            </div>

            <!-- End Time -->
            <div class="col-md-4">
              <label class="form-label fw-bold text-dark">End Time <span class="text-danger">*</span></label>
              <input
                v-model="form.end_time"
                type="time"
                class="form-control"
                :class="{ 'is-invalid': form.errors.end_time }"
                required
              >
              <div v-if="form.errors.end_time" class="invalid-feedback">
                {{ form.errors.end_time }}
              </div>
            </div>

            <!-- Semester -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark">Semester <span class="text-danger">*</span></label>
              <select
                v-model="form.semester"
                class="form-select"
                :class="{ 'is-invalid': form.errors.semester }"
                required
              >
                <option value="">Select Semester</option>
                <option value="1st Semester">1st Semester</option>
                <option value="2nd Semester">2nd Semester</option>
              </select>
              <div v-if="form.errors.semester" class="invalid-feedback">
                {{ form.errors.semester }}
              </div>
            </div>

            <!-- School Year -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark">School Year <span class="text-danger">*</span></label>
              <input
                v-model="form.school_year"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.school_year }"
                placeholder="e.g., 2024-2025"
                required
              >
              <div v-if="form.errors.school_year" class="invalid-feedback">
                {{ form.errors.school_year }}
              </div>
            </div>
          </div>

          <!-- Error Messages -->
          <div v-if="form.errors.time_conflict" class="alert alert-danger mt-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ form.errors.time_conflict }}
          </div>

          <!-- Form Actions -->
          <div class="mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between">
              <Link
                href="/admin/schedules"
                class="btn btn-outline-secondary"
              >
                <i class="fas fa-times me-1"></i>
                Cancel
              </Link>
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
              >
                <i v-if="form.processing" class="fas fa-spinner fa-spin me-2"></i>
                <i v-else class="fas fa-save me-2"></i>
                {{ form.processing ? 'Updating...' : 'Update Schedule' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js'

export default {
  name: 'SchedulesEdit',
  components: {
    AdminLayout,
    Link
  },
  props: {
    schedule: Object,
    subjects: Array,
    teachers: Array
  },
  data() {
    return {
      form: useForm({
        subject_id: this.schedule.subject_id,
        teacher_id: this.schedule.teacher_id,
        grade_level: this.schedule.grade_level,
        section: this.schedule.section,
        day_of_week: this.schedule.day_of_week,
        start_time: this.schedule.start_time,
        end_time: this.schedule.end_time,
        room: this.schedule.room,
        semester: this.schedule.semester,
        school_year: this.schedule.school_year
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
  },
  methods: {
    submit() {
      this.form.put(`/admin/schedules/${this.schedule.id}`, {
        onSuccess: () => {
          showSuccessToast('Schedule Updated!', 'The schedule has been updated successfully.')
        },
        onError: (errors) => {
          if (errors.time_conflict) {
            showErrorToast('Schedule Conflict', errors.time_conflict)
          } else {
            showErrorToast('Validation Error', 'Please check the form for errors.')
          }
        }
      })
    }
  }
}
</script>
    
<style scoped>
.form-control:focus,
.form-select:focus {
  border-color: #2c5aa0;
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  display: block;
}
</style>