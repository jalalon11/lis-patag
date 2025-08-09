<template>
  <AdminLayout page-title="Subject Details">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Subject Details</h2>
        <p class="text-muted">View detailed information about {{ subject.subject_name }}</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="btn-group" role="group">
          <Link 
            href="/admin/subjects" 
            class="btn btn-outline-secondary"
          >
            <i class="fas fa-arrow-left me-2"></i>
            Back to Subjects
          </Link>
          <Link 
            :href="`/admin/subjects/${subject.id}/edit`"
            class="btn btn-primary"
          >
            <i class="fas fa-edit me-2"></i>
            Edit Subject
          </Link>
        </div>
      </div>
    </div>

    <!-- Subject Information -->
    <div class="row">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-book me-2"></i>
              Subject Information
            </h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Subject Code</label>
                <div class="p-2 bg-light rounded">
                  <span class="badge bg-primary fs-6">{{ subject.subject_code }}</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Subject Name</label>
                <div class="p-2 bg-light rounded">
                  <strong>{{ subject.subject_name }}</strong>
                </div>
              </div>
              <div class="col-12 mb-3" v-if="subject.description">
                <label class="form-label fw-semibold text-muted">Description</label>
                <div class="p-3 bg-light rounded">
                  {{ subject.description }}
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Units</label>
                <div class="p-2 bg-light rounded">
                  <span class="badge bg-info fs-6">{{ subject.units }} {{ subject.units === 1 ? 'unit' : 'units' }}</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Subject Type</label>
                <div class="p-2 bg-light rounded">
                  <span 
                    class="badge fs-6"
                    :class="{
                      'bg-success': subject.subject_type === 'Core',
                      'bg-warning': subject.subject_type === 'Elective',
                      'bg-info': subject.subject_type === 'Special'
                    }"
                  >
                    {{ subject.subject_type }}
                  </span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Status</label>
                <div class="p-2 bg-light rounded">
                  <span 
                    class="badge fs-6"
                    :class="subject.is_active ? 'bg-success' : 'bg-danger'"
                  >
                    {{ subject.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-muted">Grade Levels</label>
                <div class="p-2 bg-light rounded">
                  <div class="d-flex flex-wrap gap-1">
                    <span 
                      v-for="grade in subject.grade_levels" 
                      :key="grade" 
                      class="badge bg-secondary"
                    >
                      {{ grade }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-chart-bar me-2"></i>
              Quick Stats
            </h6>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-12 mb-3">
                <div class="p-3 bg-primary bg-opacity-10 rounded">
                  <i class="fas fa-layer-group fa-2x text-primary mb-2"></i>
                  <h5 class="fw-bold text-primary mb-0">{{ subject.grade_levels?.length || 0 }}</h5>
                  <small class="text-muted">Grade Levels</small>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="p-3 bg-info bg-opacity-10 rounded">
                  <i class="fas fa-clock fa-2x text-info mb-2"></i>
                  <h5 class="fw-bold text-info mb-0">{{ subject.units }}</h5>
                  <small class="text-muted">{{ subject.units === 1 ? 'Unit' : 'Units' }}</small>
                </div>
              </div>
              <div class="col-12">
                <div class="p-3 bg-success bg-opacity-10 rounded">
                  <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                  <h6 class="fw-bold text-success mb-0">{{ subject.subject_type }}</h6>
                  <small class="text-muted">Subject Type</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions Card -->
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary">
              <i class="fas fa-cogs me-2"></i>
              Actions
            </h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <Link 
                :href="`/admin/subjects/${subject.id}/edit`"
                class="btn btn-outline-primary"
              >
                <i class="fas fa-edit me-2"></i>
                Edit Subject
              </Link>
              <button 
                @click="confirmDelete"
                class="btn btn-outline-danger"
              >
                <i class="fas fa-trash me-2"></i>
                Delete Subject
              </button>
            </div>
          </div>
        </div>
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
            <p>Are you sure you want to delete the subject <strong>{{ subject.subject_name }}</strong>?</p>
            <p class="text-danger small">
              <i class="fas fa-exclamation-triangle me-1"></i>
              This action cannot be undone and will affect all related schedules and assignments.
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
  name: 'SubjectsShow',
  components: {
    AdminLayout,
    Link
  },
  props: {
    subject: Object
  },
  methods: {
    confirmDelete() {
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
      modal.show()
    },
    deleteSubject() {
      router.delete(`/admin/subjects/${this.subject.id}`, {
        onSuccess: () => {
          const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
          modal.hide()
        }
      })
    }
  }
}
</script>

<style scoped>
.form-label {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.badge {
  font-size: 0.875rem;
  font-weight: 500;
}

.card {
  transition: transform 0.2s ease-in-out;
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

.bg-light {
  background-color: #f8f9fa !important;
}

.text-muted {
  color: #6c757d !important;
}
</style>
