<template>
  <TeacherLayout page-title="Grade Assessment">
    <div class="container-fluid">
      <!-- Header Section -->
      <div class="row mb-4">
        <div class="col-md-8">
          <h2 class="fw-bold text-primary mb-2">
            <i class="fas fa-medal me-3"></i>
            Grade Assessment Configuration
          </h2>
          <p class="text-muted mb-0">Configure grade weighted components and create assessments for your assigned subjects</p>
        </div>
      </div>

      <!-- Subject Selection -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-4">
              <label class="form-label fw-bold">Select Subject</label>
              <select v-model="selectedSubject" class="form-select" @change="loadGradeComponents">
                <option value="">Choose a subject...</option>
                <option v-for="subject in assignedSubjects" :key="subject.id" :value="subject.id">
                  {{ subject.subject_name }} (Grade {{ subject.grade_level }})
                </option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Section</label>
              <select v-model="selectedSection" class="form-select" @change="loadGradeComponents">
                <option value="">Choose a section...</option>
                <option v-for="section in filteredSections" :key="section.id" :value="section.id">
                  {{ section.section_name }}
                </option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Quarter</label>
              <select v-model="selectedQuarter" class="form-select" @change="loadGradeComponents">
                <option value="1">First Quarter</option>
                <option value="2">Second Quarter</option>
                <option value="3">Third Quarter</option>
                <option value="4">Fourth Quarter</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Grade Components Configuration -->
      <div v-if="selectedSubject && selectedSection" class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-cog me-2"></i>
            Grade Components Configuration
          </h5>
        </div>
        <div class="card-body">
          <div v-if="isLoading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading grade components...</p>
          </div>
          <div v-else>
            <GradeWeightedComponents 
              v-model="gradeComponents" 
              @update:modelValue="handleComponentsUpdate"
            />

            <div class="d-flex justify-content-end mt-4">
              <button 
                class="btn btn-primary px-4" 
                @click="saveGradeComponents"
                :disabled="isSaving"
              >
                <i class="fas fa-save me-2"></i>
                {{ isSaving ? 'Saving...' : 'Save Configuration' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Create Assessment Section -->
      <div v-if="selectedSubject && selectedSection && !isLoading" class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-plus-circle me-2"></i>
            Create Assessment
          </h5>
          <button 
            class="btn btn-sm btn-outline-primary" 
            @click="showCreateAssessmentForm = !showCreateAssessmentForm"
          >
            <i class="fas" :class="showCreateAssessmentForm ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
          </button>
        </div>
        <div class="card-body" v-if="showCreateAssessmentForm">
          <div class="mb-3">
            <label class="form-label fw-bold">Assessment Type</label>
            <select v-model="newAssessment.type" class="form-select">
              <option value="">Select assessment type...</option>
              <option v-for="component in gradeComponents" :key="component.id" :value="component.id">
                {{ component.name }}
              </option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-bold">Assessment Title</label>
            <input type="text" class="form-control" v-model="newAssessment.title" placeholder="Enter assessment title">
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Date</label>
              <input type="date" class="form-control" v-model="newAssessment.date">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Total Points</label>
              <input type="number" class="form-control" v-model="newAssessment.totalPoints" min="1">
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea class="form-control" v-model="newAssessment.description" rows="3" placeholder="Enter assessment description"></textarea>
          </div>
          
          <div class="d-flex justify-content-end">
            <button class="btn btn-secondary me-2" @click="resetNewAssessment">Cancel</button>
            <button 
              class="btn btn-success" 
              @click="createAssessment"
              :disabled="isCreatingAssessment || !isNewAssessmentValid"
            >
              <i class="fas fa-plus me-2"></i>
              {{ isCreatingAssessment ? 'Creating...' : 'Create Assessment' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Assessment List -->
      <div v-if="selectedSubject && selectedSection && !isLoading" class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-list-alt me-2"></i>
            Assessment List
          </h5>
        </div>
        <div class="card-body p-0">
          <div v-if="isLoadingAssessments" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading assessments...</p>
          </div>
          <div v-else-if="assessments.length === 0" class="text-center py-5">
            <i class="fas fa-clipboard-list fa-2x text-muted mb-3"></i>
            <h6 class="fw-bold">No Assessments Created</h6>
            <p class="text-muted mb-0">Create your first assessment using the form above</p>
          </div>
          <div v-else>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Total Points</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="assessment in assessments" :key="assessment.id">
                    <td>
                      <span class="badge" :class="getAssessmentTypeBadgeClass(assessment.type)">
                        {{ getAssessmentTypeName(assessment.type) }}
                      </span>
                    </td>
                    <td>{{ assessment.title }}</td>
                    <td>{{ formatDate(assessment.date) }}</td>
                    <td>{{ assessment.totalPoints }} pts</td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" title="Edit">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-success" title="Record Scores">
                          <i class="fas fa-clipboard-check"></i>
                        </button>
                        <button class="btn btn-outline-danger" title="Delete">
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
      </div>

      <!-- Empty State -->
      <div v-if="!(selectedSubject && selectedSection)" class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
          <h5 class="fw-bold">Select a Subject and Section</h5>
          <p class="text-muted mb-0">Please select a subject and section to configure grade components</p>
        </div>
      </div>
    </div>
  </TeacherLayout>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import GradeWeightedComponents from '@/Components/GradeWeightedComponents.vue'

export default {
  components: {
    TeacherLayout,
    GradeWeightedComponents
  },
  props: {
    teacherSubjects: {
      type: Array,
      default: () => []
    },
    sections: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    const selectedSubject = ref('')
    const selectedSection = ref('')
    const selectedQuarter = ref('1')
    const isLoading = ref(false)
    const isSaving = ref(false)
    const showCreateAssessmentForm = ref(false)
    const isCreatingAssessment = ref(false)
    const isLoadingAssessments = ref(false)
    const gradeComponents = ref([
      { name: 'Written Works', weight: 30, id: 'written_works' },
      { name: 'Performance Task', weight: 50, id: 'performance_task' },
      { name: 'Quarterly Assessment', weight: 20, id: 'quarterly_assessment' }
    ])
    
    // New assessment form data
    const newAssessment = ref({
      type: '',
      title: '',
      date: new Date().toISOString().split('T')[0], // Today's date in YYYY-MM-DD format
      totalPoints: 100,
      description: ''
    })
    
    // Mock assessments list
    const assessments = ref([])

    // Use the actual teacher subjects data passed from the controller
    const assignedSubjects = ref(props.teacherSubjects)

    const filteredSections = computed(() => {
      if (!selectedSubject.value) return []
      
      // Since we're using real data, we need to filter sections based on the schedules
      // All sections are available for all subjects since they come from the teacher's schedules
      return props.sections
    })
    
    const isNewAssessmentValid = computed(() => {
      return (
        newAssessment.value.type && 
        newAssessment.value.title && 
        newAssessment.value.date && 
        newAssessment.value.totalPoints > 0
      )
    })

    const loadGradeComponents = async () => {
      if (!selectedSubject.value || !selectedSection.value || !selectedQuarter.value) return
      
      isLoading.value = true
      isLoadingAssessments.value = true
      
      try {
        // Prepare the parameters for the API call
        const params = {
          subject_id: selectedSubject.value,
          section_id: selectedSection.value,
          quarter: selectedQuarter.value
        }
        
        console.log('Loading grade components with params:', params)
        
        // Simulate API call - in a real implementation, this would be an actual API call
        // In the future, this would be: const response = await axios.get('/api/teacher/grade-components', { params })
        await new Promise(resolve => setTimeout(resolve, 800))
        
        // For now, we'll just use default values until the API is implemented
        gradeComponents.value = [
          { name: 'Written Works', weight: 30, id: 'written_works' },
          { name: 'Performance Task', weight: 50, id: 'performance_task' },
          { name: 'Quarterly Assessment', weight: 20, id: 'quarterly_assessment' }
        ]
        
        // Load assessments
        await loadAssessments()
      } catch (error) {
        console.error('Error loading grade components:', error)
        // Handle error appropriately
      } finally {
        isLoading.value = false
      }
    }
    
    const loadAssessments = async () => {
      if (!selectedSubject.value || !selectedSection.value || !selectedQuarter.value) {
        isLoadingAssessments.value = false
        return
      }
      
      try {
        // Simulate API call - in a real implementation, this would be an actual API call
        await new Promise(resolve => setTimeout(resolve, 600))
        
        // Prepare the parameters for the API call
        const params = {
          subject_id: selectedSubject.value,
          section_id: selectedSection.value,
          quarter: selectedQuarter.value
        }
        
        console.log('Loading assessments with params:', params)
        
        // For now, we'll continue using mock data until the API is implemented
        // In the future, this would be: const response = await axios.get('/api/teacher/assessments', { params })
        assessments.value = [
          {
            id: 1,
            type: 'written_works',
            title: 'Quiz on Algebraic Expressions',
            date: '2023-09-15',
            totalPoints: 20,
            description: 'Covers topics from Chapter 2'
          },
          {
            id: 2,
            type: 'performance_task',
            title: 'Group Project: Data Analysis',
            date: '2023-10-05',
            totalPoints: 50,
            description: 'Students will analyze and present data sets'
          },
          {
            id: 3,
            type: 'quarterly_assessment',
            title: 'First Quarter Examination',
            date: '2023-10-20',
            totalPoints: 100,
            description: 'Comprehensive exam covering all first quarter topics'
          }
        ]
      } catch (error) {
        console.error('Error loading assessments:', error)
        // Handle error appropriately
      } finally {
        isLoadingAssessments.value = false
      }
    }

    const handleComponentsUpdate = (newComponents) => {
      gradeComponents.value = newComponents
    }

    const saveGradeComponents = async () => {
      if (!selectedSubject.value || !selectedSection.value || !selectedQuarter.value) {
        alert('Please select a subject, section, and quarter before saving')
        return
      }
      
      isSaving.value = true
      
      try {
        // Prepare the data for the API call
        const componentData = {
          subject_id: selectedSubject.value,
          section_id: selectedSection.value,
          quarter: selectedQuarter.value,
          components: gradeComponents.value
        }
        
        console.log('Saving grade components:', componentData)
        
        // Simulate API call - in a real implementation, this would be an actual API call
        // In the future, this would be: const response = await axios.post('/api/teacher/grade-components', componentData)
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        // Show success message
        alert('Grade components saved successfully!')
      } catch (error) {
        console.error('Error saving grade components:', error)
        // Handle error appropriately
        alert('Failed to save grade components. Please try again.')
      } finally {
        isSaving.value = false
      }
    }
    
    const createAssessment = async () => {
      if (!isNewAssessmentValid.value || !selectedSubject.value || !selectedSection.value || !selectedQuarter.value) {
        alert('Please ensure all fields are filled correctly')
        return
      }
      
      isCreatingAssessment.value = true
      
      try {
        // Prepare the data for the API call
        const assessmentData = {
          subject_id: selectedSubject.value,
          section_id: selectedSection.value,
          quarter: selectedQuarter.value,
          ...newAssessment.value
        }
        
        console.log('Creating assessment:', assessmentData)
        
        // Simulate API call - in a real implementation, this would be an actual API call
        // In the future, this would be: const response = await axios.post('/api/teacher/assessments', assessmentData)
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        // Add to local list (in a real app, this would come from the server response)
        const newId = assessments.value.length > 0 
          ? Math.max(...assessments.value.map(a => a.id)) + 1 
          : 1
          
        assessments.value.unshift({
          id: newId,
          ...newAssessment.value
        })
        
        // Reset form
        resetNewAssessment()
        
        // Close form
        showCreateAssessmentForm.value = false
        
        // Show success message
        alert('Assessment created successfully!')
      } catch (error) {
        console.error('Error creating assessment:', error)
        // Handle error appropriately
        alert('Failed to create assessment. Please try again.')
      } finally {
        isCreatingAssessment.value = false
      }
    }
    
    const resetNewAssessment = () => {
      newAssessment.value = {
        type: '',
        title: '',
        date: new Date().toISOString().split('T')[0],
        totalPoints: 100,
        description: ''
      }
    }
    
    const getAssessmentTypeName = (typeId) => {
      const component = gradeComponents.value.find(c => c.id === typeId)
      return component ? component.name : 'Unknown'
    }
    
    const getAssessmentTypeBadgeClass = (typeId) => {
      switch(typeId) {
        case 'written_works':
          return 'bg-info'
        case 'performance_task':
          return 'bg-warning'
        case 'quarterly_assessment':
          return 'bg-danger'
        default:
          return 'bg-secondary'
      }
    }
    
    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric' }
      return new Date(dateString).toLocaleDateString(undefined, options)
    }

    onMounted(() => {
      // The teacher's assigned subjects and sections are already provided as props
      console.log('Teacher subjects:', props.teacherSubjects)
      console.log('Available sections:', props.sections)
    })

    return {
      selectedSubject,
      selectedSection,
      selectedQuarter,
      isLoading,
      isSaving,
      gradeComponents,
      assignedSubjects,
      filteredSections,
      loadGradeComponents,
      handleComponentsUpdate,
      saveGradeComponents,
      showCreateAssessmentForm,
      newAssessment,
      isCreatingAssessment,
      isNewAssessmentValid,
      createAssessment,
      resetNewAssessment,
      assessments,
      isLoadingAssessments,
      getAssessmentTypeName,
      getAssessmentTypeBadgeClass,
      formatDate
    }
  }
}
</script>

<style scoped>
.card {
  border-radius: 0.75rem;
  transition: all 0.2s ease;
}

.card-header {
  border-top-left-radius: 0.75rem !important;
  border-top-right-radius: 0.75rem !important;
}

.form-select:focus, .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}
</style>