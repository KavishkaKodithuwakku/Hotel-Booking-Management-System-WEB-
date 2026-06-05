/**
 * LuxeStay Admin — Backend API integration
 */
(function () {
  'use strict';

  const config = window.ADMIN_CONFIG || { baseUrl: '', apiBaseUrl: '' };

  function api() {
    return window.LuxeApi;
  }

  async function adminLogin(form) {
    const data = await api().post(
      '/auth/admin/login',
      { email: form.email.value.trim(), password: form.password.value },
      true
    );
    api().setToken(data.token, true);
    sessionStorage.setItem('adminLoggedIn', '1');
    sessionStorage.setItem('adminUser', JSON.stringify(data.user));
    return data;
  }

  async function loadDashboard() {
    const data = await api().get('/admin/dashboard', true);
    const s = data.stats || {};

    document.querySelectorAll('[data-stat-revenue]').forEach((el) => {
      el.textContent = '$' + Number(s.totalRevenue || 0).toLocaleString();
    });
    document.querySelectorAll('[data-stat-bookings]').forEach((el) => {
      el.textContent = Number(s.totalBookings || 0).toLocaleString();
    });
    document.querySelectorAll('[data-stat-hotels]').forEach((el) => {
      el.textContent = s.activeHotels || 0;
    });
    document.querySelectorAll('[data-stat-users]').forEach((el) => {
      el.textContent = Number(s.totalUsers || 0).toLocaleString();
    });
    document.querySelectorAll('[data-stat-available]').forEach((el) => {
      el.textContent = s.availableRooms || 0;
    });
    document.querySelectorAll('[data-stat-occupied]').forEach((el) => {
      el.textContent = s.occupiedRooms || 0;
    });

    return data;
  }

  async function submitAdminForm(form, endpoint, method) {
    const fd = new FormData(form);
    const body = {};
    fd.forEach((v, k) => {
      if (body[k]) {
        if (!Array.isArray(body[k])) body[k] = [body[k]];
        body[k].push(v);
      } else body[k] = v;
    });

    if (method === 'POST') return api().post(endpoint, body, true);
    if (method === 'PUT') return api().put(endpoint, body, true);
    return api().post(endpoint, body, true);
  }

  window.LuxeAdminApi = {
    login: adminLogin,
    loadDashboard: loadDashboard,
    submitForm: submitAdminForm,
    get: function (path) {
      return api().get(path, true);
    },
    post: function (path, body) {
      return api().post(path, body, true);
    },
    put: function (path, body) {
      return api().put(path, body, true);
    },
    delete: function (path) {
      return api().delete(path, true);
    },
  };
})();
