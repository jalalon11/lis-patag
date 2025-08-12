<template>
  <AdminLayout page-title="Sections Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          <i class="fas fa-layer-group me-3"></i>
          Sections Management
        </h2>
        <p class="text-muted mb-0">Manage class sections for all grade levels</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/sections/create"
          class="btn btn-primary btn-sm px-3 shadow-sm"
          aria-label="Add new section"
        >
          <i class="fas fa-plus me-1"></i>
          Add Section
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
                <h6 class="card-title mb-1">Total Sections</h6>
                <h3 class="mb-0">{{ sections.total || sections.data.length }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-layer-group fa-2x opacity-75"></i>
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
                <h6 class="card-title mb-1">Active Sections</h6>
                <h3 class="mb-0">{{ activeSections }}</h3>
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
                <h6 class="card-title mb-1">Sections with Advisers</h6>
                <h3 class="mb-0">{{ sectionsWithAdvisers }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-user-tie fa-2x opacity-75"></i>
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
                <h6 class="card-title mb-1">Sections at Capacity</h6>
                <h3 class="mb-0">{{ sectionsAtCapacity }}</h3>
              </div>
              <div class="ms-3">
                <i class="fas fa-users fa-2x opacity-75"></i>
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
                placeholder="Search sections by name or grade level..."
                @input="filterSections"
              >
            </div>
          </div>
          <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'all' }"
                @click="setFilterStatus('all')"
              >
                All Sections
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'active' }"
                @click="setFilterStatus('active')"
              >
                Active
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary"
                :class="{ 'active': filterStatus === 'inactive' }"
                @click="setFilterStatus('inactive')"
              >
                Inactive
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sections Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Sections Directory
          </h6>
          <span class="badge bg-white text-primary">{{ filteredSections.length }} sections</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 sections-table">
            <thead class="table-light">
              <tr>
                <th class="border-0 ps-4 fw-bold">Section Information</th>
                <th class="border-0 fw-bold">Grade & Room</th>
                <th class="border-0 fw-bold">Enrollment</th>
                <th class="border-0 fw-bold">Adviser</th>
                <th class="border-0 fw-bold">Status</th>
                <th class="border-0 text-center fw-bold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredSections.length === 0">
                <td colspan="6" class="text-center py-5">
                  <div class="empty-state">
                    <i class="fas fa-layer-group fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No sections found</h5>
                    <p class="text-muted mb-3">
                      {{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first section' }}
                    </p>
                    <Link v-if="!searchQuery" href="/admin/sections/create" class="btn btn-primary">
                      <i class="fas fa-plus me-2"></i>
                      Add First Section
                    </Link>
                  </div>
                </td>
              </tr>
              <tr v-for="section in filteredSections" :key="section.id" class="section-row">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="section-icon bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                      <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ section.section_name }}</div>
                      <small class="text-muted" v-if="section.description">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ section.description }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-primary mb-1">{{ section.grade_level }}</span>
                    <br>
                    <span v-if="section.room_number" class="badge bg-secondary">
                      <i class="fas fa-door-open me-1"></i>
                      {{ section.room_number }}
                    </span>
                    <span v-else class="text-muted">No room assigned</span>
                  </div>
                </td>
                <td class="py-3">
                  <div class="d-flex align-items-center">
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
                    <div class="progress flex-grow-1" style="height: 6px;">
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
                </td>
                <td class="py-3">
                  <div v-if="section.adviser">
                    <div class="fw-bold text-dark">{{ section.adviser.full_name }}</div>
                    <small class="text-muted">
                      <i class="fas fa-briefcase me-1"></i>
                      {{ section.adviser.position }}
                    </small>
                  </div>
                  <span v-else class="text-muted">No adviser assigned</span>
                </td>
                <td class="py-3">
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
                </td>
                <td class="text-center py-3">
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/sections/${section.id}`"
                      class="btn btn-sm btn-outline-primary"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </Link>
                    <Link 
                      :href="`/admin/sections/${section.id}/edit`"
                      class="btn btn-sm btn-outline-warning"
                      title="Edit Section"
                    >
                      <i class="fas fa-edit"></i>
                    </Link>
                    <button 
                      @click="confirmDelete(section)"
                      class="btn btn-sm btn-outline-danger"
                      title="Delete Section"
                      :disabled="section.current_enrollment > 0"
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
      <div v-if="sections.links.length > 3" class="card-footer bg-white border-top">
        <nav aria-label="Sections pagination">
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in sections.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
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
              <h6 class="fw-semibold text-center">Are you sure you want to delete this section?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="section-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                  <i class="fas fa-layer-group"></i>
                </div>
                <div>
                  <div class="fw-bold text-dark">{{ sectionToDelete?.section_name }}</div>
                  <small class="text-muted">{{ sectionToDelete?.grade_level }}</small>
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
              @click="deleteSection"
              :disabled="deleteForm.processing"
              aria-label="Delete section"
            >
              <span class="d-flex align-items-center justify-content-center">
                <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
                {{ deleteForm.processing ? 'Deleting...' : 'Delete Section' }}
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
  name: 'SectionsIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    sections: Object
  },
  data() {
    return {
      sectionToDelete: null,
      searchQuery: '',
      filterStatus: 'all',
      filteredSections: [],
      deleteForm: useForm({})
    }
  },
  computed: {
    activeSections() {
      return this.sections.data.filter(section => section.is_active).length
    },
    sectionsWithAdvisers() {
      return this.sections.data.filter(section => section.adviser).length
    },
    sectionsAtCapacity() {
      return this.sections.data.filter(section => section.current_enrollment >= section.capacity).length
    }
  },
  mounted() {
    this.filteredSections = [...this.sections.data]
    // Check for flash messages
    if (this.$page.props.flash.success) {
      showSuccessToast('Success!', this.$page.props.flash.success)
    }
    if (this.$page.props.flash.error) {
      showErrorToast('Error', this.$page.props.flash.error)
    }
  },
  methods: {
    confirmDelete(section) {
      if (section.current_enrollment > 0) {
        showErrorToast('Cannot Delete', 'Section with enrolled students cannot be deleted.')
        return
      }
      this.sectionToDelete = section
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
    deleteSection() {
      if (this.sectionToDelete) {
        const sectionName = this.sectionToDelete.section_name
        this.deleteForm.delete(`/admin/sections/${this.sectionToDelete.id}`, {
          onSuccess: (page) => {
            this.closeDeleteModal()
            this.sections = page.props.sections
            this.updateFilteredSections()
            showSuccessToast(
              'Section Deleted!',
              `${sectionName} has been removed successfully.`
            )
            this.sectionToDelete = null
          },
          onError: (errors) => {
            this.closeDeleteModal()
            const firstError = Object.values(errors)[0]
            showErrorToast(
              'Delete Failed',
              firstError || 'Unable to delete section. Please try again.'
            )
            this.sectionToDelete = null
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
    updateFilteredSections() {
      this.filterSections()
    },
    filterSections() {
      let filtered = [...this.sections.data]
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(section =>
          section.section_name.toLowerCase().includes(query) ||
          section.grade_level.toLowerCase().includes(query)
        )
      }
      if (this.filterStatus === 'active') {
        filtered = filtered.filter(section => section.is_active)
      } else if (this.filterStatus === 'inactive') {
        filtered = filtered.filter(section => !section.is_active)
      }
      this.filteredSections = filtered
    },
    setFilterStatus(status) {
      this.filterStatus = status
      this.filterSections()
    }
  },
  watch: {
    searchQuery() {
      this.filterSections()
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

.section-icon {
  width: 45px;
  height: 45px;
  font-size: 1rem;
  background: linear-gradient(135deg, var(--primary-color) 0%, #1e3d6f 100%);
}

.sections-table {
  font-size: 0.95rem;
}

.sections-table th {
  font-weight: 600;
  color: var(--primary-color);
  background-color: #f8f9fa;
  border-bottom: 2px solid #e9ecef;
  padding: 1rem 0.75rem;
}

.section-row:hover {
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
  .section-icon {
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

  .sections-table th,
  .sections-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
  }
}
</style>