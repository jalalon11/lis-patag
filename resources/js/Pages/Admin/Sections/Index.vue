<template>
  <AdminLayout page-title="Sections Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Sections Management</h2>
        <p class="text-muted">Manage class sections for all grade levels</p>
      </div>
      <div class="col-md-4 text-end">
        <Link 
          href="/admin/sections/create" 
          class="btn btn-primary"
        >
          <i class="fas fa-plus me-2"></i>
          Add New Section
        </Link>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Error Message -->
    <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      {{ $page.props.flash.error }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Sections Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom">
        <h6 class="m-0 fw-bold text-primary">
          <i class="fas fa-layer-group me-2"></i>
          Sections List
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="fw-semibold">Section Name</th>
                <th class="fw-semibold">Grade Level</th>
                <th class="fw-semibold">Capacity</th>
                <th class="fw-semibold">Enrollment</th>
                <th class="fw-semibold">Adviser</th>
                <th class="fw-semibold">Room</th>
                <th class="fw-semibold">Status</th>
                <th class="fw-semibold text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="sections.data.length === 0">
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="fas fa-layer-group fa-2x mb-3 d-block"></i>
                  No sections found. <Link href="/admin/sections/create" class="text-primary">Add the first section</Link>
                </td>
              </tr>
              <tr v-for="section in sections.data" :key="section.id" class="align-middle">
                <td>
                  <div class="fw-semibold">{{ section.section_name }}</div>
                  <small class="text-muted" v-if="section.description">{{ section.description }}</small>
                </td>
                <td>
                  <span class="badge bg-primary">{{ section.grade_level }}</span>
                </td>
                <td>
                  <span class="badge bg-info">{{ section.capacity }}</span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <span 
                      class="badge me-2"
                      :class="{
                        'bg-success': section.current_enrollment < section.capacity * 0.8,
                        'bg-warning': section.current_enrollment >= section.capacity * 0.8 && section.current_enrollment < section.capacity,
                        'bg-danger': section.current_enrollment >= section.capacity
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
                <td>
                  <div v-if="section.adviser">
                    <div class="fw-semibold">{{ section.adviser.full_name }}</div>
                    <small class="text-muted">{{ section.adviser.position }}</small>
                  </div>
                  <span v-else class="text-muted">No adviser assigned</span>
                </td>
                <td>
                  <span v-if="section.room_number" class="badge bg-secondary">{{ section.room_number }}</span>
                  <span v-else class="text-muted">Not assigned</span>
                </td>
                <td>
                  <span 
                    class="badge"
                    :class="section.is_active ? 'bg-success' : 'bg-danger'"
                  >
                    {{ section.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/sections/${section.id}`"
                      class="btn btn-sm btn-outline-info"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </Link>
                    <Link 
                      :href="`/admin/sections/${section.id}/edit`"
                      class="btn btn-sm btn-outline-primary"
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
          <ul class="pagination pagination-sm justify-content-center mb-0">
            <li v-for="link in sections.links" :key="link.label" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
              <Link 
                v-if="link.url" 
                :href="link.url" 
                class="page-link"
                v-html="link.label"
              ></Link>
              <span v-else class="page-link" v-html="link.label"></span>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete the section <strong>{{ sectionToDelete?.section_name }}</strong>?</p>
            <p class="text-danger small">
              <i class="fas fa-exclamation-triangle me-1"></i>
              This action cannot be undone.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="deleteSection">
              <i class="fas fa-trash me-2"></i>
              Delete Section
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

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
      sectionToDelete: null
    }
  },
  methods: {
    confirmDelete(section) {
      if (section.current_enrollment > 0) {
        alert('Cannot delete section with enrolled students.')
        return
      }
      this.sectionToDelete = section
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
      modal.show()
    },
    deleteSection() {
      if (this.sectionToDelete) {
        router.delete(`/admin/sections/${this.sectionToDelete.id}`, {
          onSuccess: () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
            modal.hide()
            this.sectionToDelete = null
          }
        })
      }
    }
  }
}
</script>

<style scoped>
.table th {
  border-top: none;
  font-weight: 600;
  color: var(--text-dark);
}

.table td {
  vertical-align: middle;
}

.btn-group .btn {
  border-radius: 0.375rem;
  margin: 0 1px;
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
}

.progress {
  background-color: #e9ecef;
}

.card {
  transition: transform 0.2s ease-in-out;
}

.table-hover tbody tr:hover {
  background-color: rgba(44, 90, 160, 0.05);
}

.btn-primary {
  background: linear-gradient(45deg, var(--primary-color), #1e3d6f);
  border: none;
  font-weight: 600;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #1e3d6f, var(--primary-color));
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.pagination .page-link {
  color: var(--primary-color);
  border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.pagination .page-link:hover {
  color: var(--primary-color);
  background-color: rgba(44, 90, 160, 0.1);
}
</style>
