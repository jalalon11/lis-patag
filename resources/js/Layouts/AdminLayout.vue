<template>
  <div class="admin-layout">
    <!-- Sidebar Overlay -->
    <div
      v-if="sidebarOpen"
      class="sidebar-overlay"
      @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <nav class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
      <!-- Sidebar Header -->
      <div class="sidebar-header">
        <div class="brand-container">
          <div class="brand-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="brand-text">
            <h6 class="brand-title">Patag Elementary</h6>
            <span class="brand-subtitle">Learning Information System</span>
          </div>
        </div>
        <button class="sidebar-close-btn d-lg-none" @click="closeSidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <!-- User Profile Section -->
      <div class="user-profile">
        <div class="user-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
          <div class="user-name">{{ $page.props.auth.user.name }}</div>
          <div class="user-role">Administrator</div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <div class="sidebar-menu">
        <ul class="nav-list">
          <!-- Dashboard -->
          <li class="nav-item">
            <Link
              href="/admin/dashboard"
              class="nav-link"
              :class="{ 'active': $page.url === '/admin/dashboard' }"
              @click="closeSidebarOnMobile"
            >
              <div class="nav-icon">
                <i class="fas fa-tachometer-alt"></i>
              </div>
              <span class="nav-text">Dashboard</span>
            </Link>
          </li>

          <!-- Account Management -->
          <li class="nav-item has-submenu">
            <a
              class="nav-link submenu-toggle"
              @click="toggleSubmenu('accounts')"
              :class="{ 'active': accountsMenuOpen }"
            >
              <div class="nav-icon">
                <i class="fas fa-users"></i>
              </div>
              <span class="nav-text">Account Management</span>
              <div class="nav-arrow">
                <i class="fas fa-chevron-down" :class="{ 'rotated': accountsMenuOpen }"></i>
              </div>
            </a>
            <ul class="submenu" :class="{ 'open': accountsMenuOpen }">
              <li>
                <Link
                  href="/admin/teachers"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/teachers') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-chalkboard-teacher"></i>
                  <span>Teachers</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/parents"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/parents') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-user-friends"></i>
                  <span>Parents/Guardians</span>
                </Link>
              </li>
            </ul>
          </li>

          <!-- Curricula Management -->
          <li class="nav-item has-submenu">
            <a
              class="nav-link submenu-toggle"
              @click="toggleSubmenu('curricula')"
              :class="{ 'active': curriculaMenuOpen }"
            >
              <div class="nav-icon">
                <i class="fas fa-book"></i>
              </div>
              <span class="nav-text">Curricula Management</span>
              <div class="nav-arrow">
                <i class="fas fa-chevron-down" :class="{ 'rotated': curriculaMenuOpen }"></i>
              </div>
            </a>
            <ul class="submenu" :class="{ 'open': curriculaMenuOpen }">
              <li>
                <Link
                  href="/admin/subjects"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/subjects') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-book-open"></i>
                  <span>Subjects</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/sections"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/sections') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-layer-group"></i>
                  <span>Sections</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/school-years"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/school-years') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-calendar-alt"></i>
                  <span>School Years</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/schedules"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/schedules') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-clock"></i>
                  <span>Schedules</span>
                </Link>
              </li>
            </ul>
          </li>

          <!-- Student Admission -->
          <li class="nav-item">
            <Link
              href="/admin/admission"
              class="nav-link"
              :class="{ 'active': $page.url === '/admin/admission' }"
              @click="closeSidebarOnMobile"
            >
              <div class="nav-icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <span class="nav-text">Student Admission</span>
            </Link>
          </li>

          <!-- Reports -->
          <li class="nav-item has-submenu">
            <a
              class="nav-link submenu-toggle"
              @click="toggleSubmenu('reports')"
              :class="{ 'active': reportsMenuOpen }"
            >
              <div class="nav-icon">
                <i class="fas fa-chart-bar"></i>
              </div>
              <span class="nav-text">Reports</span>
              <div class="nav-arrow">
                <i class="fas fa-chevron-down" :class="{ 'rotated': reportsMenuOpen }"></i>
              </div>
            </a>
            <ul class="submenu" :class="{ 'open': reportsMenuOpen }">
              <li>
                <Link
                  href="/admin/reports/sf1"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/reports/sf1') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-list"></i>
                  <span>SF1 Master List</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/reports/learners"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/reports/learners') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-graduation-cap"></i>
                  <span>Learners by Grade</span>
                </Link>
              </li>
              <li>
                <Link
                  href="/admin/reports/teachers"
                  class="submenu-link"
                  :class="{ 'active': $page.url.startsWith('/admin/reports/teachers') }"
                  @click="closeSidebarOnMobile"
                >
                  <i class="fas fa-chalkboard-teacher"></i>
                  <span>Teachers List</span>
                </Link>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
        <Link
          href="/logout"
          method="post"
          class="logout-btn"
          @click="closeSidebarOnMobile"
        >
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </Link>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Navigation -->
      <header class="top-navbar">
        <div class="navbar-container">
          <!-- Left Side -->
          <div class="navbar-left">
            <button class="sidebar-toggle" @click="toggleSidebar">
              <i class="fas fa-bars"></i>
            </button>
            <div class="page-title">
              <h5 class="mb-0">{{ pageTitle || 'Dashboard' }}</h5>
            </div>
          </div>

          <!-- Right Side -->
          <div class="navbar-right">
            <div class="user-dropdown">
              <button
                class="user-dropdown-toggle"
                @click="toggleUserDropdown"
                :class="{ 'active': userDropdownOpen }"
              >
                <div class="user-avatar-small">
                  <i class="fas fa-user"></i>
                </div>
                <span class="user-name-small">{{ $page.props.auth.user.name }}</span>
                <i class="fas fa-chevron-down dropdown-arrow" :class="{ 'rotated': userDropdownOpen }"></i>
              </button>

              <div class="user-dropdown-menu" :class="{ 'open': userDropdownOpen }">
                <Link href="/admin/profile" class="dropdown-item" @click="closeUserDropdown">
                  <i class="fas fa-user"></i>
                  <span>Profile</span>
                </Link>
                <Link href="/admin/settings" class="dropdown-item" @click="closeUserDropdown">
                  <i class="fas fa-cog"></i>
                  <span>Settings</span>
                </Link>
                <div class="dropdown-divider"></div>
                <Link href="/logout" method="post" class="dropdown-item logout" @click="closeUserDropdown">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Logout</span>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="page-content">
        <slot />
      </main>
    </div>

    <!-- Global Loading Spinner -->
    <LoadingSpinner
      v-if="globalLoading"
      :show="globalLoading.show"
      :text="globalLoading.text"
      overlay
    />
  </div>

</template>

<script>
import { Link } from '@inertiajs/vue3'
import { useLoading } from '@/Composables/useLoading'

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
  setup() {
    const { globalLoading } = useLoading()
    return { globalLoading }
  },
  data() {
    return {
      sidebarOpen: false,
      accountsMenuOpen: false,
      curriculaMenuOpen: false,
      reportsMenuOpen: false,
      userDropdownOpen: false
    }
  },

  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen
      localStorage.setItem('sidebarOpen', this.sidebarOpen)
    },
    closeSidebar() {
      this.sidebarOpen = false
      localStorage.setItem('sidebarOpen', false)
    },
    closeSidebarOnMobile() {
      if (window.innerWidth < 992) {
        this.closeSidebar()
      }
    },
    toggleSubmenu(menu) {
      switch (menu) {
        case 'accounts':
          this.accountsMenuOpen = !this.accountsMenuOpen
          localStorage.setItem('accountsMenuOpen', this.accountsMenuOpen)
          break
        case 'curricula':
          this.curriculaMenuOpen = !this.curriculaMenuOpen
          localStorage.setItem('curriculaMenuOpen', this.curriculaMenuOpen)
          break
        case 'reports':
          this.reportsMenuOpen = !this.reportsMenuOpen
          localStorage.setItem('reportsMenuOpen', this.reportsMenuOpen)
          break
      }
    },
    toggleUserDropdown() {
      this.userDropdownOpen = !this.userDropdownOpen
    },
    closeUserDropdown() {
      this.userDropdownOpen = false
    },
    loadMenuStates() {
      // Load sidebar state
      const savedSidebarState = localStorage.getItem('sidebarOpen')
      if (savedSidebarState !== null) {
        this.sidebarOpen = savedSidebarState === 'true'
      }

      // Load submenu states
      const savedAccountsState = localStorage.getItem('accountsMenuOpen')
      if (savedAccountsState !== null) {
        this.accountsMenuOpen = savedAccountsState === 'true'
      }

      const savedCurriculaState = localStorage.getItem('curriculaMenuOpen')
      if (savedCurriculaState !== null) {
        this.curriculaMenuOpen = savedCurriculaState === 'true'
      }

      const savedReportsState = localStorage.getItem('reportsMenuOpen')
      if (savedReportsState !== null) {
        this.reportsMenuOpen = savedReportsState === 'true'
      }

      // Auto-open relevant submenu based on current page
      const currentUrl = this.$page.url
      if (currentUrl.startsWith('/admin/teachers') || currentUrl.startsWith('/admin/parents')) {
        this.accountsMenuOpen = true
      } else if (currentUrl.startsWith('/admin/subjects') || currentUrl.startsWith('/admin/sections') ||
                 currentUrl.startsWith('/admin/school-years') || currentUrl.startsWith('/admin/schedules')) {
        this.curriculaMenuOpen = true
      } else if (currentUrl.startsWith('/admin/reports')) {
        this.reportsMenuOpen = true
      }
    },
    handleClickOutside(event) {
      // Close user dropdown when clicking outside
      if (this.userDropdownOpen && !this.$el.querySelector('.user-dropdown').contains(event.target)) {
        this.closeUserDropdown()
      }
    }
  },
  mounted() {
    // Load saved states
    this.loadMenuStates()

    // Close sidebar on mobile by default
    if (window.innerWidth < 992) {
      this.sidebarOpen = false
    }

    // Handle window resize
    window.addEventListener('resize', () => {
      if (window.innerWidth < 992) {
        this.sidebarOpen = false
      }
    })

    // Handle clicks outside to close dropdowns
    document.addEventListener('click', this.handleClickOutside)
  },
  beforeUnmount() {
    // Clean up event listeners
    document.removeEventListener('click', this.handleClickOutside)
  }
}
</script>

<style scoped>
/* Admin Layout */
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8f9fa;
}

/* Sidebar Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
  backdrop-filter: blur(2px);
}

/* Sidebar */
.sidebar {
  position: fixed !important;
  top: 0;
  left: 0;
  width: 280px;
  height: 100vh;
  background: linear-gradient(180deg, #2c5aa0 0%, #1e3d6f 100%);
  transform: translateX(-100%) !important;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1050;
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
}

.sidebar.sidebar-open {
  transform: translateX(0) !important;
}

/* Sidebar Header */
.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.brand-container {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.brand-icon {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.25rem;
}

.brand-text {
  color: white;
}

.brand-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0;
  line-height: 1.2;
}

.brand-subtitle {
  font-size: 0.75rem;
  opacity: 0.8;
  display: block;
}

.sidebar-close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.25rem;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: background-color 0.2s ease;
}

.sidebar-close-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* User Profile */
.user-profile {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 45px;
  height: 45px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
}

.user-info {
  flex: 1;
  color: white;
}

.user-name {
  font-weight: 600;
  font-size: 0.95rem;
  margin-bottom: 0.125rem;
}

.user-role {
  font-size: 0.8rem;
  opacity: 0.8;
}

/* Sidebar Menu */
.sidebar-menu {
  flex: 1;
  padding: 1rem 0;
  overflow-y: auto;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  margin-bottom: 0.25rem;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  transition: all 0.2s ease;
  border-radius: 0;
  position: relative;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
  transform: translateX(4px);
}

.nav-link.active {
  background-color: rgba(255, 255, 255, 0.15);
  color: white;
  font-weight: 600;
}

.nav-link.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background-color: #ffd700;
}

.nav-icon {
  width: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
  font-size: 1rem;
}

.nav-text {
  flex: 1;
  font-size: 0.9rem;
}

.nav-arrow {
  margin-left: auto;
  transition: transform 0.2s ease;
}

.nav-arrow .fa-chevron-down.rotated {
  transform: rotate(180deg);
}

/* Submenu */
.submenu {
  list-style: none;
  padding: 0;
  margin: 0;
  background-color: rgba(0, 0, 0, 0.1);
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.submenu.open {
  max-height: 300px;
}

.submenu-link {
  display: flex;
  align-items: center;
  padding: 0.6rem 1.5rem 0.6rem 3rem;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  font-size: 0.85rem;
  transition: all 0.2s ease;
}

.submenu-link:hover {
  background-color: rgba(255, 255, 255, 0.08);
  color: white;
  transform: translateX(4px);
}

.submenu-link.active {
  background-color: rgba(255, 255, 255, 0.12);
  color: white;
  font-weight: 500;
}

.submenu-link i {
  margin-right: 0.5rem;
  width: 16px;
  text-align: center;
}

/* Sidebar Footer */
.sidebar-footer {
  padding: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-btn {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.75rem;
  background: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.3);
  border-radius: 0.5rem;
  color: #ff6b7a;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.logout-btn:hover {
  background: rgba(220, 53, 69, 0.2);
  color: #ff5566;
  transform: translateY(-1px);
}

.logout-btn i {
  margin-right: 0.5rem;
}

/* Main Content */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  margin-left: 0 !important; /* Ensure no margin pushes content */
  width: 100% !important;
}

/* Top Navbar */
.top-navbar {
  background: white;
  border-bottom: 1px solid #e9ecef;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
  z-index: 1030;
  position: sticky;
  top: 0;
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.navbar-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.sidebar-toggle {
  background: none;
  border: none;
  color: #2c5aa0;
  font-size: 1.25rem;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
  cursor: pointer;
}

.sidebar-toggle:hover {
  background-color: rgba(44, 90, 160, 0.1);
  transform: scale(1.05);
}

.page-title h5 {
  color: #2c5aa0;
  font-weight: 600;
}

/* User Dropdown */
.user-dropdown {
  position: relative;
}

.user-dropdown-toggle {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: none;
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  cursor: pointer;
}

.user-dropdown-toggle:hover,
.user-dropdown-toggle.active {
  background-color: #f8f9fa;
}

.user-avatar-small {
  width: 32px;
  height: 32px;
  background: linear-gradient(45deg, #2c5aa0, #1e3d6f);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.875rem;
}

.user-name-small {
  font-weight: 500;
  color: #495057;
}

.dropdown-arrow {
  font-size: 0.75rem;
  color: #6c757d;
  transition: transform 0.2s ease;
}

.dropdown-arrow.rotated {
  transform: rotate(180deg);
}

.user-dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  min-width: 200px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.2s ease;
  z-index: 1000;
}

.user-dropdown-menu.open {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #495057;
  text-decoration: none;
  font-size: 0.9rem;
  transition: background-color 0.2s ease;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #2c5aa0;
}

.dropdown-item.logout {
  color: #dc3545;
}

.dropdown-item.logout:hover {
  background-color: #fff5f5;
  color: #c82333;
}

.dropdown-item i {
  margin-right: 0.5rem;
  width: 16px;
  text-align: center;
}

.dropdown-divider {
  height: 1px;
  background-color: #e9ecef;
  margin: 0.5rem 0;
}

/* Page Content */
.page-content {
  flex: 1;
  padding: 2rem;
  background-color: #f8f9fa;
}

/* Responsive Design - Overlay behavior on all screen sizes */
@media (min-width: 992px) {
  .main-content {
    margin-left: 0 !important; /* No margin - overlay behavior on all screen sizes */
  }

  .sidebar {
    position: fixed !important; /* Always fixed position */
    transform: translateX(-100%) !important; /* Always hidden by default */
  }

  .sidebar.sidebar-open {
    transform: translateX(0) !important; /* Show when open */
  }
}

@media (max-width: 991.98px) {
  .main-content {
    margin-left: 0 !important;
  }

  .page-content {
    padding: 1.5rem;
  }

  .navbar-container {
    padding: 0.75rem 1rem;
  }
}

@media (max-width: 576px) {
  .page-content {
    padding: 1rem;
  }

  .user-name-small {
    display: none;
  }
}

/* Scrollbar Styling */
.sidebar-menu::-webkit-scrollbar {
  width: 4px;
}

.sidebar-menu::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

.sidebar-menu::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}
</style>