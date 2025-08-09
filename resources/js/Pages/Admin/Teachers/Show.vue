<template>
  <AdminLayout page-title="Teacher Details">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-user-circle me-3"></i>
          Teacher Profile
        </h2>
        <p class="text-muted mb-0">View detailed information about {{ teacher.name }}</p>
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
            <h5 class="mb-1">{{ teacher.name }}</h5>
            <p class="mb-0 opacity-75">{{ teacher.email }}</p>
          </div>
          <div class="card-body">
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">ROLE</label>
              <div class="mt-1">
                <span class="badge bg-primary-soft text-primary">
                  <i class="fas fa-chalkboard-teacher me-1"></i>
                  Teacher
                </span>
              </div>
            </div>
            
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">STATUS</label>
              <div class="mt-1">
                <span class="badge bg-success-soft text-success">
                  <i class="fas fa-check-circle me-1"></i>
                  Active
                </span>
              </div>
            </div>
            
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">JOINED DATE</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-calendar me-2 text-muted"></i>
                {{ formatDate(teacher.created_at) }}
              </div>
            </div>
            
            <div class="info-item">
              <label class="fw-bold text-muted small">LAST UPDATED</label>
              <div class="mt-1 text-dark">
                <i class="fas fa-clock me-2 text-muted"></i>
                {{ formatDate(teacher.updated_at) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sections and Assignments -->
      <div class="col-lg-8">
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

.card:hover {
  transform: translateY(-2px);
}
</style>
