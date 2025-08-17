<template>
  <AdminLayout page-title="Weekly Schedule Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Weekly Schedule Management Settings
        </h2>
        <p class="text-muted mb-0">Manage weekly class schedules by day</p>
      </div>
    </div>

    <!-- Section Selection -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <label class="form-label fw-bold text-dark mb-2">Select Section to Manage Schedule</label>
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <select v-model="selectedSectionId" class="form-select" required>
              <option value="">Choose a section...</option>
              <option v-for="section in sections" :key="section.id" :value="section.id">
                {{ section.grade_level }}-{{ section.section_name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Schedule Actions -->
    <div class="card border-0 shadow-sm mb-4" v-if="selectedSectionId">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">Weekly Schedule Actions</h5>
            <p class="text-muted mb-0">Use the button to add schedules for multiple days at once</p>
          </div>
          <button
            @click="showWeeklyScheduleModal()"
            class="btn btn-success d-flex align-items-center gap-2"
          >
            <i class="fas fa-calendar-week"></i>
            Add Weekly Schedule
          </button>
        </div>
      </div>
    </div>

    <!-- Weekly Schedule Grid -->
    <div class="card border-0 shadow-sm" v-if="selectedSectionId">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <!-- Days Header -->
            <thead class="bg-primary text-white">
              <tr>
                <th class="text-center" v-for="day in daysOfWeek" :key="day.id">
                  {{ day.name }}
                  <br>
                  <small class="opacity-75 text-xs">
                    {{ getScheduleCount(day.id) }} classes
                  </small>
                </th>
              </tr>
            </thead>
            <!-- Schedule Display -->
            <tbody>
              <tr>
                <td v-for="day in daysOfWeek" :key="day.id" class="position-relative p-0" style="min-height: 400px;">
                  <!-- Schedules for this day -->
                  <div v-for="schedule in getSchedulesForDay(day.id)" :key="schedule.id" class="border-bottom">
                    <div class="bg-white p-3 rounded-3 position-relative transition-all">
                      <div class="d-flex justify-content-between">
                        <div class="flex-grow-1">
                          <h6 class="mb-1 fw-bold">{{ getSubjectName(schedule.subject_id) }}</h6>
                          <small class="fw-semibold text-xs text-muted">
                            {{ formatTimeRange(schedule.start_time, schedule.end_time) }}
                          </small>
                          <div class="mb-2">
                            <small class="text-muted text-xs">
                              {{ getTeacherName(schedule.teacher_id) }}
                            </small>
                          </div>
                        </div>
                        <div class="dropdown">
                          <button 
                            class="btn btn-sm btn-link text-muted p-0" 
                            type="button" 
                            data-bs-toggle="dropdown"
                            style="font-size: 12px;"
                          >
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                              <button @click="editSchedule(schedule)" class="dropdown-item">
                                <i class="fas fa-edit me-2 text-primary"></i>Edit
                              </button>
                            </li>
                            <li>
                              <button @click="confirmDelete(schedule)" class="dropdown-item text-danger">
                                <i class="fas fa-trash me-2"></i>Delete
                              </button>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Add Schedule Button -->
                  <div class="p-2">
                    <button
                      @click="quickAddSchedule(day.id)"
                      class="btn btn-outline-primary btn-sm w-100 d-flex flex-column align-items-center justify-content-center"
                      style="border-style: dashed; border-width: 2px; padding: 8px;"
                    >
                      <i class="fas fa-plus mb-1"></i>
                      <small class="fw-semibold">Add Schedule</small>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- No Section Selected State -->
    <div class="card border-0 shadow-sm text-center py-5" v-else>
      <div class="card-body">
        <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No Section Selected</h5>
        <p class="text-muted">Please select a section above to manage its weekly schedule.</p>
      </div>
    </div>

    <!-- Add/Edit Schedule Modal (Single Day) -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scheduleModalLabel">
              {{ editingSchedule ? 'Edit Schedule' : 'Add New Schedule' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveSchedule">
              <div class="row g-3">
                <!-- Teacher Selection -->
                <div class="col-md-6">
                  <label class="form-label">Teacher <span class="text-danger">*</span></label>
                  <select v-model="scheduleForm.teacher_id" class="form-select" required>
                    <option value="">Select Teacher</option>
                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                      {{ getTeacherNameById(teacher.id) }}
                    </option>
                  </select>
                </div>

                <!-- Subject Selection -->
                <div class="col-md-6">
                  <label class="form-label">Subject <span class="text-danger">*</span></label>
                  <select v-model="scheduleForm.subject_id" class="form-select" required>
                    <option value="">Select Subject</option>
                    <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                      {{ subject.subject_name }}
                    </option>
                  </select>
                </div>

                <!-- Section (Read-only when editing, hidden when adding since it's pre-filled) -->
                <div class="col-md-6" v-if="editingSchedule">
                  <label class="form-label">Section</label>
                  <input 
                    :value="selectedSectionInfo ? `${selectedSectionInfo.grade_level}-${selectedSectionInfo.section_name}` : ''" 
                    type="text" 
                    class="form-control" 
                    readonly
                  >
                </div>

                <!-- Hidden section input for form submission -->
                <input v-model="scheduleForm.section_id" type="hidden">

                <!-- Day of Week (Single Selection) -->
                <div :class="editingSchedule ? 'col-md-6' : 'col-md-6'">
                  <label class="form-label">Day <span class="text-danger">*</span></label>
                  <select v-model="scheduleForm.day_of_week" class="form-select" required>
                    <option value="">Select Day</option>
                    <option v-for="day in daysOfWeek" :key="day.id" :value="day.id">
                      {{ day.name }}
                    </option>
                  </select>
                </div>

                <!-- Start Time -->
                <div class="col-md-6">
                  <label class="form-label">Start Time <span class="text-danger">*</span></label>
                  <input v-model="scheduleForm.start_time" type="time" class="form-control" required>
                </div>

                <!-- End Time -->
                <div class="col-md-6">
                  <label class="form-label">End Time <span class="text-danger">*</span></label>
                  <input v-model="scheduleForm.end_time" type="time" class="form-control" required>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" @click="saveSchedule" class="btn btn-primary" :disabled="saving">
              <i v-if="saving" class="fas fa-spinner fa-spin me-2"></i>
              {{ saving ? 'Saving...' : (editingSchedule ? 'Update Schedule' : 'Add Schedule') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Weekly Schedule Modal (Multiple Days) -->
    <div class="modal fade" id="weeklyScheduleModal" tabindex="-1" aria-labelledby="weeklyScheduleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="weeklyScheduleModalLabel">
              <i class="fas fa-calendar-week me-2"></i>
              Add Weekly Schedule
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Weekly Schedule:</strong> Create schedules for multiple days with the same time slots and subject.
            </div>
            <form @submit.prevent="saveWeeklySchedule">
              <div class="row g-3">
                <!-- Teacher Selection -->
                <div class="col-md-6">
                  <label class="form-label">Teacher <span class="text-danger">*</span></label>
                  <select v-model="weeklyScheduleForm.teacher_id" class="form-select" required>
                    <option value="">Select Teacher</option>
                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                      {{ getTeacherNameById(teacher.id) }}
                    </option>
                  </select>
                </div>

                <!-- Subject Selection -->
                <div class="col-md-6">
                  <label class="form-label">Subject <span class="text-danger">*</span></label>
                  <select v-model="weeklyScheduleForm.subject_id" class="form-select" required>
                    <option value="">Select Subject</option>
                    <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                      {{ subject.subject_name }}
                    </option>
                  </select>
                </div>

                <!-- Hidden section input for form submission -->
                <input v-model="weeklyScheduleForm.section_id" type="hidden">

                <!-- Day Selection -->
                <div class="col-12">
                  <label class="form-label">Day Selection <span class="text-danger">*</span></label>
                  
                  <!-- Select All Weekdays Option -->
                  <div class="mb-3">
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="selectAllWeekdaysWeekly"
                        v-model="selectAllWeekdaysWeekly"
                        @change="toggleAllWeekdaysWeekly"
                      >
                      <label class="form-check-label fw-bold text-success" for="selectAllWeekdaysWeekly">
                        <i class="fas fa-calendar-week me-2"></i>
                        Select All Weekdays (Monday - Friday)
                      </label>
                      <small class="text-muted d-block">Apply the same time slots to all weekdays for subjects taught consistently throughout the week</small>
                    </div>
                  </div>

                  <!-- Individual Day Selection -->
                  <div>
                    <label class="form-label small text-muted mb-2">Or select individual days:</label>
                    <div class="row g-2">
                      <div class="col-md-2 col-4" v-for="day in daysOfWeek" :key="day.id">
                        <div class="form-check">
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            :id="'weekly_day_' + day.id"
                            :value="day.id"
                            v-model="weeklyScheduleForm.selected_days"
                            @change="updateWeekdaySelectionWeekly"
                          >
                          <label class="form-check-label small" :for="'weekly_day_' + day.id">
                            {{ day.name }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Start Time -->
                <div class="col-md-6">
                  <label class="form-label">Start Time <span class="text-danger">*</span></label>
                  <input v-model="weeklyScheduleForm.start_time" type="time" class="form-control" required>
                </div>

                <!-- End Time -->
                <div class="col-md-6">
                  <label class="form-label">End Time <span class="text-danger">*</span></label>
                  <input v-model="weeklyScheduleForm.end_time" type="time" class="form-control" required>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" @click="saveWeeklySchedule" class="btn btn-success" :disabled="savingWeekly">
              <i v-if="savingWeekly" class="fas fa-spinner fa-spin me-2"></i>
              {{ savingWeekly ? 'Creating Schedules...' : 'Create Weekly Schedules' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body text-center">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h6>Delete Schedule</h6>
            <p>Are you sure you want to delete this schedule?</p>
            <div v-if="scheduleToDelete" class="alert alert-light">
              <strong>{{ getSubjectName(scheduleToDelete.subject_id) }}</strong><br>
              {{ getTeacherName(scheduleToDelete.teacher_id) }}<br>
              {{ scheduleToDelete.section?.grade_level }}-{{ scheduleToDelete.section?.section_name }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" @click="deleteSchedule" class="btn btn-danger" :disabled="deleting">
              <i v-if="deleting" class="fas fa-spinner fa-spin me-2"></i>
              {{ deleting ? 'Deleting...' : 'Delete Schedule' }}
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
  name: 'WeeklyScheduleManagement',
  components: {
    AdminLayout,
    Link
  },
  props: {
    schedules: {
      type: Array,
      default: () => []
    },
    teacherSubjectSections: {
      type: Array,
      default: () => []
    },
    sections: {
      type: Array,
      default: () => []
    },
    teachers: {
      type: Array,
      default: () => []
    },
    subjects: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      selectedSectionId: '',
      scheduleToDelete: null,
      editingSchedule: null,
      saving: false,
      deleting: false,
      selectAllWeekdays: false,
      
      daysOfWeek: [
        { id: 'Monday', name: 'Monday' },
        { id: 'Tuesday', name: 'Tuesday' },
        { id: 'Wednesday', name: 'Wednesday' },
        { id: 'Thursday', name: 'Thursday' },
        { id: 'Friday', name: 'Friday' }
      ],

      scheduleForm: {
        teacher_id: '',
        subject_id: '',
        section_id: '',
        day_of_week: '',
        start_time: '',
        end_time: ''
      },
      weeklyScheduleForm: {
        teacher_id: '',
        subject_id: '',
        section_id: '',
        start_time: '',
        end_time: '',
        selected_days: []
      },
      savingWeekly: false,
      selectAllWeekdaysWeekly: false
    }
  },
  computed: {
    filteredSchedules() {
      if (!this.schedules || !Array.isArray(this.schedules)) {
        return []
      }
      
      // Only show schedules for the selected section
      return this.schedules.filter(s => s.section_id == this.selectedSectionId)
    },

    selectedSectionInfo() {
      if (!this.selectedSectionId || !this.sections) {
        return null
      }
      return this.sections.find(s => s.id == this.selectedSectionId)
    }
  },
  methods: {
    formatTimeRange(startTime, endTime) {
      if (!startTime || !endTime) return 'N/A';
      
      // Handle time strings (HH:MM format) or datetime strings
      let start, end;
      
      if (startTime.includes('T')) {
        // Full datetime string - these are UTC, convert to Philippine time
        start = new Date(startTime);
        end = new Date(endTime);
      } else {
        // Time string only (HH:MM format) - these are already local times
        start = new Date(`1970-01-01T${startTime}:00`);
        end = new Date(`1970-01-01T${endTime}:00`);
      }
      
      const options = { hour: 'numeric', minute: '2-digit', hour12: true };
      return `${start.toLocaleTimeString('en-US', options)} - ${end.toLocaleTimeString('en-US', options)}`;
    },
    getSchedulesForDay(dayId) {
      if (!this.filteredSchedules || !Array.isArray(this.filteredSchedules)) {
        return []
      }
      
      return this.filteredSchedules.filter(schedule => schedule.day_of_week === dayId)
        .sort((a, b) => a.start_time.localeCompare(b.start_time))
    },

    getScheduleCount(dayId) {
      if (!this.filteredSchedules || !Array.isArray(this.filteredSchedules)) {
        return 0
      }
      return this.filteredSchedules.filter(s => s.day_of_week === dayId).length
    },

    getTeacherName(teacherId) {
      if (!teacherId || !this.teachers) return 'Unknown Teacher'
      
      const teacher = this.teachers.find(t => t.id == teacherId)
      if (!teacher) return 'Unknown Teacher'
      
      if (teacher.first_name && teacher.last_name) {
        return `${teacher.first_name} ${teacher.last_name}`
      } else if (teacher.name) {
        return teacher.name
      } else {
        return `Teacher #${teacher.id}`
      }
    },

    getTeacherNameById(teacherId) {
      return this.getTeacherName(teacherId)
    },

    getSubjectName(subjectId) {
      if (!subjectId || !this.subjects) return 'Unknown Subject'
      
      const subject = this.subjects.find(s => s.id == subjectId)
      return subject ? subject.subject_name : 'Unknown Subject'
    },

    quickAddSchedule(dayId) {
      if (!this.selectedSectionId) {
        showErrorToast('Error', 'Please select a section first.')
        return
      }
      
      this.editingSchedule = null
      this.resetForm()
      
      // Pre-fill day and section
      this.scheduleForm.day_of_week = dayId
      this.scheduleForm.section_id = this.selectedSectionId
      
      const modal = new window.bootstrap.Modal(document.getElementById('scheduleModal'))
      modal.show()
    },

    showAddScheduleModal() {
      if (!this.selectedSectionId) {
        showErrorToast('Error', 'Please select a section first.')
        return
      }
      
      this.editingSchedule = null
      this.resetForm()
      
      // Pre-fill section
      this.scheduleForm.section_id = this.selectedSectionId
      
      const modal = new window.bootstrap.Modal(document.getElementById('scheduleModal'))
      modal.show()
    },

    editSchedule(schedule) {
      this.editingSchedule = schedule
      this.scheduleForm = { ...schedule }
      
      const modal = new window.bootstrap.Modal(document.getElementById('scheduleModal'))
      modal.show()
    },

    resetForm() {
      this.scheduleForm = {
        teacher_id: '',
        subject_id: '',
        section_id: '',
        day_of_week: '',
        start_time: '',
        end_time: ''
      }
    },



    saveSchedule() {
      this.saving = true
      
      if (this.editingSchedule) {
        // Handle single schedule update for editing
        const url = `/admin/schedules/${this.editingSchedule.id}`
        
        this.$inertia.put(url, this.scheduleForm, {
          onSuccess: () => {
            this.saving = false
            const modal = window.bootstrap.Modal.getInstance(document.getElementById('scheduleModal'))
            modal.hide()
            
            showSuccessToast('Schedule Updated!', 'The schedule has been updated successfully.')
          },
          onError: (errors) => {
            this.saving = false
            showErrorToast('Error', 'Please check the form for errors.')
          }
        })
      } else {
        // Handle single schedule creation
        this.$inertia.post('/admin/schedules', this.scheduleForm, {
          onSuccess: () => {
            this.saving = false
            const modal = window.bootstrap.Modal.getInstance(document.getElementById('scheduleModal'))
            modal.hide()
            
            showSuccessToast('Schedule Created!', 'The schedule has been created successfully.')
          },
          onError: (errors) => {
            this.saving = false
            showErrorToast('Error', 'Please check the form for errors.')
          }
        })
      }
    },



    confirmDelete(schedule) {
      this.scheduleToDelete = schedule
      const modal = new window.bootstrap.Modal(document.getElementById('deleteModal'))
      modal.show()
    },

    deleteSchedule() {
      this.deleting = true
      
      this.$inertia.delete(`/admin/schedules/${this.scheduleToDelete.id}`, {
        onSuccess: () => {
          this.deleting = false
          const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
          modal.hide()
          
          showSuccessToast('Schedule Deleted!', 'The schedule has been removed successfully.')
          this.scheduleToDelete = null
        },
        onError: () => {
          this.deleting = false
          showErrorToast('Error', 'Unable to delete schedule.')
        }
      })
    },

    loadSchedules() {
      // This method can be used for manual refresh if needed
      // Removed automatic navigation to prevent page reload
      console.log('Selected section changed to:', this.selectedSectionId)
    },

    refreshSchedule() {
      this.$inertia.reload()
    },

    showWeeklyScheduleModal() {
      if (!this.selectedSectionId) {
        showErrorToast('Error', 'Please select a section first.')
        return
      }
      
      this.resetWeeklyForm()
      
      // Pre-fill section
      this.weeklyScheduleForm.section_id = this.selectedSectionId
      
      const modal = new window.bootstrap.Modal(document.getElementById('weeklyScheduleModal'))
      modal.show()
    },

    resetWeeklyForm() {
      this.weeklyScheduleForm = {
        teacher_id: '',
        subject_id: '',
        section_id: '',
        start_time: '',
        end_time: '',
        selected_days: []
      }
      this.selectAllWeekdaysWeekly = false
    },

    toggleAllWeekdaysWeekly() {
      if (this.selectAllWeekdaysWeekly) {
        // Select all weekdays (Monday to Friday)
        this.weeklyScheduleForm.selected_days = this.daysOfWeek.map(day => day.id)
      } else {
        // Deselect all days
        this.weeklyScheduleForm.selected_days = []
      }
    },

    updateWeekdaySelectionWeekly() {
      // Check if all weekdays are selected
      const allWeekdaysSelected = this.daysOfWeek.every(day => 
        this.weeklyScheduleForm.selected_days.includes(day.id)
      )
      this.selectAllWeekdaysWeekly = allWeekdaysSelected
    },

    saveWeeklySchedule() {
      this.savingWeekly = true
      
      // Validation for day selection
      if (this.weeklyScheduleForm.selected_days.length === 0) {
        this.savingWeekly = false
        showErrorToast('Error', 'Please select at least one day.')
        return
      }
      
      this.createMultipleWeeklySchedules()
    },

    async createMultipleWeeklySchedules() {
      const selectedDays = this.weeklyScheduleForm.selected_days
      let successCount = 0
      let errorCount = 0
      const errors = []
      
      for (const day of selectedDays) {
        const scheduleData = {
          teacher_id: this.weeklyScheduleForm.teacher_id,
          subject_id: this.weeklyScheduleForm.subject_id,
          section_id: this.weeklyScheduleForm.section_id,
          day_of_week: day,
          start_time: this.weeklyScheduleForm.start_time,
          end_time: this.weeklyScheduleForm.end_time
        }
        
        try {
          await new Promise((resolve, reject) => {
            this.$inertia.post('/admin/schedules', scheduleData, {
              onSuccess: () => {
                successCount++
                resolve()
              },
              onError: (error) => {
                errorCount++
                errors.push(`${day}: ${Object.values(error).flat().join(', ')}`)
                reject(error)
              },
              preserveState: true,
              preserveScroll: true
            })
          })
        } catch (error) {
          // Error already handled in onError callback
        }
      }
      
      this.savingWeekly = false
      const modal = window.bootstrap.Modal.getInstance(document.getElementById('weeklyScheduleModal'))
      modal.hide()
      
      if (successCount > 0 && errorCount === 0) {
        showSuccessToast(
          'Weekly Schedules Created!', 
          `Successfully created ${successCount} schedule${successCount > 1 ? 's' : ''} for the selected days.`
        )
      } else if (successCount > 0 && errorCount > 0) {
        showSuccessToast(
          'Partial Success', 
          `Created ${successCount} schedule${successCount > 1 ? 's' : ''}, but ${errorCount} failed. Please check for conflicts.`
        )
      } else {
        showErrorToast(
          'Error', 
          `Failed to create schedules. ${errors.length > 0 ? errors.join('; ') : 'Please check for conflicts or missing information.'}`
        )
      }
      
      // Refresh the page to show updated schedules
      this.$inertia.reload()
    }
  }
}
</script>

<style scoped>
.table td {
  vertical-align: top;
  width: 20%; /* 100% / 5 days */
}

.time-badge {
  font-size: 0.75rem;
}

.schedule-card {
  transition: all 0.2s ease;
}

@media (max-width: 1200px) {
  .schedule-card {
    font-size: 0.7rem;
    min-height: 50px;
  }
  
  .table th,
  .table td {
    padding: 0.25rem;
  }
}

@media (max-width: 768px) {
  .table-responsive {
    font-size: 0.6rem;
  }
}
</style>