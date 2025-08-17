<template>
  <AdminLayout page-title="Parent/Guardian Details">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-user-circle me-3"></i>
          Parent/Guardian Profile
        </h2>
        <p class="text-muted mb-0">View detailed information about {{ parent.full_name }}</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="btn-group" role="group">
          <Link :href="`/admin/parents/${parent.id}/edit`" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>
            Edit Parent
          </Link>
          <Link href="/admin/parents" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
          </Link>
        </div>
      </div>
    </div>

    <!-- Parent Information Card -->
    <div class="row mb-4">
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-gradient-info text-white">
            <h6 class="m-0 fw-bold">
              <i class="fas fa-user me-2"></i>
              Basic Information
            </h6>
          </div>
          <div class="card-body text-center">
            <div class="parent-avatar-large bg-gradient-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="fas fa-user fa-2x"></i>
            </div>
            <h5 class="mb-1">{{ parent.full_name }}</h5>
            <p class="text-muted mb-3">{{ parent.email }}</p>
            
            <div class="info-list text-start">
              <div class="info-item mb-3">
                <label class="fw-bold text-muted small">RELATIONSHIP</label>
                <div class="mt-1">
                  <span class="badge bg-primary-soft text-primary" v-if="parent.relationship">
                    <i class="fas fa-heart me-1"></i>
                    {{ parent.relationship.charAt(0).toUpperCase() + parent.relationship.slice(1) }}
                  </span>
                  <span class="text-muted" v-else>Not specified</span>
                </div>
              </div>

              <div class="info-item mb-3">
                <label class="fw-bold text-muted small">PHONE</label>
                <div class="mt-1 text-dark">
                  <i class="fas fa-phone me-2 text-muted"></i>
                  {{ parent.phone || 'Not provided' }}
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
                  {{ formatDate(parent.created_at) }}
                </div>
              </div>

              <div class="info-item">
                <label class="fw-bold text-muted small">LAST UPDATED</label>
                <div class="mt-1 text-dark">
                  <i class="fas fa-clock me-2 text-muted"></i>
                  {{ formatDate(parent.updated_at) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <!-- Statistics Cards -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="card border-0 bg-gradient-info text-white h-100">
              <div class="card-body text-center">
                <i class="fas fa-child fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ totalStudents }}</h4>
                <small>Total Students</small>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card border-0 bg-gradient-success text-white h-100">
              <div class="card-body text-center">
                <i class="fas fa-graduation-cap fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ activeStudents }}</h4>
                <small>Active Students</small>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card border-0 bg-gradient-warning text-white h-100">
              <div class="card-body text-center">
                <i class="fas fa-school fa-2x mb-2 opacity-75"></i>
                <h4 class="mb-1">{{ totalSections }}</h4>
                <small>Sections</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Address Card -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-map-marker-alt me-2"></i>
              Address Information
            </h6>
          </div>
          <div class="card-body">
            <div v-if="parent.address" class="text-dark">
              <i class="fas fa-home me-2 text-muted"></i>
              {{ parent.address }}
            </div>
            <div v-else class="text-muted">
              <i class="fas fa-home me-2"></i>
              No address provided
            </div>
          </div>
        </div>

        <!-- Students Card -->
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-users me-2"></i>
              Students
            </h6>
          </div>
          <div class="card-body">
            <div v-if="parent.students && parent.students.length > 0">
              <div class="student-item mb-3" v-for="student in parent.students" :key="student.id">
                <div class="d-flex align-items-center">
                  <div class="student-icon bg-info-soft text-info rounded-circle me-3">
                    <i class="fas fa-user-graduate"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-bold">{{ student.name }}</div>
                    <small class="text-muted">
                      <i class="fas fa-layer-group me-1"></i>
                      {{ student.section ? student.section.name : 'No section assigned' }}
                    </small>
                  </div>
                  <div>
                    <span class="badge bg-success-soft text-success">
                      <i class="fas fa-check-circle me-1"></i>
                      Active
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4">
              <i class="fas fa-child fa-2x text-muted mb-2"></i>
              <p class="text-muted mb-0">No students assigned to this parent/guardian</p>
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
  name: 'ShowParent',
  components: {
    AdminLayout,
    Link
  },
  props: {
    parent: Object
  },
  computed: {
    totalStudents() {
      return this.parent.students ? this.parent.students.length : 0
    },
    activeStudents() {
      if (!this.parent.students) return 0
      return this.parent.students.filter(student => student.status === 'active').length
    },
    totalSections() {
      if (!this.parent.students) return 0
      const sections = new Set()
      this.parent.students.forEach(student => {
        if (student.section) {
          sections.add(student.section.id)
        }
      })
      return sections.size
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'Asia/Manila'
      })
    }
  }
}
</script>

<style scoped>
.parent-avatar-large {
  width: 80px;
  height: 80px;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #2c5aa0 0%, #1e3d6f 100%);
}

.bg-gradient-info {
  background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.bg-gradient-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-primary-soft {
  background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.bg-info-soft {
  background-color: rgba(23, 162, 184, 0.1);
  border: 1px solid rgba(23, 162, 184, 0.2);
}

.text-info {
  color: #17a2b8 !important;
}

.text-success {
  color: #28a745 !important;
}

.info-item label {
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #6c757d;
}

.student-icon {
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
}

.card {
  border-radius: 0.75rem;
}

.card-header {
  border-radius: 0.75rem 0.75rem 0 0;
  border-bottom: none;
  padding: 1.5rem;
}
</style>
