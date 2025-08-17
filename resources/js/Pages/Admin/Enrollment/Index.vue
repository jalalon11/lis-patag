<template>
  <AdminLayout title="Enrolled Students">
    <div class="container-fluid py-4">
      <!-- Page Header -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h1 class="h3 mb-0 text-gray-800">Enrolled Students</h1>
                <p class="text-muted mb-0">
                  Manage all currently enrolled students across all grade levels and sections
                </p>
              </div>
              <div>
                <Link
                  href="/admin/admission"
                  class="btn btn-primary btn-sm"
                >
                  <i class="fas fa-user-plus me-2"></i>
                  Enroll New Student
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <label for="grade_level" class="form-label">Grade Level</label>
                  <select
                    id="grade_level"
                    v-model="filters.grade_level"
                    class="form-select"
                    @change="applyFilters"
                  >
                    <option value="">All Grade Levels</option>
                    <option v-for="level in gradeLevels" :key="level" :value="level">
                      {{ level }}
                    </option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="section" class="form-label">Section</label>
                  <select
                    id="section"
                    v-model="filters.section_id"
                    class="form-select"
                    @change="applyFilters"
                    :disabled="!filters.grade_level"
                  >
                    <option value="">All Sections</option>
                    <option
                      v-for="section in filteredSections"
                      :key="section.id"
                      :value="section.id"
                    >
                      {{ section.section_name }}
                    </option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="status" class="form-label">Status</label>
                  <select
                    id="status"
                    v-model="filters.status"
                    class="form-select"
                    @change="applyFilters"
                  >
                    <option value="">All Statuses</option>
                    <option value="Enrolled">Enrolled</option>
                    <option value="Dropped">Dropped</option>
                    <option value="Transferred">Transferred</option>
                    <option value="Graduated">Graduated</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="search" class="form-label">Search</label>
                  <div class="input-group">
                    <input
                      type="text"
                      id="search"
                      v-model="filters.search"
                      class="form-control"
                      placeholder="Search by name or ID..."
                      @input="debounceSearch"
                    />
                    <button
                      class="btn btn-outline-secondary"
                      type="button"
                      @click="clearFilters"
                      title="Clear filters"
                    >
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Enrollment Table -->
      <div class="row">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div v-if="isLoading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading enrollment data...</p>
              </div>
              <div v-else-if="enrollments.data.length === 0" class="text-center py-5">
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No enrolled students found</h5>
                <p class="text-muted">
                  {{ hasFilters ? 'Try adjusting your filters or search criteria' : 'Enroll students to get started' }}
                </p>
                <Link
                  v-if="!hasFilters"
                  href="/admin/admission"
                  class="btn btn-primary btn-sm mt-2"
                >
                  <i class="fas fa-user-plus me-2"></i>
                  Enroll New Student
                </Link>
              </div>
              <div v-else>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Grade & Section</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Enrollment Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="enrollment in enrollments.data" :key="enrollment.id">
                        <td>
                          <span class="badge bg-primary-soft text-primary">
                            {{ enrollment.student.student_id }}
                          </span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary-soft text-primary me-2">
                              {{ getInitials(enrollment.student) }}
                            </div>
                            <div>
                              <div class="fw-bold">
                                {{ enrollment.student.first_name }}
                                {{ enrollment.student.middle_name ? enrollment.student.middle_name.charAt(0) + '.' : '' }}
                                {{ enrollment.student.last_name }}
                                {{ enrollment.student.suffix ? enrollment.student.suffix : '' }}
                              </div>
                              <small class="text-muted">{{ enrollment.student.email }}</small>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div>
                            <span class="fw-bold">{{ enrollment.student.grade_level }}</span>
                            <span class="text-muted"> | </span>
                            <span>{{ enrollment.section.section_name }}</span>
                          </div>
                        </td>
                        <td>
                          <span
                            class="badge"
                            :class="{
                              'bg-info-soft text-info': enrollment.student.gender === 'Male',
                              'bg-danger-soft text-danger': enrollment.student.gender === 'Female'
                            }"
                          >
                            <i
                              class="fas"
                              :class="{
                                'fa-mars': enrollment.student.gender === 'Male',
                                'fa-venus': enrollment.student.gender === 'Female'
                              }"
                            ></i>
                            {{ enrollment.student.gender }}
                          </span>
                        </td>
                        <td>
                          {{ formatDate(enrollment.enrollment_date) }}
                        </td>
                        <td>
                          <span
                            class="badge"
                            :class="{
                              'bg-success': enrollment.status === 'Enrolled',
                              'bg-danger': enrollment.status === 'Dropped',
                              'bg-warning text-dark': enrollment.status === 'Transferred',
                              'bg-info': enrollment.status === 'Graduated'
                            }"
                          >
                            {{ enrollment.status }}
                          </span>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button
                              class="btn btn-sm btn-outline-primary"
                              @click="openUpdateStatusModal(enrollment)"
                              title="Update Status"
                            >
                              <i class="fas fa-exchange-alt"></i>
                            </button>
                            <button
                              class="btn btn-sm btn-outline-secondary"
                              @click="openTransferModal(enrollment)"
                              title="Transfer Section"
                              :disabled="enrollment.status !== 'Enrolled'"
                            >
                              <i class="fas fa-random"></i>
                            </button>
                            <Link
                              :href="`/admin/students/${enrollment.student.id}`"
                              class="btn btn-sm btn-outline-info"
                              title="View Student Details"
                            >
                              <i class="fas fa-eye"></i>
                            </Link>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                  <div>
                    <p class="text-muted mb-0">
                      Showing {{ enrollments.from }} to {{ enrollments.to }} of {{ enrollments.total }} entries
                    </p>
                  </div>
                  <div>
                    <nav aria-label="Page navigation">
                      <ul class="pagination pagination-sm mb-0">
                        <li
                          v-for="link in enrollments.links"
                          :key="link.label"
                          class="page-item"
                          :class="{
                            'active': link.active,
                            'disabled': !link.url
                          }"
                        >
                          <a
                            class="page-link"
                            href="#"
                            @click.prevent="goToPage(link)"
                            v-html="link.label"
                          ></a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true" ref="updateStatusModalRef">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-exchange-alt me-2"></i>
              Update Enrollment Status
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateEnrollmentStatus">
              <div class="mb-3">
                <label class="form-label">Student</label>
                <div class="fw-bold">
                  {{ selectedEnrollment?.student?.first_name }}
                  {{ selectedEnrollment?.student?.middle_name ? selectedEnrollment?.student?.middle_name.charAt(0) + '.' : '' }}
                  {{ selectedEnrollment?.student?.last_name }}
                  {{ selectedEnrollment?.student?.suffix ? selectedEnrollment?.student?.suffix : '' }}
                </div>
                <small class="text-muted">ID: {{ selectedEnrollment?.student?.student_id }}</small>
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select
                  id="status"
                  v-model="statusForm.status"
                  class="form-select"
                  :class="{ 'is-invalid': statusForm.errors.status }"
                  required
                >
                  <option value="">Select Status</option>
                  <option value="Enrolled">Enrolled</option>
                  <option value="Dropped">Dropped</option>
                  <option value="Transferred">Transferred</option>
                  <option value="Graduated">Graduated</option>
                </select>
                <div v-if="statusForm.errors.status" class="invalid-feedback">
                  {{ statusForm.errors.status }}
                </div>
              </div>

              <div class="mb-3" v-if="statusForm.status === 'Dropped' || statusForm.status === 'Transferred'">
                <label for="reason_for_status_change" class="form-label">Reason <span class="text-danger">*</span></label>
                <textarea
                  id="reason_for_status_change"
                  v-model="statusForm.reason_for_status_change"
                  class="form-control"
                  :class="{ 'is-invalid': statusForm.errors.reason_for_status_change }"
                  rows="3"
                  required
                ></textarea>
                <div v-if="statusForm.errors.reason_for_status_change" class="invalid-feedback">
                  {{ statusForm.errors.reason_for_status_change }}
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" :disabled="statusForm.processing">
                  <span v-if="statusForm.processing" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                  Update Status
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Transfer Section Modal -->
    <div class="modal fade" id="transferSectionModal" tabindex="-1" aria-hidden="true" ref="transferSectionModalRef">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-random me-2"></i>
              Transfer to Different Section
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="transferStudentSection">
              <div class="mb-3">
                <label class="form-label">Student</label>
                <div class="fw-bold">
                  {{ selectedEnrollment?.student?.first_name }}
                  {{ selectedEnrollment?.student?.middle_name ? selectedEnrollment?.student?.middle_name.charAt(0) + '.' : '' }}
                  {{ selectedEnrollment?.student?.last_name }}
                  {{ selectedEnrollment?.student?.suffix ? selectedEnrollment?.student?.suffix : '' }}
                </div>
                <small class="text-muted">ID: {{ selectedEnrollment?.student?.student_id }}</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Current Section</label>
                <div>
                  <span class="badge bg-info-soft text-info">
                    {{ selectedEnrollment?.student?.grade_level }} - {{ selectedEnrollment?.section?.section_name }}
                  </span>
                </div>
              </div>

              <div class="mb-3">
                <label for="new_section_id" class="form-label">New Section <span class="text-danger">*</span></label>
                <select
                  id="new_section_id"
                  v-model="transferForm.section_id"
                  class="form-select"
                  :class="{ 'is-invalid': transferForm.errors.section_id }"
                  required
                >
                  <option value="">Select New Section</option>
                  <optgroup
                    v-for="(sectionGroup, gradeLevel) in availableSections"
                    :key="gradeLevel"
                    :label="gradeLevel"
                  >
                    <option
                      v-for="section in sectionGroup"
                      :key="section.id"
                      :value="section.id"
                      :disabled="section.id === selectedEnrollment?.section_id || section.current_enrollment >= section.capacity"
                    >
                      {{ section.section_name }}
                      ({{ section.current_enrollment }}/{{ section.capacity }})
                      {{ section.current_enrollment >= section.capacity ? '- FULL' : '' }}
                    </option>
                  </optgroup>
                </select>
                <div v-if="transferForm.errors.section_id" class="invalid-feedback">
                  {{ transferForm.errors.section_id }}
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" :disabled="transferForm.processing">
                  <span v-if="transferForm.processing" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                  Transfer Student
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'

export default {
  name: 'EnrollmentIndex',
  components: {
    AdminLayout,
    Link
  },
  props: {
    enrollments: Object,
    sections: Object,
    currentSchoolYear: Object,
    gradeLevels: Array
  },
  setup(props) {
    // Bootstrap modal instances
    const updateStatusModalRef = ref(null)
    const transferSectionModalRef = ref(null)
    let updateStatusModal = null
    let transferSectionModal = null

    // State
    const isLoading = ref(false)
    const selectedEnrollment = ref(null)
    const filters = ref({
      grade_level: '',
      section_id: '',
      status: '',
      search: ''
    })
    let searchTimeout = null

    // Forms
    const statusForm = useForm({
      status: '',
      reason_for_status_change: ''
    })

    const transferForm = useForm({
      section_id: ''
    })

    // Computed properties
    const hasFilters = computed(() => {
      return (
        filters.value.grade_level ||
        filters.value.section_id ||
        filters.value.status ||
        filters.value.search
      )
    })

    const filteredSections = computed(() => {
      if (!filters.value.grade_level || !props.sections) return []
      return props.sections[filters.value.grade_level] || []
    })

    const availableSections = computed(() => {
      // Filter sections by the selected enrollment's grade level
      if (!selectedEnrollment.value || !props.sections) return {}
      
      const gradeLevel = selectedEnrollment.value.student.grade_level
      const result = {}
      result[gradeLevel] = props.sections[gradeLevel] || []
      return result
    })

    // Methods
    const applyFilters = () => {
      // Reset section_id if grade_level changes
      if (filters.value.grade_level === '') {
        filters.value.section_id = ''
      }
      
      router.get('/admin/enrollment', filters.value, {
        preserveState: true,
        preserveScroll: true,
        only: ['enrollments']
      })
    }

    const debounceSearch = () => {
      clearTimeout(searchTimeout)
      searchTimeout = setTimeout(() => {
        applyFilters()
      }, 500)
    }

    const clearFilters = () => {
      filters.value = {
        grade_level: '',
        section_id: '',
        status: '',
        search: ''
      }
      applyFilters()
    }

    const goToPage = (link) => {
      if (link.url) {
        router.get(link.url, {}, {
          preserveState: true,
          preserveScroll: true,
          only: ['enrollments']
        })
      }
    }

    const getInitials = (student) => {
      if (!student) return ''
      return (student.first_name.charAt(0) + student.last_name.charAt(0)).toUpperCase()
    }

    const formatDate = (dateString) => {
      if (!dateString) return ''
      const date = new Date(dateString)
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      }).format(date)
    }

    const openUpdateStatusModal = (enrollment) => {
      selectedEnrollment.value = enrollment
      statusForm.status = enrollment.status
      statusForm.reason_for_status_change = enrollment.reason_for_status_change || ''
      statusForm.clearErrors()
      
      if (updateStatusModal) {
        updateStatusModal.show()
      }
    }

    const updateEnrollmentStatus = () => {
      if (!selectedEnrollment.value) return
      
      statusForm.put(`/admin/enrollment/${selectedEnrollment.value.id}/status`, {
        preserveScroll: true,
        onSuccess: () => {
          if (updateStatusModal) {
            updateStatusModal.hide()
          }
          selectedEnrollment.value = null
          statusForm.reset()
        }
      })
    }

    const openTransferModal = (enrollment) => {
      selectedEnrollment.value = enrollment
      transferForm.section_id = ''
      transferForm.clearErrors()
      
      if (transferSectionModal) {
        transferSectionModal.show()
      }
    }

    const transferStudentSection = () => {
      if (!selectedEnrollment.value) return
      
      transferForm.put(`/admin/enrollment/${selectedEnrollment.value.id}/transfer`, {
        preserveScroll: true,
        onSuccess: () => {
          if (transferSectionModal) {
            transferSectionModal.hide()
          }
          selectedEnrollment.value = null
          transferForm.reset()
        }
      })
    }

    // Initialize Bootstrap modals after component is mounted
    onMounted(() => {
      // Import Bootstrap modal dynamically to avoid SSR issues
      import('bootstrap/js/dist/modal').then(({ Modal }) => {
        if (updateStatusModalRef.value) {
          updateStatusModal = new Modal(updateStatusModalRef.value)
        }
        if (transferSectionModalRef.value) {
          transferSectionModal = new Modal(transferSectionModalRef.value)
        }
      })
    })

    // Watch for changes in props.enrollments
    watch(() => props.enrollments, (newValue) => {
      isLoading.value = false
    })

    return {
      isLoading,
      filters,
      selectedEnrollment,
      statusForm,
      transferForm,
      updateStatusModalRef,
      transferSectionModalRef,
      hasFilters,
      filteredSections,
      availableSections,
      applyFilters,
      debounceSearch,
      clearFilters,
      goToPage,
      getInitials,
      formatDate,
      openUpdateStatusModal,
      updateEnrollmentStatus,
      openTransferModal,
      transferStudentSection
    }
  }
}
</script>

<style scoped>
.avatar-circle {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.bg-primary-soft {
  background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-soft {
  background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-soft {
  background-color: rgba(255, 193, 7, 0.1);
}

.bg-danger-soft {
  background-color: rgba(220, 53, 69, 0.1);
}

.bg-info-soft {
  background-color: rgba(13, 202, 240, 0.1);
}
</style>