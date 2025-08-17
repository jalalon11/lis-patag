<template>
  <AdminLayout page-title="Teachers Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Teachers Management
        </h2>
        <p class="text-muted mb-0">Manage teacher accounts, credentials, and assignments</p>
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
            <div class="input-group w-75">
              <span class="input-group-text">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control"
                placeholder="Search teachers by name or email..."
                @input="filterTeachers"
              >
            </div>
          </div>
          <div class="col-md-6 text-end">
            <div class="btn-group me-2" role="group">
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'all' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('all')"
              >
                All Teachers
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'with_sections' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('with_sections')"
              >
                With Sections
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'without_sections' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('without_sections')"
              >
                Without Sections
              </button>
            </div>
            <Link
              href="/admin/teachers/create"
              class="btn btn-primary"
            >
              <i class="fas fa-plus me-1"></i>
              Add Teacher
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Teachers Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Teachers Directory
          </h6>
          <span class="badge bg-light text-primary">{{ filteredTeachers.length }} teachers</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
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
              <tr v-for="teacher in filteredTeachers" :key="teacher.id">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                      <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                      <div class="fw-bold">{{ teacher.full_name || `${teacher.first_name} ${teacher.last_name}` }}</div>
                      <small class="text-muted">
                        {{ teacher.position }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <div class="mb-1">
                      <i class="fas fa-envelope me-2 text-muted"></i>
                      {{ teacher.email }}
                    </div>
                    <div class="mb-1">
                      <i class="fas fa-phone me-2 text-muted"></i>
                      {{ teacher.contact_number }}
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-primary mb-1">
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
                  <span
                    class="badge"
                    :class="{
                      'bg-success': teacher.employment_status === 'Active',
                      'bg-warning': teacher.employment_status === 'Inactive',
                      'bg-secondary': teacher.employment_status === 'Resigned',
                      'bg-danger': teacher.employment_status === 'Terminated'
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
                      class="btn btn-sm btn-outline-primary "
                      title="Edit Teacher"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="confirmDelete(teacher)"
                      class="btn btn-sm btn-outline-primary"
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
      <div v-if="teachers.links && teachers.links.length > 3" class="card-footer bg-white border-top">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in teachers.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
              <Link v-if="link.url" :href="link.url" class="page-link"></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- View Teacher Modal -->
    <div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-user-circle me-2"></i>
              Teacher Profile
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="teacherToView">
            <div class="text-center mb-4">
              <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="fas fa-user-tie fa-2x"></i>
              </div>
              <h5 class="mb-1">{{ teacherToView.name }}</h5>
              <p class="text-muted mb-0">{{ teacherToView.email }}</p>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-primary text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-layer-group fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ teacherToView.sections_count || 0 }}</h4>
                    <small>Advised Sections</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-success text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ teacherToView.schedules_count || 0 }}</h4>
                    <small>Teaching Subjects</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-info text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ teacherToView.students_count || 0 }}</h4>
                    <small>Total Students</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Basic Information</h6>
                <div class="mb-2">
                  <strong>Role:</strong> Teacher
                </div>
                <div class="mb-2">
                  <strong>Status:</strong> <span class="badge bg-success">Active</span>
                </div>
                <div class="mb-2">
                  <strong>Joined:</strong> {{ formatDate(teacherToView.created_at) }}
                </div>
                <div class="mb-2">
                  <strong>Last Updated:</strong> {{ formatDate(teacherToView.updated_at) }}
                </div>
              </div>
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Assignments</h6>
                <div v-if="teacherToView.sections_count > 0">
                  <p class="mb-2">Class Adviser for {{ teacherToView.sections_count }} section(s)</p>
                  <p class="mb-2">Teaching {{ teacherToView.schedules_count || 0 }} subject(s)</p>
                  <p class="mb-0">Total of {{ teacherToView.students_count || 0 }} students</p>
                </div>
                <div v-else>
                  <p class="text-muted mb-0">No sections assigned yet</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="editTeacher(teacherToView)" data-bs-dismiss="modal">
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
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title fw-bold">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Confirm Delete
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <div class="text-center mb-3">
              <h6 class="fw-semibold">Are you sure you want to delete this teacher?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="fw-bold">{{ teacherToDelete?.name }}</div>
                  <small class="text-muted">{{ teacherToDelete?.email }}</small>
                </div>
              </div>
            </div>
            <div class="alert alert-warning" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Warning:</strong> This action is permanent and cannot be undone.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button
              type="button"
              class="btn btn-danger"
              @click="deleteTeacher"
              :disabled="deleteForm.processing"
            >
              <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleteForm.processing ? 'Deleting...' : 'Delete Teacher' }}
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
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
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
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
        }
      })
    },
    deleteTeacher() {
      if (this.teacherToDelete) {
        const teacherName = this.teacherToDelete.name
        this.deleteForm.delete(`/admin/teachers/${this.teacherToDelete.id}`, {
          onSuccess: (page) => {
            this.teachers = page.props.teachers
            this.updateFilteredTeachers()
            showSuccessToast('Teacher Deleted!', `${teacherName} has been removed successfully.`)
            this.teacherToDelete = null
          },
          onError: (errors) => {
            const firstError = Object.values(errors)[0]
            showErrorToast('Delete Failed', firstError || 'Unable to delete teacher. Please try again.')
            this.teacherToDelete = null
          }
        })
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
        day: 'numeric',
        timeZone: 'Asia/Manila'
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
.empty-state {
  padding: 3rem 2rem;
}

.card {
  border-radius: 8px;
}
.btn-group .btn:last-child {
  margin-right: 0;
}

@media (max-width: 768px) {
  .btn-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
}
</style>