/**
 * LuxeStay Admin Panel — frontend mock interactions
 */
(function () {
  'use strict';

  const config = window.ADMIN_CONFIG || { baseUrl: '', assetPath: '' };

  function showToast(message, type) {
    type = type || 'success';
    let container = document.querySelector('.admin-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'admin-toast-container';
      document.body.appendChild(container);
    }
    const el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0 show';
    el.setAttribute('role', 'alert');
    el.innerHTML =
      '<div class="d-flex"><div class="toast-body">' +
      message +
      '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    container.appendChild(el);
    setTimeout(function () {
      el.remove();
    }, 4000);
  }

  function initSidebar() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    if (!toggle || !sidebar) return;

    let backdrop = document.querySelector('.admin-sidebar-backdrop');
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.className = 'admin-sidebar-backdrop';
      document.body.appendChild(backdrop);
    }

    function close() {
      sidebar.classList.remove('show');
      backdrop.classList.remove('show');
    }

    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('show');
      backdrop.classList.toggle('show');
    });
    backdrop.addEventListener('click', close);
  }

  function initAdminLogin() {
    const form = document.getElementById('adminLoginForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      const email = form.email.value.trim();
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;

      setTimeout(function () {
        btn.disabled = false;
        if (email === 'fail@admin.com') {
          showToast('Invalid admin credentials', 'error');
          return;
        }
        sessionStorage.setItem('adminLoggedIn', '1');
        window.location.href = config.baseUrl + '/index.php';
      }, 700);
    });
  }

  function initCrudForms() {
    document.querySelectorAll('[data-admin-form]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!form.checkValidity()) {
          form.classList.add('was-validated');
          return;
        }
        const modal = form.closest('.modal');
        if (modal) {
          const bsModal = bootstrap.Modal.getInstance(modal);
          if (bsModal) bsModal.hide();
        }
        showToast(form.getAttribute('data-success-msg') || 'Saved successfully');
        form.reset();
        form.classList.remove('was-validated');
      });
    });

    document.querySelectorAll('[data-admin-delete]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (confirm('Are you sure you want to delete this item?')) {
          const row = btn.closest('tr');
          if (row) row.remove();
          showToast('Item deleted (demo)');
        }
      });
    });
  }

  function initDashboardCharts() {
    const revenueEl = document.getElementById('revenueChart');
    const bookingEl = document.getElementById('bookingsChart');
    if (typeof Chart === 'undefined') return;

    if (revenueEl) {
      new Chart(revenueEl, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Revenue ($)',
            data: [42000, 48500, 51200, 47800, 55400, 62100],
            borderColor: '#c9a227',
            backgroundColor: 'rgba(201,162,39,0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: false, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
          }
        }
      });
    }

    if (bookingEl) {
      new Chart(bookingEl, {
        type: 'bar',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [{
            label: 'Bookings',
            data: [12, 19, 14, 22, 28, 35, 24],
            backgroundColor: '#1a2744',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
          }
        }
      });
    }
  }

  function initReportExport() {
    document.querySelectorAll('[data-export-report]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        showToast('Report generated & downloaded (demo PDF)');
      });
    });
  }

  function guardAdminPages() {
    const body = document.body;
    if (!body.classList.contains('page-admin-auth') && body.classList.contains('admin-body')) {
      if (!sessionStorage.getItem('adminLoggedIn') && !body.dataset.skipAuth) {
        /* Demo: allow direct access; uncomment to enforce login */
        /* window.location.href = config.baseUrl + '/login.php'; */
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initAdminLogin();
    initCrudForms();
    initDashboardCharts();
    initReportExport();
    guardAdminPages();
  });

  window.AdminUI = { showToast: showToast };
})();
