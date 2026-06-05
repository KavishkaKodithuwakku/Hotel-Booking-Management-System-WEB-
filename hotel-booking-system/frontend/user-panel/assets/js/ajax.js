/**
 * LuxeStay — User panel API integration
 */
(function () {
  'use strict';

  const config = window.LUXE_CONFIG || { baseUrl: '', assetPath: '', apiBaseUrl: '' };

  function renderHotelCard(hotel) {
    const detailUrl = config.baseUrl + '/hotel-details.php?id=' + hotel.id;
    const rating = parseFloat(hotel.rating) || 0;
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
                <span class="rating-value">${rating.toFixed(1)}</span>
                <small class="text-muted d-block">${hotel.reviews} reviews</small>
              </div>
            </div>
            <div class="hotel-card-footer">
              <div class="hotel-price">
                <span class="price-from">From</span>
                <span class="price-amount">$${hotel.price}</span>
                <span class="price-night">/ night</span>
              </div>
              <div class="d-flex gap-1">
              <a href="${detailUrl}" class="btn btn-lux-primary btn-sm">View Details</a>
              ${hotel.lat != null ? `<button type="button" class="btn btn-lux-outline btn-sm btn-show-on-map" data-hotel-id="${hotel.id}" title="Show on map"><i class="fas fa-map-marked-alt"></i></button>` : ''}
              </div>
            </div>
          </div>
        </article>
      </div>`;
  }

  function filterHotels(hotels, filters) {
    let result = [...hotels];
    const q = (filters.query || '').toLowerCase();
    const dest = filters.destination || '';
    const stars = parseInt(filters.stars, 10);
    const maxPrice = parseInt(filters.maxPrice, 10);
    const amenities = filters.amenities || [];

    if (q) {
      result = result.filter(
        (h) => h.name.toLowerCase().includes(q) || h.location.toLowerCase().includes(q)
      );
    }
    if (dest) result = result.filter((h) => h.destination === dest);
    if (stars) result = result.filter((h) => h.stars >= stars);
    if (maxPrice && maxPrice < 2000) result = result.filter((h) => h.price <= maxPrice);
    if (amenities.length) {
      result = result.filter((h) => amenities.every((a) => (h.amenities || []).includes(a)));
    }
    return result;
  }

  function bindHotelCardEvents() {
    document.querySelectorAll('.btn-favorite').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
      });
    });

    document.querySelectorAll('.btn-show-on-map').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const id = btn.dataset.hotelId;
        document.getElementById('hotelsMapWrap')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.getElementById('hotelsViewToggle')?.querySelector('[data-view="both"]')?.click();
        setTimeout(() => window.LuxeMap?.highlightHotel(id), 400);
      });
    });
  }

  async function loadHotels(filters = {}) {
    const grid = document.getElementById('hotelsGrid');
    const loading = document.getElementById('hotelsLoading');
    const empty = document.getElementById('hotelsEmpty');
    const countEl = document.getElementById('resultsCount');
    if (!grid) return;

    loading?.classList.remove('d-none');
    empty?.classList.add('d-none');

    try {
      const params = new URLSearchParams();
      const urlParams = new URLSearchParams(window.location.search);
      if (filters.query || urlParams.get('q')) params.set('q', filters.query || urlParams.get('q'));
      if (filters.destination || urlParams.get('destination')) {
        params.set('destination', filters.destination || urlParams.get('destination'));
      }
      if (filters.stars) params.set('stars', filters.stars);
      if (filters.maxPrice) params.set('maxPrice', filters.maxPrice);
      if (filters.sort) params.set('sort', filters.sort);

      const data = await window.LuxeApi.get('/hotels?' + params.toString());
      let hotels = data.hotels || [];

      if (filters.amenities?.length) {
        hotels = filterHotels(hotels, filters);
      }

      grid.innerHTML = hotels.map(renderHotelCard).join('');
      document.dispatchEvent(new CustomEvent('hotelsLoaded', { detail: { hotels } }));

      if (countEl) {
        countEl.textContent = hotels.length + ' hotel' + (hotels.length !== 1 ? 's' : '') + ' found';
      }
      empty?.classList.toggle('d-none', hotels.length > 0);
      bindHotelCardEvents();
    } catch (err) {
      console.error(err);
      if (countEl) countEl.textContent = 'Error loading hotels';
      window.showToast?.(err.message || 'Could not load hotels', 'error');
    } finally {
      loading?.classList.add('d-none');
    }
  }

  async function filterHotelsAjax(formData) {
    await loadHotels({
      destination: formData.get('destination') || '',
      stars: formData.get('stars') || '',
      maxPrice: formData.get('maxPrice') || 2000,
      amenities: formData.getAll('amenities[]'),
    });
    window.showToast?.('Filters applied', 'success');
  }

  let searchTimeout;
  function initHotelSearch() {
    const input = document.getElementById('hotelSearchInput');
    const form = document.getElementById('hotelSearchForm');
    if (!input) return;
    const runSearch = () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => loadHotels({ query: input.value.trim() }), 400);
    };
    input.addEventListener('input', runSearch);
    form?.addEventListener('submit', (e) => {
      e.preventDefault();
      runSearch();
    });
  }

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

      btn.disabled = true;
      btnText?.classList.add('d-none');
      loader?.classList.remove('d-none');

      try {
        const data = await window.LuxeApi.post('/auth/login', {
          email: form.email.value,
          password: form.password.value,
        });
        window.LuxeApi.setToken(data.token);
        window.showToast?.(data.message || 'Login successful!');
        setTimeout(() => {
          window.location.href = config.baseUrl + '/profile.php';
        }, 800);
      } catch (err) {
        window.showToast?.(err.message || 'Login failed', 'error');
      } finally {
        btn.disabled = false;
        btnText?.classList.remove('d-none');
        loader?.classList.add('d-none');
      }
    });
  }

  function initRegisterAjax() {
    const form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (form.password.value !== form.confirmPassword.value) {
        window.showToast?.('Passwords do not match', 'error');
        return;
      }

      const btn = document.getElementById('registerSubmitBtn');
      const btnText = btn?.querySelector('.btn-text');
      const loader = btn?.querySelector('.btn-loader');
      btn.disabled = true;
      btnText?.classList.add('d-none');
      loader?.classList.remove('d-none');

      try {
        const data = await window.LuxeApi.post('/auth/register', {
          firstName: form.firstName.value,
          lastName: form.lastName.value,
          email: form.email.value,
          phone: form.phone?.value || '',
          password: form.password.value,
        });
        window.LuxeApi.setToken(data.token);
        window.showToast?.(data.message || 'Account created!');
        setTimeout(() => {
          window.location.href = config.baseUrl + '/profile.php';
        }, 1000);
      } catch (err) {
        window.showToast?.(err.message || 'Registration failed', 'error');
      } finally {
        btn.disabled = false;
        btnText?.classList.remove('d-none');
        loader?.classList.add('d-none');
      }
    });
  }

  function initBookingAjax() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!window.LuxeApi.getToken()) {
        window.showToast?.('Please login to complete booking', 'error');
        setTimeout(() => {
          window.location.href = config.baseUrl + '/login.php';
        }, 1200);
        return;
      }

      const btn = document.getElementById('confirmBookingBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

      try {
        const params = new URLSearchParams(window.location.search);
        const data = await window.LuxeApi.post('/bookings', {
          hotel_id: parseInt(params.get('hotel') || form.hotel_id?.value || 1, 10),
          room_id: parseInt(form.room_id?.value || params.get('room_id') || 1, 10),
          check_in: form.checkIn.value,
          check_out: form.checkOut.value,
          guests: parseInt(form.guests?.value || 2, 10),
          firstName: form.firstName.value,
          lastName: form.lastName.value,
          email: form.email.value,
          payment_method: 'card',
          card_number: form.cardNumber?.value || '',
        });

        if (data.booking) {
          const refEl = document.getElementById('bookingRefDisplay');
          if (refEl) refEl.textContent = data.booking.booking_ref || data.booking.id;
          // Store booking id so payment page can load it
          sessionStorage.setItem('pendingBookingId', data.booking.id);
          new bootstrap.Modal(document.getElementById('bookingConfirmModal')).show();
          window.showToast?.('Booking created! Proceed to payment.');
          // Update pay-now link in modal if present
          const payLink = document.getElementById('bookingPayLink');
          if (payLink) payLink.href = config.baseUrl + '/payment.php?booking_id=' + data.booking.id;
        }
      } catch (err) {
        window.showToast?.(err.message || 'Booking failed', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Booking';
      }
    });
  }

  async function checkAvailability(dates) {
    const form = document.getElementById('availabilityForm');
    const hotelId = form?.dataset?.hotelId || new URLSearchParams(window.location.search).get('id');
    const body = {
      check_in: dates.checkIn,
      check_out: dates.checkOut,
      hotel_id: hotelId ? parseInt(hotelId, 10) : null,
      room_id: dates.room_id || null,
    };
    return window.LuxeApi.post('/availability/check', body);
  }

  async function fetchHotels() {
    const data = await window.LuxeApi.get('/hotels');
    return data.hotels || [];
  }

  function initFilterForm() {
    const form = document.getElementById('hotelFilterForm');
    if (!form) return;
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      filterHotelsAjax(new FormData(form));
    });
    document.getElementById('resetFilters')?.addEventListener('click', () => {
      setTimeout(() => loadHotels({}), 100);
    });
  }

  function initContactAjax() {
    const form = document.getElementById('contactForm');
    if (!form) return;
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      try {
        await window.LuxeApi.post('/contact', {
          name: form.name.value,
          email: form.email.value,
          subject: form.subject?.value || 'Contact',
          message: form.message.value,
        });
        window.showToast?.('Message sent successfully!');
        form.reset();
      } catch (err) {
        window.showToast?.(err.message, 'error');
      }
    });
  }

  window.LuxeAjax = {
    loadHotels,
    filterHotelsAjax,
    checkAvailability,
    fetchHotels,
  };

  document.addEventListener('DOMContentLoaded', () => {
    initHotelSearch();
    initLoginAjax();
    initRegisterAjax();
    initBookingAjax();
    initFilterForm();
    initContactAjax();
  });
})();
