<template>
  <ParentLayout page-title="Dashboard">
    <div>
      <!-- Welcome Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm bg-gradient-success text-white">
            <div class="card-body py-4">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <img src="/storage/Patag_ES-Logo.png" alt="Patag Elementary School Logo" style="width: 4rem; height: auto;">
              </div>
                <div>
                  <h3 class="mb-1">Welcome to Parent Portal</h3>
                  <p class="mb-0 opacity-90">Stay connected with your children's academic journey at Patag Elementary School</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="text-success mb-2">
                <i class="fas fa-child fa-2x"></i>
              </div>
              <h4 class="fw-bold text-success">2</h4>
              <small class="text-muted">Children Enrolled</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="text-primary mb-2">
                <i class="fas fa-calendar-check fa-2x"></i>
              </div>
              <h4 class="fw-bold text-primary">95%</h4>
              <small class="text-muted">Average Attendance</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="text-warning mb-2">
                <i class="fas fa-medal fa-2x"></i>
              </div>
              <h4 class="fw-bold text-warning">B+</h4>
              <small class="text-muted">Average Grade</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="text-info mb-2">
                <i class="fas fa-bullhorn fa-2x"></i>
              </div>
              <h4 class="fw-bold text-info">3</h4>
              <small class="text-muted">New Announcements</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="row">
        <!-- My Children -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-success">
                  <i class="fas fa-child me-2"></i>
                  My Children
                </h5>
                <Link href="/parent/children" class="btn btn-sm btn-outline-success">
                  View All
                </Link>
              </div>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-12">
                  <div class="p-3 rounded bg-light border-start border-3 border-success">
                    <div class="d-flex align-items-center">
                      <div class="bg-success text-white rounded-circle p-2 me-3">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Juan Dela Cruz</h6>
                        <small class="text-muted">Grade 3 - Section A</small>
                      </div>
                      <div class="text-end">
                        <span class="badge bg-success">Present</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="p-3 rounded bg-light border-start border-3 border-success">
                    <div class="d-flex align-items-center">
                      <div class="bg-success text-white rounded-circle p-2 me-3">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Maria Dela Cruz</h6>
                        <small class="text-muted">Grade 1 - Section B</small>
                      </div>
                      <div class="text-end">
                        <span class="badge bg-success">Present</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Announcements -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-success">
                  <i class="fas fa-bullhorn me-2"></i>
                  Recent Announcements
                </h5>
                <Link href="/parent/communication/announcements" class="btn btn-sm btn-outline-success">
                  View All
                </Link>
              </div>
            </div>
            <div class="card-body">
              <div class="list-group list-group-flush">
                <div class="list-group-item px-0 border-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="bg-primary text-white rounded-circle p-2">
                        <i class="fas fa-calendar-alt"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Parent-Teacher Conference</h6>
                      <p class="mb-1 text-muted small">Schedule: March 15-16, 2024</p>
                      <small class="text-muted">2 days ago</small>
                    </div>
                  </div>
                </div>
                <div class="list-group-item px-0 border-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="bg-warning text-white rounded-circle p-2">
                        <i class="fas fa-exclamation-triangle"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">School Closure Notice</h6>
                      <p class="mb-1 text-muted small">Classes suspended due to weather</p>
                      <small class="text-muted">3 days ago</small>
                    </div>
                  </div>
                </div>
                <div class="list-group-item px-0 border-0">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="bg-info text-white rounded-circle p-2">
                        <i class="fas fa-book"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">New Learning Materials</h6>
                      <p class="mb-1 text-muted small">Updated modules available for download</p>
                      <small class="text-muted">1 week ago</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Academic Performance -->
        <div class="col-lg-12 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
              <h5 class="mb-0 fw-bold text-success">
                <i class="fas fa-chart-line me-2"></i>
                Academic Performance Overview
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <h6 class="fw-bold mb-3">Juan Dela Cruz - Grade 3</h6>
                  <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small>Mathematics</small>
                      <small class="fw-bold">88%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: 90%"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small>Writing</small>
                      <small class="fw-bold">87%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-primary" role="progressbar" style="width: 87%"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small>Mathematics</small>
                      <small class="fw-bold">83%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 83%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-12 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
              <h5 class="mb-0 fw-bold text-success">
                <i class="fas fa-bolt me-2"></i>
                Quick Actions
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <Link href="/parent/academic/grades" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="fas fa-medal fa-2x mb-2"></i>
                    <span>View Grades</span>
                  </Link>
                </div>
                <div class="col-md-3">
                  <Link href="/parent/attendance" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                    <span>Check Attendance</span>
                  </Link>
                </div>
                <div class="col-md-3">
                  <Link href="/parent/academic/assignments" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="fas fa-tasks fa-2x mb-2"></i>
                    <span>View Assignments</span>
                  </Link>
                </div>
                <div class="col-md-3">
                  <Link href="/parent/communication/feedback" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="fas fa-comment-dots fa-2x mb-2"></i>
                    <span>Teacher Feedback</span>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </ParentLayout>
</template>

<script setup>
import ParentLayout from '@/Layouts/ParentLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.bg-gradient-success {
  background: linear-gradient(135deg, #198754 0%, #20c997 100%);
}

.progress-bar {
  transition: width 0.6s ease;
}

.card {
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-2px);
}

.btn:hover {
  transform: translateY(-1px);
}
</style>