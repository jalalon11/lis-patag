<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar bg-success text-white position-fixed" :class="{ 'show': sidebarOpen }">
      <!-- Sidebar Header -->
      <div class="p-3 border-bottom border-light border-opacity-25">
        <div class="d-flex align-items-center">
          <div>
            <h5 class="mb-0 fw-bold">Patag Elementary</h5>
            <small class="opacity-75">Parent Portal</small>
          </div>
          <button 
            class="btn btn-link text-white ms-auto d-lg-none" 
            @click="closeSidebar"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <!-- Navigation Menu -->
      <div class="flex-grow-1 overflow-auto">
        <ul class="nav nav-pills flex-column p-2">
          <!-- Dashboard -->
          <li class="nav-item">
            <Link
              href="/parent/dashboard"
              class="nav-link text-white"
              :class="{ 'active bg-white bg-opacity-25': $page.url === '/parent/dashboard' }"
              @click="closeSidebarOnMobile"
            >
              <i class="fas fa-tachometer-alt me-2"></i>
              Dashboard
            </Link>
          </li>

          <!-- My Children -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': childrenMenuOpen }"
              @click="toggleSubmenu('children')"
              role="button"
            >
              <span>
                <i class="fas fa-child me-2"></i>
                My Children
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': childrenMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': childrenMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/parent/children/child-1"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/children/child-1') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-user me-2"></i>
                    Child 1
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/children/child-2"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/children/child-2') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-user me-2"></i>
                    Child 2
                  </Link>
                </li>
              </ul>
            </div>
          </li>

          <!-- Academic Report -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': academicMenuOpen }"
              @click="toggleSubmenu('academic')"
              role="button"
            >
              <span>
                <i class="fas fa-graduation-cap me-2"></i>
                Academic Report
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': academicMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': academicMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/parent/academic/grades"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/academic/grades') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-medal me-2"></i>
                    View Grades
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/academic/observable-values"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/academic/observable-values') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-eye me-2"></i>
                    View Observable Values
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/academic/quizzes"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/academic/quizzes') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-question-circle me-2"></i>
                    View Quizzes
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/academic/assignments"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/academic/assignments') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-tasks me-2"></i>
                    View Assignments
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/academic/examinations"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/academic/examinations') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-clipboard-check me-2"></i>
                    View Examinations
                  </Link>
                </li>
              </ul>
            </div>
          </li>

          <!-- Attendance -->
          <li class="nav-item">
            <Link
              href="/parent/attendance"
              class="nav-link text-white"
              :class="{ 'active bg-white bg-opacity-25': $page.url === '/parent/attendance' }"
              @click="closeSidebarOnMobile"
            >
              <i class="fas fa-calendar-check me-2"></i>
              Attendance
            </Link>
          </li>

          <!-- School Communication -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': communicationMenuOpen }"
              @click="toggleSubmenu('communication')"
              role="button"
            >
              <span>
                <i class="fas fa-comments me-2"></i>
                School Communication
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': communicationMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': communicationMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/parent/communication/announcements"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/communication/announcements') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-bullhorn me-2"></i>
                    View Announcements
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/parent/communication/feedback"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/parent/communication/feedback') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-comment-dots me-2"></i>
                    View Feedback
                  </Link>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>

      <!-- Sidebar Footer -->
      <div class="p-3 border-top border-light border-opacity-25">
        <Link
          href="/logout"
          method="post"
          class="btn btn-outline-light w-100"
          @click="closeSidebarOnMobile"
        >
          <i class="fas fa-sign-out-alt me-2"></i>
          Logout
        </Link>
      </div>
    </nav>

    <!-- Backdrop for mobile -->
    <div 
      v-if="sidebarOpen && isMobile" 
      class="sidebar-backdrop position-fixed w-100 h-100 bg-black bg-opacity-50"
      @click="closeSidebar"
      style="z-index: 1040;"
    ></div>

    <!-- Main Content -->
    <div class="flex-grow-1" :class="{ 'sidebar-margin': !isMobile, 'collapsed': !sidebarOpen && !isMobile }">
      <!-- Top Navigation -->
      <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container-fluid">
          <button
            class="btn btn-text-success border-0 me-3"
            @click="toggleSidebar"
          >
            <i class="fas fa-bars"></i>
          </button>
          
          <h5 class="mb-0 text-success fw-bold">{{ pageTitle || 'Dashboard' }}</h5>

          <div class="ms-auto">
            <div class="dropdown">
              <button
                class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center"
                @click="toggleUserDropdown"
                :class="{ 'show': userDropdownOpen }"
              >
                <div class="bg-success text-white rounded-circle p-1 me-2" style="width: 32px; height: 32px;">
                  <i class="fas fa-user small"></i>
                </div>
                <span class="text-dark">{{ $page.props.auth.user.name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" :class="{ 'show': userDropdownOpen }">
                <li>
                  <Link href="/parent/profile" class="dropdown-item" @click="closeUserDropdown">
                    <i class="fas fa-user me-2"></i>
                    Profile
                  </Link>
                </li>
                <li>
                  <Link href="/parent/settings" class="dropdown-item" @click="closeUserDropdown">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                  </Link>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <Link href="/logout" method="post" class="dropdown-item text-danger" @click="closeUserDropdown">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                  </Link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Content -->
      <main class="p-4 bg-light" style="min-height: calc(100vh - 76px);">
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  name: 'ParentLayout',
  components: {
    Link
  },
  props: {
    pageTitle: {
      type: String,
      default: 'Dashboard'
    }
  },
  data() {
    return {
      sidebarOpen: true, // Start with sidebar open on desktop
      childrenMenuOpen: false,
      academicMenuOpen: false,
      communicationMenuOpen: false,
      userDropdownOpen: false,
      isMobile: false
    }
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen
    },
    
    closeSidebar() {
      this.sidebarOpen = false
    },
    
    closeSidebarOnMobile() {
      if (this.isMobile) {
        this.closeSidebar()
      }
    },
    
    toggleSubmenu(menu) {
      switch (menu) {
        case 'children':
          this.childrenMenuOpen = !this.childrenMenuOpen
          break
        case 'academic':
          this.academicMenuOpen = !this.academicMenuOpen
          break
        case 'communication':
          this.communicationMenuOpen = !this.communicationMenuOpen
          break
      }
    },
    
    toggleUserDropdown() {
      this.userDropdownOpen = !this.userDropdownOpen
    },
    
    closeUserDropdown() {
      this.userDropdownOpen = false
    },
    
    checkScreenSize() {
      const wasMobile = this.isMobile
      this.isMobile = window.innerWidth < 992
      
      // Only change sidebar state if we're switching between mobile/desktop
      if (wasMobile !== this.isMobile) {
        if (this.isMobile) {
          this.sidebarOpen = false // Hide on mobile
        } else {
          this.sidebarOpen = true // Show on desktop
        }
      }
    },
    
    handleClickOutside(event) {
      // Close user dropdown when clicking outside
      if (this.userDropdownOpen && !event.target.closest('.dropdown')) {
        this.closeUserDropdown()
      }
    },
    
    autoOpenMenus() {
      const currentUrl = this.$page.url
      if (currentUrl.startsWith('/parent/children')) {
        this.childrenMenuOpen = true
      } else if (currentUrl.startsWith('/parent/academic')) {
        this.academicMenuOpen = true
      } else if (currentUrl.startsWith('/parent/communication')) {
        this.communicationMenuOpen = true
      }
    }
  },
  
  mounted() {
    this.checkScreenSize()
    this.autoOpenMenus()
    window.addEventListener('resize', this.checkScreenSize)
    document.addEventListener('click', this.handleClickOutside)
  },
  
  beforeUnmount() {
    window.removeEventListener('resize', this.checkScreenSize)
    document.removeEventListener('click', this.handleClickOutside)
  }
}
</script>

<style scoped>
.sidebar {
  width: 280px;
  height: 100vh;
  top: 0;
  left: 0;
  z-index: 1050;
  transition: transform 0.3s ease;
}

/* Mobile: sidebar is fixed and hidden by default */
@media (max-width: 991.98px) {
  .sidebar {
    position: fixed;
    transform: translateX(-100%);
  }
  
  .sidebar.show {
    transform: translateX(0);
  }
  
  .sidebar-margin {
    margin-left: 0 !important;
  }
}

/* Desktop: sidebar is relative and visible by default */
@media (min-width: 992px) {
  .sidebar {
    position: relative;
    transform: translateX(0);
  }
  
  .sidebar:not(.show) {
    transform: translateX(-100%);
  }
  
  .sidebar-margin {
    margin-left: 280px;
    transition: margin-left 0.3s ease;
  }
  
  .sidebar-margin.collapsed {
    margin-left: 0;
  }
}

/* Sidebar Styling - Using Bootstrap Success Green */

/* Top-level nav items */
.sidebar .nav-link {
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 2px;
  transition: all 0.2s ease;
  color: rgba(255, 255, 255, 0.9) !important;
}

/* Hover effect for top-level items */
.sidebar .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1) !important;
  color: #ffffff !important;
}

/* Active state for top-level items */
.sidebar .nav-link.active {
  background-color: rgba(255, 255, 255, 0.25) !important;
  color: #ffffff !important;
  font-weight: 600;
}

/* Submenu container styling */
.sidebar .collapse {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  margin-top: 4px;
  margin-bottom: 8px;
  padding: 8px 0;
}

/* Submenu items */
.sidebar .collapse .nav-link {
  padding: 0.5rem 1.5rem;
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.75) !important;
  margin-bottom: 1px;
  border-radius: 6px;
  margin-left: 8px;
  margin-right: 8px;
}

/* Submenu hover effect */
.sidebar .collapse .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15) !important;
  color: #ffffff !important;
}

/* Active submenu item */
.sidebar .collapse .nav-link.active {
  background-color: rgba(255, 255, 255, 0.3) !important;
  color: #ffffff !important;
  font-weight: 600;
}

/* Arrow icon rotation */
.rotate-180 {
  transform: rotate(180deg);
  transition: transform 0.2s ease;
}

/* Spacing between nav items */
.sidebar .nav-item + .nav-item {
  margin-top: 4px;
}

/* Header and footer borders */
.sidebar .border-bottom,
.sidebar .border-top {
  border-color: rgba(255, 255, 255, 0.2) !important;
}

/* Logout button */
.sidebar .btn-outline-light {
  border-color: rgba(255, 255, 255, 0.3);
  color: rgba(255, 255, 255, 0.9);
}

.sidebar .btn-outline-light:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.5);
  color: #ffffff;
}
</style>