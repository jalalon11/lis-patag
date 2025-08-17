<template>
  <AdminLayout page-title="Subjects Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Subjects Management
        </h2>
        <p class="text-muted mb-0">Manage curriculum subjects for all grade levels</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/subjects/create"
          class="btn btn-primary btn-sm px-3 shadow-sm"
        >
          <i class="fas fa-plus me-1"></i>
          Add Subject
        </Link>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <label class="form-label fw-bold text-dark mb-2">
          Grade Level
        </label>
        <div class="d-flex justify-content-between">
          <!-- Grade Level Filter as Cards -->
          <div>
            <div class="row g-2">
              <div class="col-auto">
                <button
                  type="button"
                  class="btn btn-md btn-primary-soft text-center"
                  :class="{ 'bg-primary text-white': selectedGrade === 'all' }"
                  @click="setGradeFilter('all')"
                >
                  <div class="">
                    <h6 class="mb-0">All Grades</h6>
                  </div>
                </button>
              </div>
              <div v-for="grade in availableGrades" :key="grade" class="col-auto">
                <button
                  type="button"
                  class="btn btn-md btn-primary-soft text-center"
                  :class="{ 'bg-primary text-white': selectedGrade === grade }"
                  @click="setGradeFilter(grade)"
                >
                  <div class="">
                    <h6 class="mb-0">Grade {{ grade }}</h6>
                  </div>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex">
            <!-- Subject Type Filter as Dropdown -->
            <div class="me-3">
              <select
                v-model="selectedType"
                class="form-select"
                @change="setTypeFilter($event.target.value)"
              >
                <option value="all">All Types</option>
                <option value="Core">Core</option>
                <option value="Elective">Elective</option>
                <option value="Special">Special</option>
              </select>
            </div>

            <!-- Search Bar -->
            <div>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="fas fa-search text-muted"></i>
                </span>
                <input
                  v-model="searchQuery"
                  type="text"
                  class="form-control border-start-0"
                  placeholder="Search by subject code or name..."
                >
                <button 
                  v-if="searchQuery"
                  class="btn btn-outline-secondary" 
                  type="button"
                  @click="searchQuery = ''"
                >
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Active Filters Display -->
        <div v-if="hasActiveFilters" class="mt-3">
          <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted">Active filters:</span>
            <span v-if="selectedGrade !== 'all'" class="badge bg-primary">
              Grade {{ selectedGrade }}
              <button @click="setGradeFilter('all')" class="btn-close btn-close-white ms-1" style="font-size: 0.75em;"></button>
            </span>
            <span v-if="selectedType !== 'all'" class="badge bg-secondary">
              {{ selectedType }}
              <button @click="setTypeFilter('all')" class="btn-close btn-close-white ms-1" style="font-size: 0.75em;"></button>
            </span>
            <span v-if="searchQuery" class="badge bg-info">
              "{{ searchQuery }}"
              <button @click="searchQuery = ''" class="btn-close btn-close-white ms-1" style="font-size: 0.75em;"></button>
            </span>
            <button @click="clearAllFilters" class="btn btn-sm btn-tex-danger">
              <i class="fas fa-times me-1"></i>
            </button> 
          </div>
        </div>
      </div>
    </div>

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div v-if="filteredSubjects.length === 0" class="text-center py-5">
          <i class="fas fa-book fa-4x text-muted mb-3"></i>
          <h5 class="text-muted">No subjects found</h5>
          <p class="text-muted mb-3">
            {{ hasActiveFilters ? 'Try adjusting your filters' : 'Get started by adding your first subject' }}
          </p>
          <Link v-if="!hasActiveFilters" href="/admin/subjects/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add First Subject
          </Link>
          <button v-else @click="clearAllFilters" class="btn btn-outline-primary">
            <i class="fas fa-filter me-2"></i>
            Clear Filters
          </button>
        </div>
        <div v-else class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="bg-light">
              <tr>
                <th scope="col">Subject Code</th>
                <th scope="col">Name</th>
                <th scope="col">Grade Level</th>
                <th scope="col">Type</th>
                <th scope="col">Units</th>
                <th scope="col">Curriculum Year</th>
                <th scope="col" >Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="subject in filteredSubjects" :key="subject.id">
                <td>{{ subject.subject_code }}</td>
                <td>{{ subject.subject_name }}</td>
                <td>Grade {{ subject.grade_levels }}</td>
                <td>
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
                </td>
                <td>{{ subject.units }} {{ subject.units === 1 ? 'unit' : 'units' }}</td>
                <td>{{ subject.curriculum_year }}</td>
                <td >
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/subjects/${subject.id}`" 
                      class="btn btn-sm btn-outline-primary"
                    >
                      <i class="fas fa-eye me-1"></i>
                    </Link>

                    <Link 
                      :href="`/admin/subjects/${subject.id}/edit`" 
                      class="btn btn-sm btn-outline-primary"
                    >
                      <i class="fas fa-edit me-1"></i>
                    </Link>

                    <button 
                      @click="confirmDelete(subject)" 
                      class="btn btn-sm btn-outline-primary"
                    >
                      <i class="fas fa-trash me-1"></i>
                    </button>
                  </div>
                </td>

              </tr>
            </tbody>
          </table>
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
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <div class="text-center mb-3">
              <div class="subject-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                <i class="fas fa-book fa-2x"></i>
              </div>
              <h6 class="fw-semibold">Delete Subject</h6>
              <p class="text-muted mb-3">
                Are you sure you want to delete <strong>{{ subjectToDelete?.subject_name }}</strong>?
              </p>
            </div>
            <div class="alert the alert-warning border-0" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              This action cannot be undone.
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-danger"
              @click="deleteSubject"
              :disabled="deleteForm.processing"
            >
              <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleteForm.processing ? ' Deleting...' : 'Delete Subject' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
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
      selectedGrade: 'all',
      selectedType: 'all',
      deleteForm: useForm({})
    }
  },
  computed: {
    availableGrades() {
      const grades = [...new Set(this.subjects.data.map(subject => subject.grade_levels))]
      return grades.sort((a, b) => a - b)
    },
    filteredSubjects() {
      let filtered = [...this.subjects.data]

      // Filter by grade level
      if (this.selectedGrade !== 'all') {
        filtered = filtered.filter(subject => subject.grade_levels === this.selectedGrade)
      }

      // Filter by subject type
      if (this.selectedType !== 'all') {
        filtered = filtered.filter(subject => subject.subject_type === this.selectedType)
      }

      // Filter by search query
      if (this.searchQuery) {
1556
1574
      const query = this.searchQuery.toLowerCase()
      filtered = filtered.filter(subject =>
        subject.subject_code.toLowerCase().includes(query) ||
        subject.subject_name.toLowerCase().includes(query)
      )
    }

    return filtered
  },
  hasActiveFilters() {
    return this.selectedGrade !== 'all' || this.selectedType !== 'all' || this.searchQuery !== ''
  }
},
mounted() {
  // Check for flash messages
  if (this.$page.props.flash.success) {
    showSuccessToast('Success!', this.$page.props.flash.success)
  }
  if (this.$page.props.flash.error) {
    showErrorToast('Error', this.$page.props.flash.error)
  }
},
methods: {
  setGradeFilter(grade) {
    this.selectedGrade = grade
  },
  setTypeFilter(type) {
    this.selectedType = type
  },
  clearAllFilters() {
    this.selectedGrade = 'all'
    this.selectedType = 'all'
    this.searchQuery = ''
  },
  confirmDelete(subject) {
    this.subjectToDelete = subject
    this.$nextTick(() => {
      const modal = new window.bootstrap.Modal(document.getElementById('deleteModal'))
      modal.show()
    })
  },
  deleteSubject() {
    if (this.subjectToDelete) {
      const subjectName = this.subjectToDelete.subject_name
      this.deleteForm.delete(`/admin/subjects/${this.subjectToDelete.id}`, {
/*************  ✨ Windsurf Command ⭐  *************/
/**
 * Callback function executed when the subject deletion is successful.
 * Hides the delete confirmation modal, displays a success toast notification,
 * and resets the subjectToDelete state to null.
 */

/*******  70a9ea56-fafe-45da-adb9-9f71328ee952  *******/
        onSuccess: () => {
          const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
          modal.hide()
          showSuccessToast('Subject Deleted!', `${subjectName} has been removed successfully.`)
          this.subjectToDelete = null
        },
        onError: (errors) => {
          const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
          modal.hide()
          const firstError = Object.values(errors)[0]
          showErrorToast('Delete Failed', firstError || 'Unable to delete subject. Please try again.')
          this.subjectToDelete = null
        }
      })
    }
  }
}
}
</script>

<style scoped>
.btn-outline-primary.active,
.btn-outline-secondary.active {
  background-color: var(--bs-primary);
  border-color: var(--bs-primary);
  color: white;
}

.form-control:focus {
  border-color: #2c5aa0;
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

@media (max-width: 768px) {
  .btn-group {
    flex-direction: column;
  }
}
</style>