<template>
  <AdminLayout page-title="Subjects Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Subjects Management</h2>
        <p class="text-muted">Manage curriculum subjects for all grade levels</p>
      </div>
      <div class="col-md-4 text-end">
        <Link 
          href="/admin/subjects/create" 
          class="btn btn-primary"
        >
          <i class="fas fa-plus me-2"></i>
          Add New Subject
        </Link>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      {{ $page.props.flash.success }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom">
        <h6 class="m-0 fw-bold text-primary">
          <i class="fas fa-book me-2"></i>
          Subjects List
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="fw-semibold">Subject Code</th>
                <th class="fw-semibold">Subject Name</th>
                <th class="fw-semibold">Grade Levels</th>
                <th class="fw-semibold">Units</th>
                <th class="fw-semibold">Type</th>
                <th class="fw-semibold">Status</th>
                <th class="fw-semibold text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="subjects.data.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  <i class="fas fa-book fa-2x mb-3 d-block"></i>
                  No subjects found. <Link href="/admin/subjects/create" class="text-primary">Add the first subject</Link>
                </td>
              </tr>
              <tr v-for="subject in subjects.data" :key="subject.id" class="align-middle">
                <td>
                  <span class="badge bg-primary">{{ subject.subject_code }}</span>
                </td>
                <td>
                  <div class="fw-semibold">{{ subject.subject_name }}</div>
                  <small class="text-muted" v-if="subject.description">{{ subject.description }}</small>
                </td>
                <td>
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
                <td>
                  <span class="badge bg-info">{{ subject.units }} {{ subject.units === 1 ? 'unit' : 'units' }}</span>
                </td>
                <td>
                  <span 
                    class="badge"
                    :class="{
                      'bg-success': subject.subject_type === 'Core',
                      'bg-warning': subject.subject_type === 'Elective',
                      'bg-info': subject.subject_type === 'Special'
                    }"
                  >
                    {{ subject.subject_type }}
                  </span>
                </td>
                <td>
                  <span 
                    class="badge"
                    :class="subject.is_active ? 'bg-success' : 'bg-danger'"
                  >
                    {{ subject.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/subjects/${subject.id}`"
                      class="btn btn-sm btn-outline-info"
                      title="View Details"
                    >
                      <i class="fas fa-eye"></i>
                    </Link>
                    <Link 
                      :href="`/admin/subjects/${subject.id}/edit`"
                      class="btn btn-sm btn-outline-primary"
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
        <nav aria-label="Subjects pagination">
          <ul class="pagination pagination-sm justify-content-center mb-0">
            <li v-for="link in subjects.links" :key="link.label" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
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
            <p>Are you sure you want to delete the subject <strong>{{ subjectToDelete?.subject_name }}</strong>?</p>
            <p class="text-danger small">
              <i class="fas fa-exclamation-triangle me-1"></i>
              This action cannot be undone.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="deleteSubject">
              <i class="fas fa-trash me-2"></i>
              Delete Subject
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
      subjectToDelete: null
    }
  },
  methods: {
    confirmDelete(subject) {
      this.subjectToDelete = subject
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
      modal.show()
    },
    deleteSubject() {
      if (this.subjectToDelete) {
        router.delete(`/admin/subjects/${this.subjectToDelete.id}`, {
          onSuccess: () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
            modal.hide()
            this.subjectToDelete = null
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
