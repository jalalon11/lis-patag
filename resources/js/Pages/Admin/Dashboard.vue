<template>
  <AdminLayout page-title="Dashboard">
    <!-- Welcome Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-lg bg-gradient-primary text-white">
          <div class="card-body py-4">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="card-title mb-2 fw-bold">Welcome back, {{ $page.props.auth.user.name }}!</h2>
                <p class="card-text mb-0 opacity-90">
                  Here's what's happening at Patag Elementary School today.
                </p>
                <small class="opacity-75">{{ currentDate }}</small>
              </div>
              <div class="col-md-4 text-end">
                <div class="welcome-icon">
                  <i class="fas fa-school"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 stat-card stat-card-primary">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="stat-icon bg-primary-soft text-primary me-3">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="flex-grow-1">
                <div class="stat-label">Total Students</div>
                <div class="stat-value">{{ stats.totalStudents }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 stat-card stat-card-success">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="stat-icon bg-success-soft text-success me-3">
                <i class="fas fa-chalkboard-teacher"></i>
              </div>
              <div class="flex-grow-1">
                <div class="stat-label">Total Teachers</div>
                <div class="stat-value">{{ stats.totalTeachers }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 stat-card stat-card-info">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="stat-icon bg-info-soft text-info me-3">
                <i class="fas fa-user-friends"></i>
              </div>
              <div class="flex-grow-1">
                <div class="stat-label">Total Parents</div>
                <div class="stat-value">{{ stats.totalParents }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 stat-card stat-card-warning">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="stat-icon bg-warning-soft text-warning me-3">
                <i class="fas fa-layer-group"></i>
              </div>
              <div class="flex-grow-1">
                <div class="stat-label">Active Sections</div>
                <div class="stat-value">{{ stats.totalSections }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activities and Quick Actions -->
    <div class="row">
      <!-- Recent Activities -->
      <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-clock me-2"></i>
              Recent Activities
            </h6>
          </div>
          <div class="card-body">
            <div v-if="recentActivities && recentActivities.length > 0" class="timeline" style="max-height: 300px; overflow-y: auto;">
              <div v-for="activity in recentActivities" :key="activity.id" class="timeline-item mb-3">
                <div class="d-flex">
                  <div class="timeline-marker me-3">
                    <i :class="activity.icon" class="text-primary"></i>
                  </div>
                  <div class="timeline-content flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <h6 class="mb-1 fw-bold">{{ activity.title }}</h6>
                        <p class="text-muted mb-1">{{ activity.description }}</p>
                      </div>
                      <small class="text-muted">{{ activity.time }}</small>
                    </div>
                    <div v-if="activity.user" class="mt-1">
                      <small class="text-primary">
                        <i class="fas fa-user me-1"></i>
                        {{ activity.user }}
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-5">
              <i class="fas fa-clock fa-3x text-muted mb-3"></i>
              <h6 class="text-muted">No recent activities</h6>
              <p class="text-muted mb-0">Activities will appear here as you use the system</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-bolt me-2"></i>
              Quick Actions
            </h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-3">
              <Link href="/admin/admission" class="btn btn-outline-primary btn-lg quick-action-btn">
                <i class="fas fa-user-plus me-2"></i>
                New Student Admission
              </Link>
              <Link href="/admin/teachers/create" class="btn btn-outline-success btn-lg quick-action-btn">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                Add Teacher
              </Link>
              <Link href="/admin/parents/create" class="btn btn-outline-info btn-lg quick-action-btn">
                <i class="fas fa-user-friends me-2"></i>
                Add Parent/Guardian
              </Link>
              <Link href="/admin/subjects/create" class="btn btn-outline-warning btn-lg quick-action-btn">
                <i class="fas fa-book-open me-2"></i>
                Add Subject
              </Link>
              <Link href="/admin/reports/sf1" class="btn btn-outline-secondary btn-lg quick-action-btn">
                <i class="fas fa-file-alt me-2"></i>
                Generate SF1 Report
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Events -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-calendar-check me-2"></i>
              Upcoming Events & Reminders
            </h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div v-for="event in upcomingEvents" :key="event.id" class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center mb-2">
                    <i :class="event.icon" class="text-primary me-2"></i>
                    <h6 class="mb-0">{{ event.title }}</h6>
                  </div>
                  <p class="text-muted mb-2">{{ event.description }}</p>
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ event.date }}
                  </small>
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
  name: 'Dashboard',
  components: {
    AdminLayout,
    Link
  },
  props: {
    stats: {
      type: Object,
      default: () => ({
        totalStudents: 0,
        totalTeachers: 0,
        totalParents: 0,
        totalSections: 0,
        currentSchoolYear: '2024-2025'
      })
    },
    recentActivities: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    currentDate() {
      return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }
  },
  data() {
    return {
      upcomingEvents: [
        {
          id: 1,
          icon: 'fas fa-calendar-check',
          title: 'Enrollment Period',
          description: 'Registration for new students opens',
          date: 'March 15, 2024'
        },
        {
          id: 2,
          icon: 'fas fa-graduation-cap',
          title: 'Graduation Ceremony',
          description: 'Grade 6 graduation ceremony',
          date: 'March 25, 2024'
        },
        {
          id: 3,
          icon: 'fas fa-users',
          title: 'Parent-Teacher Conference',
          description: 'Quarterly parent-teacher meeting',
          date: 'April 5, 2024'
        }
      ]
    }
  }
}
</script>

<style scoped>
/* Welcome Section */
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.welcome-icon {
  font-size: 4rem;
  opacity: 0.2;
}

/* Statistics Cards */
.stat-card {
  border-left: 4px solid transparent;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.stat-card-primary {
  border-left-color: #007bff;
}

.stat-card-success {
  border-left-color: #28a745;
}

.stat-card-info {
  border-left-color: #17a2b8;
}

.stat-card-warning {
  border-left-color: #ffc107;
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.25rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #2d3748;
  line-height: 1;
}

/* Soft Background Colors */
.bg-primary-soft {
  background-color: rgba(0, 123, 255, 0.1);
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
}

.bg-info-soft {
  background-color: rgba(23, 162, 184, 0.1);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
}

/* Timeline */
.timeline-marker {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgba(0, 123, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border: 2px solid #e9ecef;
}

.timeline-content {
  padding-left: 1rem;
}

.timeline-item {
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #f8f9fa;
}

.timeline-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

/* Quick Actions */
.quick-action-btn {
  padding: 1rem 1.5rem;
  font-weight: 600;
  border-width: 2px;
  transition: all 0.3s ease;
}

.quick-action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Cards */
.card {
  border-radius: 12px;
  border: none;
  transition: all 0.3s ease;
}

.card-header {
  background-color: #f8f9fa !important;
  border-bottom: 1px solid #e9ecef;
  border-radius: 12px 12px 0 0 !important;
  padding: 1.25rem 1.5rem;
}

.card-body {
  padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .stat-value {
    font-size: 1.5rem;
  }

  .stat-icon {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }

  .welcome-icon {
    font-size: 3rem;
  }

  .quick-action-btn {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }
}
</style>
