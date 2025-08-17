<template>
  <AdminLayout page-title="Schedule Management">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary mb-2">
          Schedule Management
        </h2>
        <p class="text-muted mb-0">Manage class schedules</p>
      </div>
    </div>

    <!-- Section Selection -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <label class="form-label fw-bold text-dark mb-2">Select Section to Manage Schedule</label>
        <div class="d-flex justify-content-between g-3">
          <div class="col-md-3">
            <select v-model="selectedSectionId" class="form-select" required>
              <option value="">Choose a section...</option>
              <option v-for="section in sections" :key="section.id" :value="section.id">
                {{ section.grade_level }}-{{ section.section_name }}
              </option>
            </select>
          </div>
          <div class="">
            <Link
              href="/admin/schedules/create"
              class="btn btn-primary"
            >
              Manage Schedule
            </Link>
          </div>
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
                      </div>
                    </div>
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
        <p class="text-muted">Please select a section above to show its weekly schedule.</p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
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
      }
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
  watch: {
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
    loadSchedules(sectionId) {
      this.$inertia.get('/admin/schedules', { section_id: sectionId }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          console.log('Schedules loaded for section:', sectionId)
        },
        onError: () => {
          showErrorToast('Error', 'Unable to load schedules.')
        }
      })
    },
    refreshSchedule() {
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