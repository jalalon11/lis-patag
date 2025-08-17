<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar bg-primary text-white position-fixed" :class="{ 'show': sidebarOpen }">
      <!-- Sidebar Header -->
      <div class="p-3 border-bottom border-light border-opacity-25">
        <div class="d-flex align-items-center">
          <div>
            <h5 class="mb-0 fw-bold">Patag Elementary</h5>
            <small class="opacity-75">Learning Information System</small>
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
              href="/admin/dashboard"
              class="nav-link text-white"
              :class="{ 'active bg-white bg-opacity-25': $page.url === '/admin/dashboard' }"
              @click="closeSidebarOnMobile"
            >
              <i class="fas fa-tachometer-alt me-2"></i>
              Dashboard
            </Link>
          </li>

          <!-- Account Management -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': accountsMenuOpen }"
              @click="toggleSubmenu('accounts')"
              role="button"
            >
              <span>
                <i class="fas fa-users me-2"></i>
                Account Management
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': accountsMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': accountsMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/admin/teachers"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/teachers') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/parents"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/parents') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-user-friends me-2"></i>
                    Parents/Guardians
                  </Link>
                </li>
              </ul>
            </div>
          </li>

          <!-- Curricula Management -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': curriculaMenuOpen }"
              @click="toggleSubmenu('curricula')"
              role="button"
            >
              <span>
                <i class="fas fa-book me-2"></i>
                Curricula Management
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': curriculaMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': curriculaMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/admin/school-years"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/school-years') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-calendar-alt me-2"></i>
                    School Years
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/subjects"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/subjects') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-book-open me-2"></i>
                    Subjects
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/sections"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white ': $page.url.startsWith('/admin/sections') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-layer-group me-2"></i>
                    Sections
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/schedules"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/schedules') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-clock me-2"></i>
                    Schedules
                  </Link>
                </li>
              </ul>
            </div>
          </li>

          <!-- Student Admission -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': admissionMenuOpen }"
              @click="toggleSubmenu('admission')"
              role="button"
            >
              <span>
                <i class="fas fa-user-plus me-2"></i>
                Student Management
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': admissionMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': admissionMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/admin/admission"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/admission') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-user-plus me-2"></i>
                    Student Admission
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/enrollment"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/enrollment') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-user-graduate me-2"></i>
                    Enrolled Students
                  </Link>
                </li>
              </ul>
            </div>
          </li>

          <!-- Reports -->
          <li class="nav-item">
            <a
              class="nav-link text-white d-flex justify-content-between align-items-center"
              :class="{ 'active bg-white bg-opacity-25': reportsMenuOpen }"
              @click="toggleSubmenu('reports')"
              role="button"
            >
              <span>
                <i class="fas fa-chart-bar me-2"></i>
                Reports
              </span>
              <i class="fas fa-chevron-down" :class="{ 'rotate-180': reportsMenuOpen }"></i>
            </a>
            <div class="collapse" :class="{ 'show': reportsMenuOpen }">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <Link
                    href="/admin/reports/sf1"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/reports/sf1') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-list me-2"></i>
                    SF1 Master List
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/reports/learners"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/reports/learners') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-graduation-cap me-2"></i>
                    Learners by Grade
                  </Link>
                </li>
                <li class="nav-item">
                  <Link
                    href="/admin/reports/teachers"
                    class="nav-link text-white-50"
                    :class="{ 'active text-white': $page.url.startsWith('/admin/reports/teachers') }"
                    @click="closeSidebarOnMobile"
                  >
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers List
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
            class="btn btn-text-primary border-0 me-3"
            @click="toggleSidebar"
          >
            <i class="fas fa-bars"></i>
          </button>
          
          <h5 class="mb-0 text-primary fw-bold">{{ pageTitle || 'Dashboard' }}</h5>

          <div class="ms-auto">
            <div class="dropdown">
              <button
                class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center"
                @click="toggleUserDropdown"
                :class="{ 'show': userDropdownOpen }"
              >
                <div class="bg-primary text-white rounded-circle p-1 me-2" style="width: 32px; height: 32px;">
                  <i class="fas fa-user small"></i>
                </div>
                <span class="text-dark">{{ $page.props.auth.user.name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" :class="{ 'show': userDropdownOpen }">
                <li>
                  <Link href="/admin/profile" class="dropdown-item" @click="closeUserDropdown">
                    <i class="fas fa-user me-2"></i>
                    Profile
                  </Link>
                </li>
                <li>
                  <Link href="/admin/settings" class="dropdown-item" @click="closeUserDropdown">
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
  name: 'AdminLayout',
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
      accountsMenuOpen: false,
      curriculaMenuOpen: false,
      reportsMenuOpen: false,
      admissionMenuOpen: false,
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
        case 'accounts':
          this.accountsMenuOpen = !this.accountsMenuOpen
          break
        case 'curricula':
          this.curriculaMenuOpen = !this.curriculaMenuOpen
          break
        case 'reports':
          this.reportsMenuOpen = !this.reportsMenuOpen
          break
        case 'admission':
          this.admissionMenuOpen = !this.admissionMenuOpen
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
      if (currentUrl.startsWith('/admin/teachers') || currentUrl.startsWith('/admin/parents')) {
        this.accountsMenuOpen = true
      } else if (currentUrl.startsWith('/admin/subjects') || currentUrl.startsWith('/admin/sections') ||
                 currentUrl.startsWith('/admin/school-years') || currentUrl.startsWith('/admin/schedules')) {
        this.curriculaMenuOpen = true
      } else if (currentUrl.startsWith('/admin/reports')) {
        this.reportsMenuOpen = true
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

/* Sidebar Styling - Using Bootstrap Primary Blue */


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