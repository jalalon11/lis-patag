<template>
  <AdminLayout page-title="Teacher Details">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-user-circle me-3"></i>
          Teacher Profile
        </h2>
        <p class="text-muted mb-0">View detailed information about {{ teacher.full_name || `${teacher.first_name} ${teacher.last_name}` }}</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="btn-group" role="group">
          <Link :href="`/admin/teachers/${teacher.id}/edit`" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>
            Edit Teacher
          </Link>
          <Link href="/admin/teachers" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
          </Link>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Teacher Information Card -->
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-gradient-primary text-white text-center">
            <div class="teacher-avatar-large bg-white text-primary rounded-circle mx-auto mb-3">
              <i class="fas fa-user-tie"></i>
            </div>
            <h5 class="mb-1">{{ teacher.full_name || `${teacher.first_name} ${teacher.last_name}` }}</h5>
            <p class="mb-0 opacity-75">{{ teacher.teacher_id }}</p>
            <small class="opacity-75">{{ teacher.email }}</small>
          </div>
          <div class="card-body">
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">POSITION</label>
              <div class="mt-1">
                <span class="badge bg-primary-soft text-primary">
                  <i class="fas fa-briefcase me-1"></i>
                  {{ teacher.position }}
                </span>
              </div>
            </div>

            <div class="info-item mb-3" v-if="teacher.department">
              <label class="fw-bold text-muted small">DEPARTMENT</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-building me-2 text-muted"></i>
                {{ teacher.department }}
              </div>
            </div>

            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">STATUS</label>
              <div class="mt-1">
                <span
                  class="badge"
                  :class="{
                    'bg-success-soft text-success': teacher.employment_status === 'Active',
                    'bg-warning-soft text-warning': teacher.employment_status === 'Inactive',
                    'bg-secondary-soft text-secondary': teacher.employment_status === 'Resigned',
                    'bg-danger-soft text-danger': teacher.employment_status === 'Terminated'
                  }"
                >
                  <i
                    class="me-1"
                    :class="{
                      'fas fa-check-circle': teacher.employment_status === 'Active',
                      'fas fa-pause-circle': teacher.employment_status === 'Inactive',
                      'fas fa-sign-out-alt': teacher.employment_status === 'Resigned',
                      'fas fa-times-circle': teacher.employment_status === 'Terminated'
                    }"
                  ></i>
                  {{ teacher.employment_status || 'Active' }}
                </span>
              </div>
            </div>

            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">HIRE DATE</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-calendar me-2 text-muted"></i>
                {{ formatDate(teacher.hire_date) }}
              </div>
            </div>

            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">CONTACT</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-phone me-2 text-muted"></i>
                {{ teacher.contact_number }}
              </div>
            </div>

            <div class="info-item" v-if="teacher.emergency_contact">
              <label class="fw-bold text-muted small">EMERGENCY CONTACT</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-phone-alt me-2 text-muted"></i>
                {{ teacher.emergency_contact }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Teacher Details -->
      <div class="col-lg-8">
        <div class="row">
          <!-- Personal Information -->
          <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-id-card me-2"></i>
                  Personal Information
                </h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">FULL NAME</label>
                      <div class="mt-1 text-dark">
                        {{ teacher.full_name || `${teacher.first_name} ${teacher.middle_name ? teacher.middle_name + ' ' : ''}${teacher.last_name}${teacher.suffix ? ' ' + teacher.suffix : ''}` }}
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="teacher.birth_date">
                      <label class="fw-bold text-muted small">BIRTH DATE</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-birthday-cake me-2 text-muted"></i>
                        {{ formatDate(teacher.birth_date) }}
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="teacher.gender">
                      <label class="fw-bold text-muted small">GENDER</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-user me-2 text-muted"></i>
                        {{ teacher.gender }}
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="teacher.address">
                      <label class="fw-bold text-muted small">ADDRESS</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        {{ teacher.address }}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-item mb-3" v-if="teacher.salary">
                      <label class="fw-bold text-muted small">SALARY</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-money-bill-wave me-2 text-muted"></i>
                        ₱{{ Number(teacher.salary).toLocaleString() }}
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="teacher.qualifications">
                      <label class="fw-bold text-muted small">QUALIFICATIONS</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-graduation-cap me-2 text-muted"></i>
                        {{ teacher.qualifications }}
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="teacher.certifications">
                      <label class="fw-bold text-muted small">CERTIFICATIONS</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-certificate me-2 text-muted"></i>
                        {{ teacher.certifications }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Advised Sections -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-layer-group me-2"></i>
                  Advised Sections
                </h6>
              </div>
              <div class="card-body">
                <div v-if="teacher.advised_sections && teacher.advised_sections.length > 0">
                  <div v-for="section in teacher.advised_sections" :key="section.id" class="section-item mb-3">
                    <div class="d-flex align-items-center">
                      <div class="section-icon bg-info-soft text-info rounded-circle me-3">
                        <i class="fas fa-users"></i>
                      </div>
                      <div>
                        <div class="fw-bold">{{ section.name }}</div>
                        <small class="text-muted">Grade {{ section.grade_level }}</small>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-4">
                  <i class="fas fa-layer-group fa-2x text-muted mb-2"></i>
                  <p class="text-muted mb-0">No sections assigned</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Teaching Schedules -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-clock me-2"></i>
                  Teaching Schedules
                </h6>
              </div>
              <div class="card-body">
                <div v-if="teacher.schedules && teacher.schedules.length > 0">
                  <div v-for="schedule in teacher.schedules" :key="schedule.id" class="schedule-item mb-3">
                    <div class="d-flex align-items-center">
                      <div class="schedule-icon bg-warning-soft text-warning rounded-circle me-3">
                        <i class="fas fa-book"></i>
                      </div>
                      <div>
                        <div class="fw-bold">{{ schedule.subject?.name || 'Subject' }}</div>
                        <small class="text-muted">{{ schedule.section?.name || 'Section' }}</small>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-4">
                  <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                  <p class="text-muted mb-0">No schedules assigned</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics -->
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm bg-gradient-info text-white">
              <div class="card-body text-center">
                <i class="fas fa-layer-group fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ teacher.advised_sections?.length || 0 }}</h4>
                <small>Advised Sections</small>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white">
              <div class="card-body text-center">
                <i class="fas fa-book fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ teacher.schedules?.length || 0 }}</h4>
                <small>Teaching Subjects</small>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
              <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ totalStudents }}</h4>
                <small>Total Students</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

export default {
  name: 'ShowTeacher',
  components: {
    AdminLayout,
    Link
  },
  props: {
    teacher: Object
  },
  computed: {
    totalStudents() {
      if (!this.teacher.advised_sections) return 0
      return this.teacher.advised_sections.reduce((total, section) => {
        return total + (section.students_count || 0)
      }, 0)
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      })
    }
  }
}
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

.section-icon,
.schedule-icon {
  width: 35px;
  height: 35px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-item label {
  font-size: 0.75rem;
  letter-spacing: 0.5px;
}

.bg-primary-soft {
  background-color: rgba(44, 90, 160, 0.1);
  border: 1px solid rgba(44, 90, 160, 0.2);
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.bg-info-soft {
  background-color: rgba(23, 162, 184, 0.1);
  border: 1px solid rgba(23, 162, 184, 0.2);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
  border: 1px solid rgba(255, 193, 7, 0.2);
}

.text-primary {
  color: #2c5aa0 !important;
}

.text-success {
  color: #28a745 !important;
}

.text-info {
  color: #17a2b8 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #2c5aa0 0%, #1e3d6f 100%);
}

.bg-gradient-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-info {
  background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.section-item,
.schedule-item {
  padding: 0.75rem;
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  border: 1px solid #e9ecef;
}

.card {
  transition: transform 0.2s ease;
}

</style>
