<template>
  <AdminLayout page-title="Subjects Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-book me-3"></i>
          Subjects Management
        </h2>
        <p class="text-muted mb-0">Manage curriculum subjects for all grade levels</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/subjects/create"
          class="btn btn-primary btn-sm px-3 shadow-sm"
          aria-label="Add new subject"
        >
          <i class="fas fa-plus me-1"></i>
          Add Subject
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
                <h6 class="card-title mb-1">Total Subjects</h6>
                <h3 class="mb-0">{{ subjects.total || subjects.data.length }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-book fa-2x opacity-75"></i>
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
                <h6 class="card-title mb-1">Active Subjects</h6>
                <h3 class="mb-0">{{ activeSubjects }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
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
                <h6 class="card-title mb-1">Core Subjects</h6>
                <h3 class="mb-0">{{ coreSubjects }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
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
                <h6 class="card-title mb-1">Assigned Subjects</h6>
                <h3 class="mb-0">{{ assignedSubjects }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-layer-group fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
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
                placeholder="Search subjects by code or name..."
                @input="filterSubjects"
              >
            </div>
          </div>
          <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterType === 'all' }"
                @click="setFilterType('all')"
              >
                All Subjects
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterType === 'core' }"
                @click="setFilterType('core')"
              >
                Core
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterType === 'elective' }"
                @click="setFilterType('elective')"
              >
                Elective
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterType === 'special' }"
                @click="setFilterType('special')"
              >
                Special
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Subjects Directory
          </h6>
          <span class="badge bg-white text-primary">{{ filteredSubjects.length }} subjects</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 subjects-table">
            <thead class="table-light">
              <tr>
                <th class="border-0 ps-4 fw-bold">Subject Information</th>
                <th class="border-0 fw-bold">Grade Levels</th>
                <th class="border-0 fw-bold">Units & Type</th>
                <th class="border-0 fw-bold">Status</th>
                <th class="border-0 text-center fw-bold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredSubjects.length === 0">
                <td colspan="5" class="text-center py-5">
                  <div class="empty-state">
                    <i class="fas fa-book fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No subjects found</h5>
                    <p class="text-muted mb-3">
                      {{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first subject' }}
                    </p>
                    <Link v-if="!searchQuery" href="/admin/subjects/create" class="btn btn-primary">
                      <i class="fas fa-plus me-2"></i>
                      Add First Subject
                    </Link>
                  </div>
                </td>
              </tr>
              <tr v-for="subject in filteredSubjects" :key="subject.id" class="subject-row">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="subject-icon bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                      <i class="fas fa-book"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ subject.subject_name }}</div>
                      <small class="text-muted">
                        <i class="fas fa-id-badge me-1"></i>
                        Code: {{ subject.subject_code }}
                      </small>
                      <br>
                      <small class="text-muted" v-if="subject.description">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ subject.description }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div class="d-flex flex-wrap gap-1">
                    <span 
                      v-for="grade in subject.grade_levels" 
                      :key="grade" 
                      class="badge bg-secondary"
                    >
                      {{ grade }}
                    </span>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-info-soft text-info mb-1">
                      <i class="fas fa-graduation-cap me-1"></i>
                      {{ subject.units }} {{ subject.units === 1 ? 'unit' : 'units' }}
                    </span>
                    <br>
                    <span 
                      class="badge"
                      :class="{
                        'bg-success-soft text-success': subject.subject_type === 'Core',
                        'bg-warning-soft text-warning': subject.subject_type === 'Elective',
                        'bg-info-soft text-info': subject.subject_type === 'Special'
                      }"
                    >
                      {{ subject.subject_type }}
                    </span>
                  </div>
                </td>
                <td class="py-3">
                  <span 
                    class="badge"
                    :class="{
                      'bg-success-soft text-success': subject.is_active,
                      'bg-danger-soft text-danger': !subject.is_active
                    }"
                  >
                    <i
                      class="me-1"
                      :class="{
                        'fas fa-check-circle': subject.is_active,
                        'fas fa-times-circle': !subject.is_active
                      }"
                    ></i>
                    {{ subject.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-center py-3">
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/subjects/${subject.id}`"
                      class="btn btn-sm btn-outline-primary"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </Link>
                    <Link 
                      :href="`/admin/subjects/${subject.id}/edit`"
                      class="btn btn-sm btn-outline-warning"
                      title="Edit Subject"
                    >
                      <i class="fas fa-edit"></i>
                    </Link>
                    <button 
                      @click="confirmDelete(subject)"
                      class="btn btn-sm btn-outline-danger"
                      title="Delete Subject"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="subjects.links.length > 3" class="card-footer bg-white border-top">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in subjects.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
              <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
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
              <h6 class="fw-semibold text-center">Are you sure you want to delete this subject?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="subject-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                  <i class="fas fa-book"></i>
                </div>
                <div>
                  <div class="fw-bold text-dark">{{ subjectToDelete?.subject_name }}</div>
                  <small class="text-muted">{{ subjectToDelete?.subject_code }}</small>
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
              @click="deleteSubject"
              :disabled="deleteForm.processing"
              aria-label="Delete subject"
            >
              <span class="d-flex align-items-center justify-content-center">
                <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
                {{ deleteForm.processing ? 'Deleting...' : 'Delete Subject' }}
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
  name: 'SubjectsIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    subjects: Object
  },
  data() {
    return {
      subjectToDelete: null,
      searchQuery: '',
      filterType: 'all',
      filteredSubjects: [],
      deleteForm: useForm({})
    }
  },
  computed: {
    activeSubjects() {
      return this.subjects.data.filter(subject => subject.is_active).length
    },
    coreSubjects() {
      return this.subjects.data.filter(subject => subject.subject_type === 'Core').length
    },
    assignedSubjects() {
      return this.subjects.data.filter(subject => subject.assigned_sections_count > 0).length
    }
  },
  mounted() {
    this.filteredSubjects = [...this.subjects.data]
    // Check for flash messages
    if (this.$page.props.flash.success) {
      showSuccessToast('Success!', this.$page.props.flash.success)
    }
    if (this.$page.props.flash.error) {
      showErrorToast('Error', this.$page.props.flash.error)
    }
  },
  methods: {
    confirmDelete(subject) {
      this.subjectToDelete = subject
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
    deleteSubject() {
      if (this.subjectToDelete) {
        const subjectName = this.subjectToDelete.subject_name
        this.deleteForm.delete(`/admin/subjects/${this.subjectToDelete.id}`, {
          onSuccess: (page) => {
            this.closeDeleteModal()
            this.subjects = page.props.subjects
            this.updateFilteredSubjects()
            showSuccessToast(
              'Subject Deleted!',
              `${subjectName} has been removed successfully.`
            )
            this.subjectToDelete = null
          },
          onError: (errors) => {
            this.closeDeleteModal()
            const firstError = Object.values(errors)[0]
            showErrorToast(
              'Delete Failed',
              firstError || 'Unable to delete subject. Please try again.'
            )
            this.subjectToDelete = null
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
    updateFilteredSubjects() {
      this.filterSubjects()
    },
    filterSubjects() {
      let filtered = [...this.subjects.data]
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(subject =>
          subject.subject_code.toLowerCase().includes(query) ||
          subject.subject_name.toLowerCase().includes(query)
        )
      }
      if (this.filterType !== 'all') {
        filtered = filtered.filter(subject => subject.subject_type.toLowerCase() === this.filterType)
      }
      this.filteredSubjects = filtered
    },
    setFilterType(type) {
      this.filterType = type
      this.filterSubjects()
    }
  },
  watch: {
    searchQuery() {
      this.filterSubjects()
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

.subject-icon {
  width: 45px;
  height: 45px;
  font-size: 1rem;
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
}

.subjects-table {
  font-size: 0.95rem;
}

.subjects-table th {
  font-weight: 600;
  color: var(--primary-color);
  background-color: #f8f9fa;
  border-bottom: 2px solid #e9ecef;
  padding: 1rem 0.75rem;
}

.subject-row:hover {
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

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
  border: 1px solid rgba(255, 193, 7, 0.2);
}

.bg-danger-soft {
  background-color: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.2);
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

.fa-spinner.fa-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .subject-icon {
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

  .subjects-table th,
  .subjects-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
  }
}
</style>