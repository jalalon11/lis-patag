<template>
  <AdminLayout page-title="Dashboard">
    <!-- Top Section: Welcome and Stats -->
    <div class="row mb-4">
      <!-- Welcome Section - Left Side -->
      <div class="col-7">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
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
                  <img src="/storage/app/public/Patag_ES-Logo.png" alt="Patag Elementary School Logo" style="width: 4rem; height: auto;">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics Cards - Right Side -->
      <div class="col-5">
        <div class="row g-2">
          <!-- Total Students -->
          <div class="col-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
              <div class="card-body p-3 text-center">
                <div class="stat-value text-primary mb-1">{{ stats.totalStudents }}</div>
                <div class="stat-label text-secondary">Total Students</div>
              </div>
            </div>
          </div>

          <!-- Total Teachers -->
          <div class="col-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
              <div class="card-body p-3 text-center">
                <div class="stat-value text-primary mb-1">{{ stats.totalTeachers }}</div>
                <div class="stat-label text-secondary">Total Teachers</div>
              </div>
            </div>
          </div>

          <!-- Total Parents -->
          <div class="col-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
              <div class="card-body p-3 text-center">
                <div class="stat-value text-primary mb-1">{{ stats.totalParents }}</div>
                <div class="stat-label text-secondary">Total Parents</div>
              </div>
            </div>
          </div>

          <!-- Active Sections -->
          <div class="col-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
              <div class="card-body p-3 text-center">
                <div class="stat-value text-primary mb-1">{{ stats.totalSections }}</div>
                <div class="stat-label text-secondary">Active Sections</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions - Horizontal Row -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-bolt me-2"></i>
              Quick Actions
            </h6>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col">
                <Link href="/admin/admission" class="btn btn-primary w-100">
                  <i class="fas fa-user-plus me-2"></i>
                  New Student
                </Link>
              </div>
              <div class="col">
                <Link href="/admin/teachers/create" class="btn btn-primary w-100">
                  <i class="fas fa-chalkboard-teacher me-2"></i>
                  Add Teacher
                </Link>
              </div>
              <div class="col">
                <Link href="/admin/parents/create" class="btn btn-primary w-100">
                  <i class="fas fa-user-friends me-2"></i>
                  Add Parent
                </Link>
              </div>
              <div class="col">
                <Link href="/admin/subjects/create" class="btn btn-primary w-100">
                  <i class="fas fa-book-open me-2"></i>
                  Add Subject
                </Link>
              </div>
              <div class="col">
                <Link href="/admin/reports/sf1" class="btn btn-primary w-100">
                  <i class="fas fa-file-alt me-2"></i>
                  SF1 Report
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Announcements -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-bullhorn me-2"></i>
              Announcements
            </h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div v-for="announcement in announcements" :key="announcement.id" class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center mb-2">
                    <i :class="announcement.icon" class="text-primary me-2"></i>
                    <h6 class="mb-0">{{ announcement.title }}</h6>
                  </div>
                  <p class="text-muted mb-2">{{ announcement.description }}</p>
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ announcement.date }}
                  </small>
                </div>
              </div>
            </div>
            <div v-if="!announcements || announcements.length === 0" class="text-center py-5">
              <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
              <h6 class="text-muted">No announcements</h6>
              <p class="text-muted mb-0">Announcements will appear here when posted</p>
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
    announcements: {
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
        day: 'numeric',
        timeZone: 'Asia/Manila'
      })
    }
  },
  data() {
    return {
      announcements: [
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
.welcome-icon {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1;
}

.stat-label {
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Cards */
.card {
  border-radius: 8px;
  border: none;
}

.card-header {
  border-bottom: 1px solid #e9ecef;
  border-radius: 8px 8px 0 0 !important;
  padding: 1rem 1.25rem;
}

.card-body {
  padding: 1.25rem;
}

/* Quick Actions Card Body */
.card-body .btn {
  padding: 0.75rem 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
  .stat-value {
    font-size: 1.5rem;
  }
  
  .stat-label {
    font-size: 0.7rem;
  }
  
  .card-body .btn {
    padding: 0.5rem 0.25rem;
    font-size: 0.75rem;
  }
  
  .card-body .btn i {
    display: none;
  }
}

@media (max-width: 576px) {
  .row.g-2 > .col {
    margin-bottom: 0.5rem;
  }
}
</style>