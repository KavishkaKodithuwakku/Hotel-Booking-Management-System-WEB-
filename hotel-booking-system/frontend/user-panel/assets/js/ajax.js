/**
 * LuxeStay — AJAX Frontend Examples
 * Uses dummy JSON files — NO backend/database connection
 */

(function () {
  'use strict';

  const config = window.LUXE_CONFIG || { baseUrl: '', assetPath: '' };
  const dataPath = config.assetPath + '/data';

  /**
   * Simulated API delay for realistic UX
   */
  function simulateDelay(ms = 600) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }

  /**
   * Fetch dummy JSON (frontend-only mock API)
   */
  async function fetchMock(endpoint, options = {}) {
    const { method = 'GET', body = null } = options;
    await simulateDelay(method === 'GET' ? 400 : 800);

    const files = {
      hotels: dataPath + '/hotels.json',
      login: dataPath + '/login-response.json',
      register: dataPath + '/register-response.json',
      booking: dataPath + '/booking-response.json',
      availability: dataPath + '/availability-response.json',
    };

    const url = files[endpoint];
    if (!url) throw new Error('Unknown mock endpoint: ' + endpoint);

    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to load mock data');
    let data = await response.json();

    if (endpoint === 'login' && body) {
      if (body.email === 'fail@demo.com') {
        return { success: false, message: 'Invalid email or password' };
      }
      data.user.email = body.email || data.user.email;
    }

    if (endpoint === 'register' && body) {
      data.user.firstName = body.firstName || data.user.firstName;
      data.user.email = body.email || data.user.email;
    }

    if (endpoint === 'booking' && body) {
      data.booking.id = 'BK-2026-' + Math.floor(1000 + Math.random() * 9000);
      if (body.checkIn) data.booking.checkIn = body.checkIn;
      if (body.checkOut) data.booking.checkOut = body.checkOut;
    }

    return data;
  }

  /**
   * Build hotel card HTML from JSON item
   */
  function renderHotelCard(hotel) {
    const detailUrl = config.baseUrl + '/hotel-details.php?id=' + hotel.id;
    return `
      <div class="col-md-6 col-xl-4">
        <article class="hotel-card lux-card" data-hotel-id="${hotel.id}" data-price="${hotel.price}" data-stars="${hotel.stars}">
          <div class="hotel-card-image">
            <img src="${hotel.image}" alt="${hotel.name}" loading="lazy">
            <div class="hotel-card-overlay"></div>
            <span class="hotel-badge">${hotel.stars} <i class="fas fa-star"></i></span>
            <button type="button" class="btn-favorite" aria-label="Favorite"><i class="far fa-heart"></i></button>
          </div>
          <div class="hotel-card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h5 class="hotel-card-title">${hotel.name}</h5>
                <p class="hotel-card-location"><i class="fas fa-map-marker-alt text-gold me-1"></i>${hotel.location}</p>
              </div>
              <div class="hotel-rating">
                <span class="rating-value">${hotel.rating.toFixed(1)}</span>
                <small class="text-muted d-block">${hotel.reviews} reviews</small>
              </div>
            </div>
            <div class="hotel-card-footer">
              <div class="hotel-price">
                <span class="price-from">From</span>
                <span class="price-amount">$${hotel.price}</span>
                <span class="price-night">/ night</span>
              </div>
              <a href="${detailUrl}" class="btn btn-lux-primary btn-sm">View Details</a>
            </div>
          </div>
        </article>
      </div>`;
  }

  /**
   * Filter hotels client-side after AJAX load
   */
  function filterHotels(hotels, filters) {
    let result = [...hotels];
    const q = (filters.query || '').toLowerCase();
    const dest = filters.destination || '';
    const stars = parseInt(filters.stars, 10);
    const maxPrice = parseInt(filters.maxPrice, 10);
    const amenities = filters.amenities || [];

    if (q) {
      result = result.filter(
        (h) =>
          h.name.toLowerCase().includes(q) ||
          h.location.toLowerCase().includes(q)
      );
    }
    if (dest) {
      result = result.filter((h) => h.destination === dest);
    }
    if (stars) {
      result = result.filter((h) => h.stars >= stars);
    }
    if (maxPrice && maxPrice < 2000) {
      result = result.filter((h) => h.price <= maxPrice);
    }
    if (amenities.length) {
      result = result.filter((h) =>
        amenities.every((a) => (h.amenities || []).includes(a))
      );
    }
    return result;
  }

  let cachedHotels = [];

  /**
   * AJAX: Load hotels (hotel search / listing)
   */
  async function loadHotels(filters = {}) {
    const grid = document.getElementById('hotelsGrid');
    const loading = document.getElementById('hotelsLoading');
    const empty = document.getElementById('hotelsEmpty');
    const countEl = document.getElementById('resultsCount');

    if (!grid) return;

    loading?.classList.remove('d-none');
    empty?.classList.add('d-none');

    try {
      const data = await fetchMock('hotels');
      cachedHotels = data.hotels || [];

      const params = new URLSearchParams(window.location.search);
      if (params.get('q')) filters.query = params.get('q');
      if (params.get('destination')) filters.destination = params.get('destination');

      const filtered = filterHotels(cachedHotels, filters);
      grid.innerHTML = filtered.map(renderHotelCard).join('');

      if (countEl) {
        countEl.textContent = filtered.length + ' hotel' + (filtered.length !== 1 ? 's' : '') + ' found';
      }
      empty?.classList.toggle('d-none', filtered.length > 0);

      document.querySelectorAll('.btn-favorite').forEach((btn) => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          btn.classList.toggle('active');
          const icon = btn.querySelector('i');
          icon.classList.toggle('far');
          icon.classList.toggle('fas');
        });
      });
    } catch (err) {
      console.error(err);
      if (countEl) countEl.textContent = 'Error loading hotels';
      window.showToast?.('Could not load hotels. Check console.', 'error');
    } finally {
      loading?.classList.add('d-none');
    }
  }

  /**
   * AJAX: Filter hotels
   */
  async function filterHotelsAjax(formData) {
    const filters = {
      destination: formData.get('destination') || '',
      stars: formData.get('stars') || '',
      maxPrice: formData.get('maxPrice') || 2000,
      amenities: formData.getAll('amenities[]'),
    };
    await loadHotels(filters);
    window.showToast?.('Filters applied', 'success');
  }

  /**
   * AJAX: Hotel search (debounced text search)
   */
  let searchTimeout;
  function initHotelSearch() {
    const input = document.getElementById('hotelSearchInput');
    const form = document.getElementById('hotelSearchForm');
    if (!input) return;

    const runSearch = () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        loadHotels({ query: input.value.trim() });
      }, 400);
    };

    input.addEventListener('input', runSearch);
    form?.addEventListener('submit', (e) => {
      e.preventDefault();
      runSearch();
    });
  }

  /**
   * AJAX: Login form submission
   */
  function initLoginAjax() {
    const form = document.getElementById('loginForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('loginSubmitBtn');
      const btnText = btn?.querySelector('.btn-text');
      const loader = btn?.querySelector('.btn-loader');

      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      const body = {
        email: form.email.value,
        password: form.password.value,
      };

      btn.disabled = true;
      btnText?.classList.add('d-none');
      loader?.classList.remove('d-none');

      try {
        const data = await fetchMock('login', { method: 'POST', body });
        if (data.success) {
          window.showToast?.(data.message || 'Login successful!');
          setTimeout(() => {
            window.location.href = config.baseUrl + '/profile.php';
          }, 1200);
        } else {
          window.showToast?.(data.message || 'Login failed', 'error');
        }
      } catch (err) {
        window.showToast?.('Connection error (demo)', 'error');
      } finally {
        btn.disabled = false;
        btnText?.classList.remove('d-none');
        loader?.classList.add('d-none');
      }
    });
  }

  /**
   * AJAX: Registration form submission
   */
  function initRegisterAjax() {
    const form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const pass = form.password.value;
      const confirm = form.confirmPassword.value;
      if (pass !== confirm) {
        window.showToast?.('Passwords do not match', 'error');
        return;
      }

      const btn = document.getElementById('registerSubmitBtn');
      const btnText = btn?.querySelector('.btn-text');
      const loader = btn?.querySelector('.btn-loader');

      const body = {
        firstName: form.firstName.value,
        lastName: form.lastName.value,
        email: form.email.value,
        phone: form.phone.value,
      };

      btn.disabled = true;
      btnText?.classList.add('d-none');
      loader?.classList.remove('d-none');

      try {
        const data = await fetchMock('register', { method: 'POST', body });
        if (data.success) {
          window.showToast?.(data.message || 'Account created!');
          setTimeout(() => {
            window.location.href = config.baseUrl + '/login.php';
          }, 1500);
        }
      } catch (err) {
        window.showToast?.('Registration failed (demo)', 'error');
      } finally {
        btn.disabled = false;
        btnText?.classList.remove('d-none');
        loader?.classList.add('d-none');
      }
    });
  }

  /**
   * AJAX: Booking form submission
   */
  function initBookingAjax() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const required = form.querySelectorAll('[required]');
      let valid = true;
      required.forEach((f) => {
        if (!f.value.trim()) {
          f.classList.add('is-invalid');
          valid = false;
        } else f.classList.remove('is-invalid');
      });
      if (!valid) {
        window.showToast?.('Please fill in all required fields', 'error');
        return;
      }

      const btn = document.getElementById('confirmBookingBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

      const body = {
        firstName: form.firstName.value,
        lastName: form.lastName.value,
        email: form.email.value,
        checkIn: form.checkIn.value,
        checkOut: form.checkOut.value,
        roomType: form.roomType.value,
      };

      try {
        const data = await fetchMock('booking', { method: 'POST', body });
        if (data.success) {
          const refEl = document.getElementById('bookingRefDisplay');
          if (refEl) refEl.textContent = data.booking.id;
          new bootstrap.Modal(document.getElementById('bookingConfirmModal')).show();
          window.showToast?.('Booking confirmed!');
        }
      } catch (err) {
        window.showToast?.('Booking failed (demo)', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Booking';
      }
    });
  }

  /**
   * AJAX: Room availability check
   */
  async function checkAvailability(dates) {
    const data = await fetchMock('availability', { method: 'POST', body: dates });
    return data;
  }

  /**
   * Filter form handler
   */
  function initFilterForm() {
    const form = document.getElementById('hotelFilterForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const fd = new FormData(form);
      filterHotelsAjax(fd);
    });

    document.getElementById('resetFilters')?.addEventListener('click', () => {
      setTimeout(() => loadHotels({}), 100);
    });
  }

  window.LuxeAjax = {
    loadHotels,
    filterHotelsAjax,
    checkAvailability,
    fetchMock,
  };

  document.addEventListener('DOMContentLoaded', () => {
    initHotelSearch();
    initLoginAjax();
    initRegisterAjax();
    initBookingAjax();
    initFilterForm();
  });
})();
