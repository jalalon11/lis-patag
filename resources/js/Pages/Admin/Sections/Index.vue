<template>
  <AdminLayout page-title="Sections Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Sections Management
        </h2>
        <p class="text-muted mb-0">Manage class sections for all grade levels</p>
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
                placeholder="Search sections by name or grade level..."
                @input="filterSections"
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
                All Sections
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'active' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('active')"
              >
                Active
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'inactive' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('inactive')"
              >
                Inactive
              </button>
            </div>
            <Link
              href="/admin/sections/create"
              class="btn btn-primary"
            >
              <i class="fas fa-plus me-1"></i>
              Add Section
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Sections Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Sections Directory
          </h6>
          <span class="badge bg-light text-primary">{{ filteredSections.length }} sections</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
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
              <tr v-for="section in filteredSections" :key="section.id">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                      <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                      <div class="fw-bold">{{ section.section_name }}</div>
                      <small class="text-muted" v-if="section.description">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ section.description }}
                      </small>
                      <br v-if="section.description">
                      <small class="text-muted">
                        <i class="fas fa-id-badge me-1"></i>
                        Section ID: {{ section.id }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <div class="mb-1">
                      <span class="badge bg-primary">
                        <i class="fas fa-graduation-cap me-1"></i>
                        {{ section.grade_level_display || section.grade_level }}
                      </span>
                    </div>
                    <small class="text-muted">
                      <i class="fas fa-door-open me-1"></i>
                      {{ section.room_number || 'No room assigned' }}
                    </small>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-primary mb-1">
                      <i class="fas fa-users me-1"></i>
                      {{ section.current_enrollment || 0 }}/{{ section.capacity || 0 }}
                    </span>
                    <div class="progress mt-1" style="height: 4px;">
                      <div 
                        class="progress-bar"
                        :class="{
                          'bg-success': (section.current_enrollment / section.capacity) < 0.8,
                          'bg-warning': (section.current_enrollment / section.capacity) >= 0.8 && (section.current_enrollment / section.capacity) < 1,
                          'bg-danger': (section.current_enrollment / section.capacity) >= 1
                        }"
                        :style="{ width: Math.min((section.current_enrollment / section.capacity) * 100, 100) + '%' }"
                      ></div>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div v-if="section.adviser">
                    <div class="fw-bold">{{ section.adviser.full_name || section.adviser.name }}</div>
                    <small class="text-muted">
                      <i class="fas fa-briefcase me-1"></i>
                      {{ section.adviser.position || 'Class Adviser' }}
                    </small>
                  </div>
                  <div v-else>
                    <small class="text-warning">No adviser assigned</small>
                  </div>
                </td>
                <td class="py-3">
                  <span
                    class="badge"
                    :class="{
                      'bg-success': section.is_active,
                      'bg-secondary': !section.is_active
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
                    <button
                      @click="viewSection(section)"
                      class="btn btn-sm btn-outline-primary"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="editSection(section)"
                      class="btn btn-sm btn-primary"
                      title="Edit Section"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
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
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Pagination -->
      <div v-if="sections.links && sections.links.length > 3" class="card-footer bg-white border-top">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in sections.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
              <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- View Section Modal -->
    <div class="modal fade" id="viewSectionModal" tabindex="-1" aria-labelledby="viewSectionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-layer-group me-2"></i>
              Section Details
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="sectionToView">
            <div class="text-center mb-4">
              <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="fas fa-layer-group fa-2x"></i>
              </div>
              <h5 class="mb-1">{{ sectionToView.section_name }}</h5>
              <p class="text-muted mb-0">{{ sectionToView.grade_level_display || sectionToView.grade_level }}</p>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-primary text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ sectionToView.current_enrollment || 0 }}</h4>
                    <small>Enrolled Students</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-success text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-chair fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ sectionToView.capacity || 0 }}</h4>
                    <small>Total Capacity</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-info text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ sectionToView.subjects_count || 0 }}</h4>
                    <small>Subjects</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Section Information</h6>
                <div class="mb-2">
                  <strong>Grade Level:</strong> {{ sectionToView.grade_level_display || sectionToView.grade_level }}
                </div>
                <div class="mb-2">
                  <strong>Room:</strong> {{ sectionToView.room_number || 'Not assigned' }}
                </div>
                <div class="mb-2">
                  <strong>Status:</strong> 
                  <span class="badge" :class="sectionToView.is_active ? 'bg-success' : 'bg-secondary'">
                    {{ sectionToView.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <div class="mb-2">
                  <strong>Created:</strong> {{ formatDate(sectionToView.created_at) }}
                </div>
              </div>
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Class Adviser</h6>
                <div v-if="sectionToView.adviser">
                  <p class="mb-2">{{ sectionToView.adviser.full_name || sectionToView.adviser.name }}</p>
                  <p class="mb-2">{{ sectionToView.adviser.position || 'Teacher' }}</p>
                  <p class="mb-0">{{ sectionToView.adviser.email }}</p>
                </div>
                <div v-else>
                  <p class="text-muted mb-0">No adviser assigned yet</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="editSection(sectionToView)" data-bs-dismiss="modal">
              <i class="fas fa-edit me-2"></i>
              Edit Section
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
              <h6 class="fw-semibold">Are you sure you want to delete this section?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                  <i class="fas fa-layer-group"></i>
                </div>
                <div>
                  <div class="fw-bold">{{ sectionToDelete?.section_name }}</div>
                  <small class="text-muted">{{ sectionToDelete?.grade_level }}</small>
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
              @click="deleteSection"
              :disabled="deleteForm.processing"
            >
              <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleteForm.processing ? 'Deleting...' : 'Delete Section' }}
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
      sectionToView: null,
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
  },
  methods: {
    viewSection(section) {
      this.sectionToView = section
      this.$nextTick(() => {
        const modalElement = document.getElementById('viewSectionModal')
        if (modalElement) {
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
        }
      })
    },
    editSection(section) {
      router.visit(`/admin/sections/${section.id}/edit`)
    },
    confirmDelete(section) {
      if (section.current_enrollment > 0) {
        showErrorToast('Cannot Delete', 'Section with enrolled students cannot be deleted.')
        return
      }
      this.sectionToDelete = section
      this.$nextTick(() => {
        const modalElement = document.getElementById('deleteModal')
        if (modalElement) {
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
        }
      })
    },
    deleteSection() {
      if (this.sectionToDelete) {
        const sectionName = this.sectionToDelete.section_name
        this.deleteForm.delete(`/admin/sections/${this.sectionToDelete.id}`, {
          onSuccess: (page) => {
            this.sections = page.props.sections
            this.updateFilteredSections()
            showSuccessToast('Section Deleted!', `${sectionName} has been removed successfully.`)
            this.sectionToDelete = null
          },
          onError: (errors) => {
            const firstError = Object.values(errors)[0]
            showErrorToast('Delete Failed', firstError || 'Unable to delete section. Please try again.')
            this.sectionToDelete = null
          }
        })
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
          (section.grade_level && section.grade_level.toLowerCase().includes(query))
        )
      }
      if (this.filterStatus === 'active') {
        filtered = filtered.filter(section => section.is_active)
      } else if (this.filterStatus === 'inactive') {
        filtered = filtered.filter(section => !section.is_active)
      }
      this.filteredSections = filtered
    },
    setFilter(status) {
      this.filterStatus = status
      this.filterSections()
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
      this.filterSections()
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