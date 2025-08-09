<template>
  <AdminLayout page-title="Teachers Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-chalkboard-teacher me-3"></i>
          Teachers Management
        </h2>
        <p class="text-muted mb-0">Manage teacher accounts, credentials, and assignments</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/teachers/create"
          class="btn btn-primary btn-sm px-3 shadow-sm"
          aria-label="Add new teacher"
        >
          <i class="fas fa-plus me-1"></i>
          Add Teacher
        </Link>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-gradient-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1">
                <h6 class="card-title mb-1">Total Teachers</h6>
                <h3 class="mb-0">{{ teachers.total || teachers.data.length }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-users fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-gradient-success text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1">
                <h6 class="card-title mb-1">Active Teachers</h6>
                <h3 class="mb-0">{{ teachers.data.length }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-user-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-gradient-info text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1">
                <h6 class="card-title mb-1">With Sections</h6>
                <h3 class="mb-0">{{ teachersWithSections }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-layer-group fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-gradient-warning text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-grow-1">
                <h6 class="card-title mb-1">New This Month</h6>
                <h3 class="mb-0">{{ newThisMonth }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-user-plus fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control border-start-0"
                placeholder="Search teachers by name or email..."
                @input="filterTeachers"
              >
            </div>
          </div>
          <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'all' }"
                @click="setFilter('all')"
              >
                All Teachers
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'with_sections' }"
                @click="setFilter('with_sections')"
              >
                With Sections
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'without_sections' }"
                @click="setFilter('without_sections')"
              >
                Without Sections
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Teachers Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Teachers Directory
          </h6>
          <span class="badge bg-white text-primary">{{ filteredTeachers.length }} teachers</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 teachers-table">
            <thead class="table-light">
              <tr>
                <th class="border-0 ps-4 fw-bold">Teacher Information</th>
                <th class="border-0 fw-bold">Contact</th>
                <th class="border-0 fw-bold">Assignments</th>
                <th class="border-0 fw-bold">Status</th>
                <th class="border-0 text-center fw-bold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="teacher in filteredTeachers" :key="teacher.id" class="teacher-row">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="teacher-avatar bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                      <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ teacher.name }}</div>
                      <small class="text-muted">
                        <i class="fas fa-id-badge me-1"></i>
                        Teacher ID: {{ teacher.id }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <div class="text-dark mb-1">
                      <i class="fas fa-envelope me-2 text-muted"></i>
                      {{ teacher.email }}
                    </div>
                    <small class="text-muted">
                      <i class="fas fa-calendar me-1"></i>
                      Joined {{ formatDate(teacher.created_at) }}
                    </small>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-info-soft text-info mb-1">
                      <i class="fas fa-layer-group me-1"></i>
                      {{ teacher.advised_sections_count || 0 }} sections
                    </span>
                    <div v-if="teacher.advised_sections_count > 0">
                      <small class="text-muted">Class Adviser</small>
                    </div>
                    <div v-else>
                      <small class="text-warning">No sections assigned</small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <span class="badge bg-success-soft text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Active
                  </span>
                </td>
                <td class="text-center py-3">
                  <div class="btn-group" role="group">
                    <button
                      @click="viewTeacher(teacher)"
                      class="btn btn-sm btn-outline-primary"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="editTeacher(teacher)"
                      class="btn btn-sm btn-outline-warning"
                      title="Edit Teacher"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="confirmDelete(teacher)"
                      class="btn btn-sm btn-outline-danger"
                      title="Delete Teacher"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredTeachers.length === 0">
                <td colspan="5" class="text-center py-5">
                  <div class="empty-state">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No teachers found</h5>
                    <p class="text-muted mb-3">
                      {{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first teacher' }}
                    </p>
                    <Link v-if="!searchQuery" href="/admin/teachers/create" class="btn btn-primary">
                      <i class="fas fa-plus me-2"></i>
                      Add First Teacher
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Pagination -->
      <div v-if="teachers.links.length > 3" class="card-footer bg-white border-top">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in teachers.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
              <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- View Teacher Modal -->
    <div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-gradient-info text-white">
            <h5 class="modal-title" id="viewTeacherModalLabel">
              <i class="fas fa-user-circle me-2"></i>
              Teacher Profile
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-0" v-if="teacherToView">
            <div class="row g-0">
              <!-- Teacher Information Card -->
              <div class="col-lg-4 bg-light border-end">
                <div class="p-4">
                  <div class="text-center mb-4">
                    <div class="teacher-avatar-large bg-gradient-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                      <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <h5 class="mb-1">{{ teacherToView.name }}</h5>
                    <p class="text-muted mb-0">{{ teacherToView.email }}</p>
                  </div>

                  <div class="info-section mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                      <i class="fas fa-info-circle me-2"></i>Basic Information
                    </h6>

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
                        {{ formatDate(teacherToView.created_at) }}
                      </div>
                    </div>

                    <div class="info-item">
                      <label class="fw-bold text-muted small">LAST UPDATED</label>
                      <div class="mt-1 text-dark">
                        <i class="fas fa-clock me-2 text-muted"></i>
                        {{ formatDate(teacherToView.updated_at) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Assignments and Statistics -->
              <div class="col-lg-8">
                <div class="p-4">
                  <!-- Statistics Cards -->
                  <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                      <div class="card border-0 bg-gradient-info text-white h-100">
                        <div class="card-body text-center">
                          <i class="fas fa-layer-group fa-2x mb-2 opacity-75"></i>
                          <h4 class="mb-1">{{ teacherToView.sections_count || 0 }}</h4>
                          <small>Advised Sections</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <div class="card border-0 bg-gradient-warning text-white h-100">
                        <div class="card-body text-center">
                          <i class="fas fa-book fa-2x mb-2 opacity-75"></i>
                          <h4 class="mb-1">{{ teacherToView.schedules_count || 0 }}</h4>
                          <small>Teaching Subjects</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <div class="card border-0 bg-gradient-success text-white h-100">
                        <div class="card-body text-center">
                          <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                          <h4 class="mb-1">{{ teacherToView.students_count || 0 }}</h4>
                          <small>Total Students</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Sections and Schedules -->
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
                          <div v-if="teacherToView.sections_count > 0">
                            <div class="section-item mb-3" v-for="i in Math.min(teacherToView.sections_count, 3)" :key="i">
                              <div class="d-flex align-items-center">
                                <div class="section-icon bg-info-soft text-info rounded-circle me-3">
                                  <i class="fas fa-users"></i>
                                </div>
                                <div>
                                  <div class="fw-bold">Section {{ i }}</div>
                                  <small class="text-muted">Grade Level</small>
                                </div>
                              </div>
                            </div>
                            <div v-if="teacherToView.sections_count > 3" class="text-center">
                              <small class="text-muted">+{{ teacherToView.sections_count - 3 }} more sections</small>
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
                          <div v-if="teacherToView.schedules_count > 0">
                            <div class="schedule-item mb-3" v-for="i in Math.min(teacherToView.schedules_count, 3)" :key="i">
                              <div class="d-flex align-items-center">
                                <div class="schedule-icon bg-warning-soft text-warning rounded-circle me-3">
                                  <i class="fas fa-book"></i>
                                </div>
                                <div>
                                  <div class="fw-bold">Subject {{ i }}</div>
                                  <small class="text-muted">Teaching Schedule</small>
                                </div>
                              </div>
                            </div>
                            <div v-if="teacherToView.schedules_count > 3" class="text-center">
                              <small class="text-muted">+{{ teacherToView.schedules_count - 3 }} more schedules</small>
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
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-2"></i>
              Close
            </button>
            <button type="button" class="btn btn-warning" @click="editTeacher(teacherToView)" data-bs-dismiss="modal">
              <i class="fas fa-edit me-2"></i>
              Edit Teacher
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
          <div class="modal-header bg-danger text-white border-0">
            <h5 class="modal-title fw-bold" id="deleteModalLabel">
              <i class="fas fa-exclamation-triangle me-1"></i> Confirm Delete
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <h6 class="fw-semibold text-center">Are you sure you want to delete this teacher?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="teacher-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="fw-bold text-dark">{{ teacherToDelete?.name }}</div>
                  <small class="text-muted">{{ teacherToDelete?.email }}</small>
                </div>
              </div>
            </div>
            <div class="alert alert-warning alert-dismissible fade show small border-0" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Warning:</strong> This action is permanent and cannot be undone.
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
          <div class="modal-footer border-top pt-3">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-danger btn-sm px-4"
              @click="deleteTeacher"
              :disabled="deleteForm.processing"
              aria-label="Delete teacher"
            >
              <span class="d-flex align-items-center justify-content-center">
                <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
                {{ deleteForm.processing ? 'Deleting...' : 'Delete Teacher' }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js'

export default {
  name: 'TeachersIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    teachers: Object
  },
  data() {
    return {
      teacherToDelete: null,
      teacherToView: null,
      searchQuery: '',
      filterStatus: 'all',
      filteredTeachers: [],
      deleteForm: useForm({})
    }
  },
  computed: {
    teachersWithSections() {
      return this.teachers.data.filter(teacher => teacher.advised_sections_count > 0).length
    },
    newThisMonth() {
      const currentMonth = new Date().getMonth()
      const currentYear = new Date().getFullYear()
      return this.teachers.data.filter(teacher => {
        const createdDate = new Date(teacher.created_at)
        return createdDate.getMonth() === currentMonth && createdDate.getFullYear() === currentYear
      }).length
    }
  },
  mounted() {
    this.filteredTeachers = [...this.teachers.data]
  },
  methods: {
    viewTeacher(teacher) {
      this.teacherToView = teacher
      this.$nextTick(() => {
        const modalElement = document.getElementById('viewTeacherModal')
        if (modalElement) {
          try {
            if (window.bootstrap && window.bootstrap.Modal) {
              const modal = new window.bootstrap.Modal(modalElement)
              modal.show()
            } else {
              modalElement.classList.add('show')
              modalElement.style.display = 'block'
              modalElement.setAttribute('aria-modal', 'true')
              modalElement.setAttribute('role', 'dialog')
              const backdrop = document.createElement('div')
              backdrop.className = 'modal-backdrop fade show'
              backdrop.id = 'view-modal-backdrop'
              document.body.appendChild(backdrop)
              document.body.classList.add('modal-open')
            }
          } catch (error) {
            console.error('Error opening view modal:', error)
          }
        }
      })
    },
    editTeacher(teacher) {
      router.visit(`/admin/teachers/${teacher.id}/edit`)
    },
    confirmDelete(teacher) {
      this.teacherToDelete = teacher
      this.$nextTick(() => {
        const modalElement = document.getElementById('deleteModal')
        if (modalElement) {
          try {
            if (window.bootstrap && window.bootstrap.Modal) {
              const modal = new window.bootstrap.Modal(modalElement)
              modal.show()
            } else {
              modalElement.classList.add('show')
              modalElement.style.display = 'block'
              modalElement.setAttribute('aria-modal', 'true')
              modalElement.setAttribute('role', 'dialog')
              const backdrop = document.createElement('div')
              backdrop.className = 'modal-backdrop fade show'
              backdrop.id = 'delete-modal-backdrop'
              document.body.appendChild(backdrop)
              document.body.classList.add('modal-open')
            }
          } catch (error) {
            console.error('Error opening delete modal:', error)
          }
        }
      })
    },
    deleteTeacher() {
      if (this.teacherToDelete) {
        const teacherName = this.teacherToDelete.name
        this.deleteForm.delete(`/admin/teachers/${this.teacherToDelete.id}`, {
          onSuccess: (page) => {
            this.closeDeleteModal()
            this.teachers = page.props.teachers
            this.updateFilteredTeachers()
            showSuccessToast(
              'Teacher Deleted!',
              `${teacherName} has been removed successfully.`
            )
            this.teacherToDelete = null
          },
          onError: (errors) => {
            this.closeDeleteModal()
            const firstError = Object.values(errors)[0]
            showErrorToast(
              'Delete Failed',
              firstError || 'Unable to delete teacher. Please try again.'
            )
            this.teacherToDelete = null
          }
        })
      }
    },
    closeDeleteModal() {
      const modalElement = document.getElementById('deleteModal')
      if (modalElement) {
        try {
          if (window.bootstrap && window.bootstrap.Modal) {
            const modal = window.bootstrap.Modal.getInstance(modalElement)
            if (modal) {
              modal.hide()
            }
          } else {
            modalElement.classList.remove('show')
            modalElement.style.display = 'none'
            modalElement.removeAttribute('aria-modal')
            modalElement.removeAttribute('role')
            const backdrop = document.getElementById('delete-modal-backdrop')
            if (backdrop) {
              backdrop.remove()
            }
            document.body.classList.remove('modal-open')
          }
        } catch (error) {
          console.error('Error closing delete modal:', error)
        }
      }
    },
    updateFilteredTeachers() {
      this.filterTeachers()
    },
    filterTeachers() {
      let filtered = [...this.teachers.data]
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(teacher =>
          teacher.name.toLowerCase().includes(query) ||
          teacher.email.toLowerCase().includes(query)
        )
      }
      if (this.filterStatus === 'with_sections') {
        filtered = filtered.filter(teacher => teacher.advised_sections_count > 0)
      } else if (this.filterStatus === 'without_sections') {
        filtered = filtered.filter(teacher => teacher.advised_sections_count === 0)
      }
      this.filteredTeachers = filtered
    },
    setFilter(status) {
      this.filterStatus = status
      this.filterTeachers()
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    }
  },
  watch: {
    searchQuery() {
      this.filterTeachers()
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

.bg-gradient-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
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

.teacher-avatar {
  width: 45px;
  height: 45px;
  font-size: 1rem;
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
}

.teachers-table {
  font-size: 0.95rem;
}

.teachers-table th {
  font-weight: 600;
  color: var(--primary-color);
  background-color: #f8f9fa;
  border-bottom: 2px solid #e9ecef;
  padding: 1rem 0.75rem;
}

.teacher-row:hover {
  background-color: #f8f9fa;
}

.bg-info-soft {
  background-color: rgba(23, 162, 184, 0.1);
  border: 1px solid rgba(23, 162, 184, 0.2);
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.text-info {
  color: #17a2b8 !important;
}

.text-success {
  color: #28a745 !important;
}

.btn-group .btn {
  border-radius: 0.375rem;
  margin-right: 0.25rem;
}

.btn-group .btn:last-child {
  margin-right: 0;
}

.empty-state {
  padding: 3rem 2rem;
}

.input-group-text {
  background-color: #f8f9fa;
  border-color: #e9ecef;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.modal-content {
  border: none;
  border-radius: 0.75rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
  border-radius: 0.75rem 0.75rem 0 0;
  border-bottom: none;
  padding: 1.5rem;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
  border-radius: 0 0 0.75rem 0.75rem;
  padding: 1rem 1.5rem;
}

.btn-primary {
  background: var(--primary-color);
  border: none;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(44, 90, 160, 0.2);
}

.btn-primary:disabled {
  background: var(--primary-color);
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-danger {
  background: #dc3545;
  border: none;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

.btn-danger:hover {
  background: #c82333;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
}

.btn-danger:disabled {
  background: #dc3545;
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

.alert {
  border-radius: 0.5rem;
  border: none;
}

.bg-primary-soft {
  background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-soft {
  background-color: rgba(25, 135, 84, 0.1);
}

.bg-info-soft {
  background-color: rgba(13, 202, 240, 0.1);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
}

.teacher-avatar-large {
  width: 80px;
  height: 80px;
  font-size: 1.5rem;
}

.info-section .info-item label {
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: var(--text-muted);
}

.section-icon, .schedule-icon {
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
}

.fa-spinner.fa-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .teacher-avatar {
    width: 40px;
    height: 40px;
    font-size: 0.875rem;
  }

  .btn-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }

  .btn-group .btn {
    margin-right: 0;
    font-size: 0.875rem;
  }

  .teachers-table th,
  .teachers-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
  }
}
</style>