<template>
  <AdminLayout page-title="School Years Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          School Years Management
        </h2>
        <p class="text-muted mb-0">Manage academic school years</p>
      </div>
      <div class="col-md-4 text-end">
        <Link
          href="/admin/school-years/create"
          class="btn btn-primary btn-md shadow-sm"
        >
          <i class="fas fa-plus me-1"></i>
          Add School Year
        </Link>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <label class="form-label fw-bold text-dark mb-2">
          Filters
        </label>
        <div class="d-flex justify-content-between">
          <!-- Status Filter -->
          <div>
            <div class="row g-2">
              <div class="col-auto">
                <button
                  type="button"
                  class="btn btn-md btn-primary-soft text-center"
                  :class="{ 'bg-primary text-white': selectedStatus === 'all' }"
                  @click="setStatusFilter('all')"
                >
                  <div class="">
                    <h6 class="mb-0">All Status</h6>
                  </div>
                </button>
              </div>
              <div class="col-auto">
                <button
                  type="button"
                  class="btn btn-md btn-primary-soft text-center"
                  :class="{ 'bg-primary text-white': selectedStatus === 'current' }"
                  @click="setStatusFilter('current')"
                >
                  <div>
                    <h6 class="mb-0">Current</h6>
                  </div>
                </button>
              </div>
              <div class="col-auto">
                <button
                  type="button"
                  class="btn btn-md btn-primary-soft text-center"
                  :class="{ 'bg-primary text-white': selectedStatus === 'active' }"
                  @click="setStatusFilter('active')"
                >
                  <div >
                    <h6 class="mb-0">Active</h6>
                  </div>
                </button>
              </div>
            </div>
          </div>
          <!-- Search Bar -->
          <div class="d-flex">
            <div class="input-group me-2">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control border-start-0"
                placeholder="Search by year name..."
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
        <!-- Active Filters Display -->
        <div v-if="hasActiveFilters" class="mt-3">
          <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted">Active filters:</span>
            <span v-if="selectedStatus !== 'all'" class="badge bg-primary">
              {{ selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1) }}
              <button @click="setStatusFilter('all')" class="btn-close btn-close-white ms-1" style="font-size: 0.75em;"></button>
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

    <!-- School Years Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div v-if="filteredSchoolYears.length === 0" class="text-center py-5">
          <i class="fas fa-calendar fa-4x text-muted mb-3"></i>
          <h5 class="text-muted">No school years found</h5>
          <p class="text-muted mb-3">
            {{ hasActiveFilters ? 'Try adjusting your filters' : 'Get started by adding your first school year' }}
          </p>
          <Link v-if="!hasActiveFilters" href="/admin/school-years/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add First School Year
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
                <th scope="col">Year</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Current</th>
                <th scope="col">Active</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="schoolYear in filteredSchoolYears" :key="schoolYear.id">
                <td>{{ schoolYear.year_name }}</td>
                <td>{{ formatDate(schoolYear.start_date) }}</td>
                <td>{{ formatDate(schoolYear.end_date) }}</td>
                <td>
                  <span 
                    class="badge"
                    :class="schoolYear.is_current ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger'"
                  >
                    {{ schoolYear.is_current ? 'Yes' : 'No' }}
                  </span>
                </td>
                <td>
                  <span 
                    class="badge"
                    :class="schoolYear.is_active ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger'"
                  >
                    {{ schoolYear.is_active ? 'Yes' : 'No' }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <Link 
                      :href="`/admin/school-years/${schoolYear.id}`" 
                      class="btn btn-outline-primary btn-sm"
                      title="View"
                    >
                      <i class="fas fa-eye"></i>
                    </Link>
                    <Link 
                      :href="`/admin/school-years/${schoolYear.id}/edit`" 
                      class="btn btn-outline-primary btn-sm"
                      title="Edit"
                    >
                      <i class="fas fa-edit"></i>
                    </Link>
                    <button 
                      @click="confirmDelete(schoolYear)" 
                      class="btn btn-outline-primary btn-sm"
                      title="Delete"
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
              <div class="calendar-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                <i class="fas fa-calendar fa-2x"></i>
              </div>
              <h6 class="fw-semibold">Delete School Year</h6>
              <p class="text-muted mb-3">
                Are you sure you want to delete <strong>{{ schoolYearToDelete?.year_name }}</strong>?
              </p>
            </div>
            <div class="alert alert-warning border-0" role="alert">
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
              @click="deleteSchoolYear"
              :disabled="deleteForm.processing"
            >
              <i v-if="deleteForm.processing" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleteForm.processing ? 'Deleting...' : 'Delete School Year' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { showSuccessToast, showErrorToast } from '@/Components/SweetAlert.js';

const { schoolYears } = defineProps({
  schoolYears: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');
const selectedStatus = ref('all');
const deleteForm = useForm({});
const schoolYearToDelete = ref(null);

const filteredSchoolYears = computed(() => {
  let filtered = [...schoolYears];

  // Filter by status
  if (selectedStatus.value === 'current') {
    filtered = filtered.filter(year => year.is_current);
  } else if (selectedStatus.value === 'active') {
    filtered = filtered.filter(year => year.is_active);
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(year => 
      year.year_name.toLowerCase().includes(query)
    );
  }

  return filtered;
});

const hasActiveFilters = computed(() => {
  return selectedStatus.value !== 'all' || searchQuery.value !== '';
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const setStatusFilter = (status) => {
  selectedStatus.value = status;
};

const clearAllFilters = () => {
  selectedStatus.value = 'all';
  searchQuery.value = '';
};

const confirmDelete = (schoolYear) => {
  schoolYearToDelete.value = schoolYear;
  const modal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
  modal.show();
};

const deleteSchoolYear = () => {
  if (schoolYearToDelete.value) {
    const yearName = schoolYearToDelete.value.year_name;
    deleteForm.delete(`/admin/school-years/${schoolYearToDelete.value.id}`, {
      onSuccess: () => {
        const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
        showSuccessToast('School Year Deleted!', `${yearName} has been removed successfully.`);
        schoolYearToDelete.value = null;
      },
      onError: (errors) => {
        const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
        const firstError = Object.values(errors)[0];
        showErrorToast('Delete Failed', firstError || 'Unable to delete school year. Please try again.');
        schoolYearToDelete.value = null;
      }
    });
  }
};

// Check for flash messages
if (window.$page?.props?.flash?.success) {
  showSuccessToast('Success!', window.$page.props.flash.success);
}
if (window.$page?.props?.flash?.error) {
  showErrorToast('Error', window.$page.props.flash.error);
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
  .row.g-2 {
    flex-direction: column;
  }
  
  .col-auto {
    width: 100%;
  }
  
  .card.border-0 {
    width: 100%;
  }
}

.bg-success-soft {
  background-color: rgba(40, 167, 69, 0.1);
}

.bg-danger-soft {
  background-color: rgba(220, 53, 69, 0.1);
}

</style>