<template>
  <AdminLayout page-title="Dashboard">
    <!-- Welcome Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 bg-primary text-white">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h3 class="card-title mb-2">Welcome back, {{ $page.props.auth.user.name }}!</h3>
                <p class="card-text mb-0">
                  Here's what's happening at Patag Elementary School today.
                </p>
              </div>
              <div class="col-md-4 text-end">
                <i class="fas fa-school" style="font-size: 4rem; opacity: 0.3;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                  Total Students
                </div>
                <div class="h5 mb-0 fw-bold text-gray-800">{{ stats.totalStudents }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                  Total Teachers
                </div>
                <div class="h5 mb-0 fw-bold text-gray-800">{{ stats.totalTeachers }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                  Active Sections
                </div>
                <div class="h5 mb-0 fw-bold text-gray-800">{{ stats.totalSections }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-layer-group fa-2x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                  School Year
                </div>
                <div class="h5 mb-0 fw-bold text-gray-800">{{ stats.currentSchoolYear }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar-alt fa-2x text-info"></i>
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
            <div class="timeline">
              <div v-for="activity in recentActivities" :key="activity.id" class="timeline-item mb-3">
                <div class="d-flex">
                  <div class="timeline-marker me-3">
                    <i :class="activity.icon" class="text-primary"></i>
                  </div>
                  <div class="timeline-content">
                    <h6 class="mb-1">{{ activity.title }}</h6>
                    <p class="text-muted mb-1">{{ activity.description }}</p>
                    <small class="text-muted">{{ activity.time }}</small>
                  </div>
                </div>
              </div>
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
            <div class="d-grid gap-2">
              <Link href="/admin/admission" class="btn btn-outline-primary">
                <i class="fas fa-user-plus me-2"></i>
                New Student Admission
              </Link>
              <Link href="/admin/teachers/create" class="btn btn-outline-success">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                Add Teacher
              </Link>
              <Link href="/admin/subjects/create" class="btn btn-outline-warning">
                <i class="fas fa-book-open me-2"></i>
                Add Subject
              </Link>
              <Link href="/admin/reports/sf1" class="btn btn-outline-info">
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
        totalSections: 0,
        currentSchoolYear: '2024-2025'
      })
    }
  },
  data() {
    return {
      recentActivities: [
        {
          id: 1,
          icon: 'fas fa-user-plus',
          title: 'New Student Enrolled',
          description: 'Juan Dela Cruz has been enrolled in Grade 1-A',
          time: '2 hours ago'
        },
        {
          id: 2,
          icon: 'fas fa-chalkboard-teacher',
          title: 'Teacher Account Created',
          description: 'Maria Santos has been added as a Grade 3 teacher',
          time: '5 hours ago'
        },
        {
          id: 3,
          icon: 'fas fa-book-open',
          title: 'Subject Added',
          description: 'Mathematics subject has been added to Grade 2 curriculum',
          time: '1 day ago'
        }
      ],
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
.timeline-marker {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: var(--light-color);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.text-xs {
  font-size: 0.75rem;
}

.card {
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-2px);
}

.btn {
  transition: all 0.2s ease-in-out;
}

.btn:hover {
  transform: translateY(-1px);
}
</style>
