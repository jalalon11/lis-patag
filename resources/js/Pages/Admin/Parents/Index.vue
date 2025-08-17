<template>
  <AdminLayout page-title="Parents/Guardian Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Parents/Guardian Management
        </h2>
        <p class="text-muted mb-0">Manage parent and guardian accounts, contact information, and student relationships</p>
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
          <div class="col-md-4">
            <div class="input-group w-75">
              <span class="input-group-text">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control"
                placeholder="Search parents by name or email..."
                @input="filterParents"
              >
            </div>
          </div>
          <div class="col-md-8 text-end">
            <div class="btn-group me-2" role="group">
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'all' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('all')"
              >
                All Parents
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'with_students' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('with_students')"
              >
                With Students
              </button>
              <button
                type="button"
                class="btn"
                :class="filterStatus === 'without_students' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setFilter('without_students')"
              >
                Without Students
              </button>
            </div>
            <Link
              href="/admin/parents/create"
              class="btn btn-primary"
            >
              <i class="fas fa-plus me-1"></i>
              Add Parent/Guardian
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Parents Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between">
          <h6 class="m-0 fw-bold">
            <i class="fas fa-list me-2"></i>
            Parents/Guardian Directory
          </h6>
          <span class="badge bg-light text-primary">{{ filteredParents.length }} parents</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 ps-4 fw-bold">Parent Information</th>
                <th class="border-0 fw-bold">Contact</th>
                <th class="border-0 fw-bold">Students</th>
                <th class="border-0 fw-bold">Status</th>
                <th class="border-0 text-center fw-bold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="parent in filteredParents" :key="parent.id">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                      <i class="fas fa-user"></i>
                    </div>
                    <div>
                      <div class="fw-bold">{{ parent.full_name || `${parent.first_name} ${parent.last_name}` }}</div>
                      <small class="text-muted">
                        <i class="fas fa-id-badge me-1"></i>
                        ID: {{ parent.id }}
                      </small>
                      <br>
                      <small class="text-muted">
                        <i class="fas fa-users me-1"></i>
                        Parent/Guardian
                      </small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <div class="mb-1">
                      <i class="fas fa-envelope me-2 text-muted"></i>
                      {{ parent.email }}
                    </div>
                    <div class="mb-1" v-if="parent.contact_number">
                      <i class="fas fa-phone me-2 text-muted"></i>
                      {{ parent.contact_number }}
                    </div>
                    <small class="text-muted">
                      <i class="fas fa-calendar me-1"></i>
                      Joined {{ formatDate(parent.created_at) }}
                    </small>
                  </div>
                </td>
                <td class="py-3">
                  <div>
                    <span class="badge bg-primary mb-1">
                      <i class="fas fa-child me-1"></i>
                      {{ parent.students_count || 0 }} students
                    </span>
                    <div v-if="parent.students_count > 0">
                      <small class="text-muted">Guardian</small>
                    </div>
                    <div v-else>
                      <small class="text-warning">No students assigned</small>
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <span class="badge bg-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Active
                  </span>
                </td>
                <td class="text-center py-3">
                  <div class="btn-group" role="group">
                    <button
                      @click="viewParent(parent)"
                      class="btn btn-sm btn-outline-primary"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="editParent(parent)"
                      class="btn btn-sm btn-primary"
                      title="Edit Parent"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="confirmDelete(parent)"
                      class="btn btn-sm btn-outline-danger"
                      title="Delete Parent"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredParents.length === 0">
                <td colspan="5" class="text-center py-5">
                  <div class="empty-state">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No parents found</h5>
                    <p class="text-muted mb-3">
                      {{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first parent/guardian' }}
                    </p>
                    <Link v-if="!searchQuery" href="/admin/parents/create" class="btn btn-primary">
                      <i class="fas fa-plus me-2"></i>
                      Add First Parent/Guardian
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Pagination -->
      <div v-if="parents.links && parents.links.length > 3" class="card-footer bg-white border-top">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li v-for="link in parents.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
              <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- View Parent Modal -->
    <div class="modal fade" id="viewParentModal" tabindex="-1" aria-labelledby="viewParentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-user-circle me-2"></i>
              Parent/Guardian Profile
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="parentToView">
            <div class="text-center mb-4">
              <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="fas fa-user fa-2x"></i>
              </div>
              <h5 class="mb-1">{{ parentToView.full_name }}</h5>
              <p class="text-muted mb-0">{{ parentToView.email }}</p>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-primary text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-child fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ parentToView.students_count || 0 }}</h4>
                    <small>Students</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-success text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ parentToView.active_students_count || 0 }}</h4>
                    <small>Active Students</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card border-0 bg-info text-white h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-school fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ parentToView.sections_count || 0 }}</h4>
                    <small>Sections</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Basic Information</h6>
                <div class="mb-2">
                  <strong>Role:</strong> Parent/Guardian
                </div>
                <div class="mb-2">
                  <strong>Status:</strong> <span class="badge bg-success">Active</span>
                </div>
                <div class="mb-2">
                  <strong>Joined:</strong> {{ formatDate(parentToView.created_at) }}
                </div>
                <div class="mb-2">
                  <strong>Last Updated:</strong> {{ formatDate(parentToView.updated_at) }}
                </div>
              </div>
              <div class="col-md-6">
                <h6 class="text-primary fw-bold mb-3">Student Relationships</h6>
                <div v-if="parentToView.students_count > 0">
                  <p class="mb-2">Guardian for {{ parentToView.students_count }} student(s)</p>
                  <p class="mb-2">Active students: {{ parentToView.active_students_count || 0 }}</p>
                  <p class="mb-0">Across {{ parentToView.sections_count || 0 }} section(s)</p>
                </div>
                <div v-else>
                  <p class="text-muted mb-0">No students assigned yet</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="editParent(parentToView)" data-bs-dismiss="modal">
              <i class="fas fa-edit me-2"></i>
              Edit Parent
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
              <h6 class="fw-semibold">Are you sure you want to delete this parent/guardian?</h6>
              <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="fw-bold">{{ parentToDelete?.full_name }}</div>
                  <small class="text-muted">{{ parentToDelete?.email }}</small>
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
              @click="deleteParent"
              :disabled="deleteForm.processing"
            >
              <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleteForm.processing ? 'Deleting...' : 'Delete Parent' }}
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
  name: 'ParentsIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    parents: Object
  },
  data() {
    return {
      parentToDelete: null,
      parentToView: null,
      searchQuery: '',
      filterStatus: 'all',
      filteredParents: [],
      deleteForm: useForm({})
    }
  },
  computed: {
    parentsWithStudents() {
      return this.parents.data.filter(parent => parent.students_count > 0).length
    },
    newThisMonth() {
      const currentMonth = new Date().getMonth()
      const currentYear = new Date().getFullYear()
      return this.parents.data.filter(parent => {
        const createdDate = new Date(parent.created_at)
        return createdDate.getMonth() === currentMonth && createdDate.getFullYear() === currentYear
      }).length
    }
  },
  mounted() {
    this.filteredParents = [...this.parents.data]
  },
  methods: {
    viewParent(parent) {
      this.parentToView = parent
      this.$nextTick(() => {
        const modalElement = document.getElementById('viewParentModal')
        if (modalElement) {
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
        }
      })
    },
    editParent(parent) {
      router.visit(`/admin/parents/${parent.id}/edit`)
    },
    confirmDelete(parent) {
      this.parentToDelete = parent
      this.$nextTick(() => {
        const modalElement = document.getElementById('deleteModal')
        if (modalElement) {
          const modal = new bootstrap.Modal(modalElement)
          modal.show()
        }
      })
    },
    deleteParent() {
      if (this.parentToDelete) {
        const parentName = this.parentToDelete.full_name
        this.deleteForm.delete(`/admin/parents/${this.parentToDelete.id}`, {
          onSuccess: (page) => {
            this.parents = page.props.parents
            this.updateFilteredParents()
            showSuccessToast('Parent Deleted!', `${parentName} has been removed successfully.`)
            this.parentToDelete = null
          },
          onError: (errors) => {
            const firstError = Object.values(errors)[0]
            showErrorToast('Delete Failed', firstError || 'Unable to delete parent. Please try again.')
            this.parentToDelete = null
          }
        })
      }
    },
    updateFilteredParents() {
      this.filterParents()
    },
    filterParents() {
      let filtered = [...this.parents.data]
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(parent =>
          (parent.full_name || `${parent.first_name} ${parent.last_name}`).toLowerCase().includes(query) ||
          parent.email.toLowerCase().includes(query)
        )
      }
      if (this.filterStatus === 'with_students') {
        filtered = filtered.filter(parent => parent.students_count > 0)
      } else if (this.filterStatus === 'without_students') {
        filtered = filtered.filter(parent => parent.students_count === 0)
      }
      this.filteredParents = filtered
    },
    setFilter(status) {
      this.filterStatus = status
      this.filterParents()
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
      this.filterParents()
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