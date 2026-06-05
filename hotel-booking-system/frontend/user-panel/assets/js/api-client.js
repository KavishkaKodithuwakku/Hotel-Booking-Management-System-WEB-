/**
 * LuxeStay API Client — connects frontend to PHP backend
 */
(function () {
  'use strict';

  const TOKEN_KEY = 'luxe_token';
  const ADMIN_TOKEN_KEY = 'luxe_admin_token';

  function getConfig() {
    return window.LUXE_CONFIG || { apiBaseUrl: '' };
  }

  function normalizeUrl(path) {
    const base = (getConfig().apiBaseUrl || '').replace(/\/$/, '');
    const p = path.startsWith('/') ? path : '/' + path;
    return base + p;
  }

  function getToken(admin) {
    return localStorage.getItem(admin ? ADMIN_TOKEN_KEY : TOKEN_KEY);
  }

  function setToken(token, admin) {
    localStorage.setItem(admin ? ADMIN_TOKEN_KEY : TOKEN_KEY, token);
  }

  function clearToken(admin) {
    localStorage.removeItem(admin ? ADMIN_TOKEN_KEY : TOKEN_KEY);
  }

  async function request(path, options, admin) {
    options = options || {};
    const headers = Object.assign({ Accept: 'application/json' }, options.headers || {});

    const token = getToken(admin);
    if (token) {
      headers.Authorization = 'Bearer ' + token;
    }

    if (!(options.body instanceof FormData)) {
      headers['Content-Type'] = 'application/json';
    }

    const fetchOpts = {
      method: options.method || 'GET',
      headers: headers,
    };

    if (options.body) {
      fetchOpts.body =
        options.body instanceof FormData ? options.body : JSON.stringify(options.body);
    }

    const res = await fetch(normalizeUrl(path), fetchOpts);
    let data;
    try {
      data = await res.json();
    } catch (e) {
      throw new Error('Invalid server response');
    }

    if (!res.ok || data.success === false) {
      const err = new Error(data.message || 'Request failed');
      err.status = res.status;
      err.errors = data.errors;
      throw err;
    }

    return data;
  }

  window.LuxeApi = {
    getToken: getToken,
    setToken: setToken,
    clearToken: clearToken,
    get: function (path, admin) {
      return request(path, { method: 'GET' }, admin);
    },
    post: function (path, body, admin) {
      return request(path, { method: 'POST', body: body }, admin);
    },
    put: function (path, body, admin) {
      return request(path, { method: 'PUT', body: body }, admin);
    },
    delete: function (path, admin) {
      return request(path, { method: 'DELETE' }, admin);
    },
    upload: function (path, formData, admin) {
      return request(path, { method: 'POST', body: formData }, admin);
    },
  };
})();
