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

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;

      try {
        if (window.LuxeAdminApi) {
          await window.LuxeAdminApi.login(form);
          showToast('Login successful');
          window.location.href = config.baseUrl + '/index.php';
        } else {
          showToast('API client not loaded', 'error');
        }
      } catch (err) {
        showToast(err.message || 'Invalid admin credentials', 'error');
      } finally {
        btn.disabled = false;
      }
    });
  }

  function initCrudForms() {
    document.querySelectorAll('[data-admin-form]').forEach(function (form) {
      form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!form.checkValidity()) {
          form.classList.add('was-validated');
          return;
        }
        const endpoint = form.getAttribute('data-api-endpoint');
        const method = form.getAttribute('data-api-method') || 'POST';
        try {
          if (endpoint && window.LuxeAdminApi) {
            await window.LuxeAdminApi.submitForm(form, endpoint, method);
          }
          const modal = form.closest('.modal');
          if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
          }
          showToast(form.getAttribute('data-success-msg') || 'Saved successfully');
          form.reset();
          form.classList.remove('was-validated');
          if (form.dataset.reload) location.reload();
        } catch (err) {
          showToast(err.message || 'Save failed', 'error');
        }
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

  function initDashboardData() {
    if (!document.querySelector('[data-stat-revenue]') || !window.LuxeAdminApi) return;
    window.LuxeAdminApi.loadDashboard()
      .then(function (data) {
        const s = data.stats || {};

        // Extra live badges
        const pendingBadge = document.getElementById('dashPendingBookings');
        if (pendingBadge) pendingBadge.textContent = s.pendingBookings || 0;
        const pendingRevBadge = document.getElementById('dashPendingPayments');
        if (pendingRevBadge) pendingRevBadge.textContent = s.pendingPayments || 0;
        const reviewBadge = document.getElementById('dashPendingReviews');
        if (reviewBadge) reviewBadge.textContent = s.pendingReviews || 0;
        const msgBadge = document.getElementById('dashNewMessages');
        if (msgBadge) msgBadge.textContent = s.newMessages || 0;
        const newUsersBadge = document.getElementById('dashNewUsers');
        if (newUsersBadge) newUsersBadge.textContent = s.newUsersToday || 0;
        const occupancyEl = document.getElementById('dashOccupancyRate');
        if (occupancyEl) occupancyEl.textContent = (s.occupancyRate || 0) + '%';

        // Recent bookings table
        const recentEl = document.getElementById('recentBookingsBody');
        if (recentEl && data.recentBookings) {
          recentEl.innerHTML = data.recentBookings.map(function (b) {
            var sc = b.status === 'confirmed' ? 'badge-confirmed'
                   : b.status === 'pending'   ? 'badge-pending'
                   : b.status === 'cancelled' ? 'badge-cancelled' : 'badge-completed';
            return '<tr>' +
              '<td>' + b.booking_ref + '</td>' +
              '<td>' + b.guest + '</td>' +
              '<td>' + b.hotel + '</td>' +
              '<td>' + b.check_in + '</td>' +
              '<td>$' + Number(b.total_amount).toLocaleString() + '</td>' +
              '<td><span class="badge-status ' + sc + '">' + b.status + '</span></td>' +
            '</tr>';
          }).join('');
        }

        if (typeof Chart === 'undefined') return;

        // Revenue chart from live data
        const revenueEl = document.getElementById('revenueChart');
        if (revenueEl && data.revenueChart && data.revenueChart.length) {
          // Destroy existing
          var existing = Chart.getChart(revenueEl);
          if (existing) existing.destroy();
          new Chart(revenueEl, {
            type: 'line',
            data: {
              labels: data.revenueChart.map(function (r) { return r.month; }),
              datasets: [{
                label: 'Revenue ($)',
                data: data.revenueChart.map(function (r) { return parseFloat(r.revenue); }),
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
              scales: { y: { beginAtZero: false }, x: { grid: { display: false } } }
            }
          });
        }

        // Bookings chart
        const bookingEl = document.getElementById('bookingsChart');
        if (bookingEl && data.bookingsChart && data.bookingsChart.length) {
          var existingB = Chart.getChart(bookingEl);
          if (existingB) existingB.destroy();
          new Chart(bookingEl, {
            type: 'bar',
            data: {
              labels: data.bookingsChart.map(function (b) { return b.day; }),
              datasets: [{
                label: 'Bookings',
                data: data.bookingsChart.map(function (b) { return parseInt(b.count); }),
                backgroundColor: '#1a2744',
                borderRadius: 6
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
            }
          });
        }
      })
      .catch(function () { /* dashboard uses static fallback */ });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initAdminLogin();
    initCrudForms();
    initDashboardCharts();
    initDashboardData();
    initReportExport();
    guardAdminPages();
  });

  window.AdminUI = { showToast: showToast };
})();
