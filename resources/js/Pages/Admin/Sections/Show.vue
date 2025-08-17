<template>
  <AdminLayout page-title="Section Details">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-layer-group me-3"></i>
          Section Profile
        </h2>
        <p class="text-muted mb-0">View detailed information about {{ section.grade_level_display || section.grade_level }} - {{ section.section_name }}</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="btn-group" role="group">
          <Link :href="`/admin/sections/${section.id}/edit`" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>
            Edit Section
          </Link>
          <Link href="/admin/sections" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
          </Link>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Section Information Card -->
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-gradient-primary text-white text-center">
            <div class="section-avatar-large bg-white text-primary rounded-circle mx-auto mb-3">
              <i class="fas fa-layer-group"></i>
            </div>
            <h5 class="mb-1">{{ section.grade_level_display || section.grade_level }} - {{ section.section_name }}</h5>
            <p class="mb-0 opacity-75">{{ section.school_year?.year_name }}</p>
            <small class="opacity-75" v-if="section.room_number">Room {{ section.room_number }}</small>
          </div>
          <div class="card-body">
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">CAPACITY</label>
              <div class="mt-1">
                <span class="badge bg-primary-soft text-primary">
                  <i class="fas fa-users me-1"></i>
                  {{ section.capacity }} students
                </span>
              </div>
            </div>
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">CURRENT ENROLLMENT</label>
              <div class="mt-1">
                <span 
                  class="badge me-2"
                  :class="{
                    'bg-success-soft text-success': section.current_enrollment < section.capacity * 0.8,
                    'bg-warning-soft text-warning': section.current_enrollment >= section.capacity * 0.8 && section.current_enrollment < section.capacity,
                    'bg-danger-soft text-danger': section.current_enrollment >= section.capacity
                  }"
                >
                  {{ section.current_enrollment }}/{{ section.capacity }}
                </span>
                <div class="progress mt-2" style="height: 6px;">
                  <div 
                    class="progress-bar"
                    :class="{
                      'bg-success': section.current_enrollment < section.capacity * 0.8,
                      'bg-warning': section.current_enrollment >= section.capacity * 0.8 && section.current_enrollment < section.capacity,
                      'bg-danger': section.current_enrollment >= section.capacity
                    }"
                    :style="{ width: (section.current_enrollment / section.capacity * 100) + '%' }"
                  ></div>
                </div>
              </div>
            </div>
            <div class="info-item mb-3">
              <label class="fw-bold text-muted small">STATUS</label>
              <div class="mt-1">
                <span 
                  class="badge"
                  :class="{
                    'bg-success-soft text-success': section.is_active,
                    'bg-danger-soft text-danger': !section.is_active
                  }"
                >
                  <i
                    class="me-1"
                    :class="{
                      'fas fa-check-circle': section.is_active,
                      'fas fa-times-circle': !section.is_active
                    }"
                  ></i>
                  {{ section.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
            <div class="info-item" v-if="section.adviser">
              <label class="fw-bold text-muted small">ADVISER</label>
              <div class="mt-1">
                <div class="d-flex align-items-center">
                  <div class="adviser-icon bg-info-soft text-info rounded-circle me-2">
                    <i class="fas fa-user-tie"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-dark">{{ section.adviser.full_name }}</div>
                    <small class="text-muted">{{ section.adviser.email }}</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="info-item" v-else>
              <label class="fw-bold text-muted small">ADVISER</label>
              <div class="mt-1 text-muted">No adviser assigned</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Section Details -->
      <div class="col-lg-8">
        <div class="row">
          <!-- Basic Information -->
          <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-info-circle me-2"></i>
                  Section Information
                </h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">SECTION NAME</label>
                      <div class="mt-1 text-dark">{{ section.section_name }}</div>
                    </div>
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">GRADE LEVEL</label>
                      <div class="mt-1">
                        <span class="badge bg-primary">{{ section.grade_level_display || section.grade_level }}</span>
                      </div>
                    </div>
                    <div class="info-item mb-3" v-if="section.room_number">
                      <label class="fw-bold text-muted small">ROOM NUMBER</label>
                      <div class="mt-1">
                        <span class="badge bg-secondary">
                          <i class="fas fa-door-open me-1"></i>
                          {{ section.room_number }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">SCHOOL YEAR</label>
                      <div class="mt-1 text-dark">{{ section.school_year?.year_name || 'Not assigned' }}</div>
                    </div>
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">CAPACITY</label>
                      <div class="mt-1 text-dark">{{ section.capacity }} students</div>
                    </div>
                    <div class="info-item mb-3">
                      <label class="fw-bold text-muted small">CURRENT ENROLLMENT</label>
                      <div class="mt-1 text-dark">{{ section.current_enrollment }} students</div>
                    </div>
                  </div>
                </div>
                <div v-if="section.description" class="row">
                  <div class="col-12">
                    <div class="info-item">
                      <label class="fw-bold text-muted small">DESCRIPTION</label>
                      <div class="mt-1 text-dark">{{ section.description }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Enrolled Students -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-users me-2"></i>
                  Enrolled Students
                </h6>
              </div>
              <div class="card-body">
                <div v-if="section.enrollments && section.enrollments.length > 0">
                  <div v-for="enrollment in section.enrollments.slice(0, 5)" :key="enrollment.id" class="student-item mb-3">
                    <div class="d-flex align-items-center">
                      <div class="student-icon bg-success-soft text-success rounded-circle me-3">
                        <i class="fas fa-user-graduate"></i>
                      </div>
                      <div>
                        <div class="fw-bold">{{ enrollment.student?.full_name || `${enrollment.student?.first_name} ${enrollment.student?.last_name}` }}</div>
                        <small class="text-muted">{{ enrollment.student?.student_id }}</small>
                      </div>
                    </div>
                  </div>
                  <div v-if="section.enrollments.length > 5" class="text-center">
                    <small class="text-muted">and {{ section.enrollments.length - 5 }} more students...</small>
                  </div>
                </div>
                <div v-else class="text-center py-4">
                  <i class="fas fa-users fa-3x text-muted mb-3"></i>
                  <p class="text-muted mb-0">No students enrolled yet</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Section Statistics -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-header bg-white border-bottom">
                <h6 class="m-0 fw-bold text-primary">
                  <i class="fas fa-chart-bar me-2"></i>
                  Statistics
                </h6>
              </div>
              <div class="card-body">
                <div class="stat-item mb-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Enrollment Rate</span>
                    <span class="fw-bold">{{ Math.round((section.current_enrollment / section.capacity) * 100) }}%</span>
                  </div>
                  <div class="progress mt-1" style="height: 4px;">
                    <div 
                      class="progress-bar bg-primary" 
                      :style="{ width: (section.current_enrollment / section.capacity * 100) + '%' }"
                    ></div>
                  </div>
                </div>
                <div class="stat-item mb-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Available Slots</span>
                    <span class="fw-bold">{{ section.capacity - section.current_enrollment }}</span>
                  </div>
                </div>
                <div class="stat-item mb-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Status</span>
                    <span 
                      class="badge"
                      :class="{
                        'bg-success': section.is_active,
                        'bg-danger': !section.is_active
                      }"
                    >
                      {{ section.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Has Adviser</span>
                    <span 
                      class="badge"
                      :class="{
                        'bg-success': section.adviser,
                        'bg-warning': !section.adviser
                      }"
                    >
                      {{ section.adviser ? 'Yes' : 'No' }}
                    </span>
                  </div>
                </div>
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
  name: 'ShowSection',
  components: {
    AdminLayout,
    Link
  },
  props: {
    section: Object
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

.section-avatar-large {
  width: 80px;
  height: 80px;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.adviser-icon {
  width: 36px;
  height: 36px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.student-icon {
  width: 36px;
  height: 36px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
}

.bg-primary-soft {
  background-color: rgba(44, 90, 160, 0.1);
  border: 1px solid rgba(44, 90, 160, 0.2);
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
  border: 1px solid rgba(255, 193, 7, 0.2);
}

.bg-danger-soft {
  background-color: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.2);
}

.bg-info-soft {
  background-color: rgba(23, 162, 184, 0.1);
  border: 1px solid rgba(23, 162, 184, 0.2);
}

.text-primary {
  color: var(--primary-color) !important;
}

.text-success {
  color: #28a745 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.text-danger {
  color: #dc3545 !important;
}

.text-info {
  color: #17a2b8 !important;
}

.card {
  border-radius: 8px;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.info-item {
  margin-bottom: 1rem;
}

.info-item:last-child {
  margin-bottom: 0;
}

.info-item label {
  font-size: 0.75rem;
  letter-spacing: 0.5px;
  margin-bottom: 0.25rem;
}

.student-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.student-item:last-child {
  border-bottom: none;
}

.stat-item {
  padding: 0.5rem 0;
}

.btn-warning {
  background: #ffc107;
  border: none;
  color: #212529;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-warning:hover {
  background: #e0a800;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
}

.btn-outline-secondary {
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-outline-secondary:hover {
  transform: translateY(-1px);
}

.progress {
  border-radius: 10px;
  overflow: hidden;
}

.progress-bar {
  border-radius: 10px;
}

.badge {
  font-size: 0.75rem;
  padding: 0.375rem 0.75rem;
  border-radius: 0.375rem;
}

@media (max-width: 768px) {
  .section-avatar-large {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }

  .adviser-icon,
  .student-icon {
    width: 32px;
    height: 32px;
    font-size: 0.75rem;
  }

  .btn-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .btn-group .btn {
    font-size: 0.875rem;
  }

  .card-body {
    padding: 1rem;
  }
}
</style>
