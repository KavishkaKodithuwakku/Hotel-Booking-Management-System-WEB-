/**
 * LuxeStay — Main Frontend JavaScript
 * No backend connections — UI interactions only
 */

(function () {
  'use strict';

  const config = window.LUXE_CONFIG || { baseUrl: '', assetPath: '' };

  /* ---------- Navbar Scroll Effect ---------- */
  function initNavbar() {
    const navbar = document.getElementById('mainNavbar');
    if (!navbar) return;

    const onScroll = () => {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Hero Image Slider ---------- */
  function initHeroSlider() {
    const slider = document.getElementById('heroSlider');
    const dotsContainer = document.getElementById('heroDots');
    if (!slider || !dotsContainer) return;

    const slides = slider.querySelectorAll('.hero-slide');
    let current = 0;
    let interval;

    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'hero-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', 'Slide ' + (i + 1));
      dot.addEventListener('click', () => goTo(i));
      dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.hero-dot');

    function goTo(index) {
      slides[current].classList.remove('active');
      dots[current].classList.remove('active');
      current = index;
      slides[current].classList.add('active');
      dots[current].classList.add('active');
    }

    function next() {
      goTo((current + 1) % slides.length);
    }

    interval = setInterval(next, 6000);

    slider.addEventListener('mouseenter', () => clearInterval(interval));
    slider.addEventListener('mouseleave', () => {
      interval = setInterval(next, 6000);
    });
  }

  /* ---------- Hotel Gallery ---------- */
  function initGallery() {
    const mainImg = document.getElementById('galleryMainImg');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const prevBtn = document.getElementById('galleryPrev');
    const nextBtn = document.getElementById('galleryNext');

    if (!mainImg || !thumbs.length) return;

    const images = Array.from(thumbs).map((t) => t.dataset.image);
    let idx = 0;

    function show(i) {
      idx = (i + images.length) % images.length;
      mainImg.style.opacity = '0';
      setTimeout(() => {
        mainImg.src = images[idx];
        mainImg.style.opacity = '1';
      }, 200);
      thumbs.forEach((t, j) => t.classList.toggle('active', j === idx));
    }

    mainImg.style.transition = 'opacity 0.3s ease';
    thumbs.forEach((thumb, i) => {
      thumb.addEventListener('click', () => show(i));
    });
    prevBtn?.addEventListener('click', () => show(idx - 1));
    nextBtn?.addEventListener('click', () => show(idx + 1));
  }

  /* ---------- Form Validation ---------- */
  function validateForm(form) {
    let valid = true;
    form.querySelectorAll('[required]').forEach((field) => {
      if (!field.value.trim()) {
        field.classList.add('is-invalid');
        valid = false;
      } else {
        field.classList.remove('is-invalid');
      }
    });

    const emailFields = form.querySelectorAll('[type="email"]');
    emailFields.forEach((field) => {
      if (field.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
        field.classList.add('is-invalid');
        valid = false;
      }
    });

    const pass = form.querySelector('[name="password"]');
    const confirm = form.querySelector('[name="confirmPassword"]');
    if (pass && confirm && pass.value !== confirm.value) {
      confirm.classList.add('is-invalid');
      valid = false;
      showToast('Passwords do not match', 'error');
    }

    return valid;
  }

  /* ---------- Toast Notifications ---------- */
  window.showToast = function (message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const id = 'toast-' + Date.now();
    const html = `
      <div id="${id}" class="toast lux-toast ${type} align-items-center" role="alert">
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>`;
    container.insertAdjacentHTML('beforeend', html);
    const el = document.getElementById(id);
    const toast = new bootstrap.Toast(el, { delay: 4000 });
    toast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
  };

  /* ---------- Modal Helpers ---------- */
  function initModals() {
    document.querySelectorAll('[data-confirm]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        if (!confirm(btn.dataset.confirm)) e.preventDefault();
      });
    });
  }

  /* ---------- Password Toggle ---------- */
  function initPasswordToggle() {
    document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
      btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    });
  }

  /* ---------- Password Strength ---------- */
  function initPasswordStrength() {
    const input = document.getElementById('regPassword');
    const bar = document.getElementById('passwordStrength');
    if (!input || !bar) return;

    input.addEventListener('input', () => {
      const v = input.value;
      let strength = 0;
      if (v.length >= 8) strength++;
      if (/[A-Z]/.test(v)) strength++;
      if (/[0-9]/.test(v)) strength++;
      if (/[^A-Za-z0-9]/.test(v)) strength++;

      const widths = ['0%', '25%', '50%', '75%', '100%'];
      const colors = ['#e9ecef', '#dc3545', '#ffc107', '#0d6efd', '#198754'];
      bar.innerHTML = `<div class="password-strength-bar" style="width:${widths[strength]};background:${colors[strength]}"></div>`;
    });
  }

  /* ---------- Favorites ---------- */
  function initFavorites() {
    document.querySelectorAll('.btn-favorite').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
        showToast(btn.classList.contains('active') ? 'Added to favorites' : 'Removed from favorites');
      });
    });
  }

  /* ---------- Star Rating (Review) ---------- */
  function initStarRating() {
    const container = document.getElementById('starRatingInput');
    const hidden = document.getElementById('reviewRating');
    if (!container) return;

    const buttons = container.querySelectorAll('.star-btn');
    buttons.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        const rating = parseInt(btn.dataset.rating, 10);
        hidden.value = rating;
        buttons.forEach((b, j) => {
          const icon = b.querySelector('i');
          if (j < rating) {
            icon.classList.replace('far', 'fas');
            b.classList.add('active');
          } else {
            icon.classList.replace('fas', 'far');
            b.classList.remove('active');
          }
        });
      });
    });

    document.getElementById('reviewForm')?.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Thank you! Your review has been submitted (demo).');
      bootstrap.Modal.getInstance(document.getElementById('writeReviewModal'))?.hide();
    });
  }

  /* ---------- Availability Check ---------- */
  function initAvailability() {
    const btn = document.getElementById('checkAvailabilityBtn');
    const result = document.getElementById('availabilityResult');
    if (!btn || !result) return;

    btn.addEventListener('click', () => {
      const checkIn = document.getElementById('availCheckIn')?.value;
      const checkOut = document.getElementById('availCheckOut')?.value;
      if (!checkIn || !checkOut) {
        showToast('Please select check-in and check-out dates', 'error');
        return;
      }
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking...';

      setTimeout(() => {
        if (window.LuxeAjax?.checkAvailability) {
          window.LuxeAjax.checkAvailability({ checkIn, checkOut }).then((data) => {
            result.classList.remove('d-none', 'success', 'danger');
            if (data.available) {
              result.classList.add('success');
              result.innerHTML = `<i class="fas fa-check-circle me-1"></i>${data.message} (${data.roomsLeft} rooms left)`;
            } else {
              result.classList.add('danger');
              result.innerHTML = '<i class="fas fa-times-circle me-1"></i>No rooms available';
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Check Availability';
          });
        }
      }, 800);
    });
  }

  /* ---------- Booking Summary Calculator ---------- */
  function initBookingSummary() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    const roomPrices = { deluxe: 349, executive: 549, presidential: 1299 };

    function updateSummary() {
      const checkIn = form.querySelector('[name="checkIn"]')?.value;
      const checkOut = form.querySelector('[name="checkOut"]')?.value;
      const guests = form.querySelector('[name="guests"]')?.value || '2';
      const roomType = form.querySelector('[name="roomType"]')?.value || 'deluxe';
      const price = roomPrices[roomType] || 349;

      let nights = 5;
      if (checkIn && checkOut) {
        const diff = (new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24);
        nights = Math.max(1, Math.ceil(diff));
      }

      const subtotal = price * nights;
      const tax = Math.round(subtotal * 0.1);
      const total = subtotal + tax;

      const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
      };

      set('summaryNights', nights);
      set('summaryGuests', guests);
      set('summaryRate', '$' + price);
      set('summaryTax', '$' + tax);
      set('summaryTotal', '$' + total.toLocaleString());
      set('summaryRoom', form.querySelector('[name="roomType"] option:checked')?.text.split('—')[0].trim() || 'Deluxe');
    }

    form.querySelectorAll('input, select').forEach((el) => {
      el.addEventListener('change', updateSummary);
    });
    updateSummary();
  }

  /* ---------- Booking Cancellation ---------- */
  function initBookingCancellation() {
    let cancelId = '';
    const modal = document.getElementById('cancelBookingModal');
    const idDisplay = document.getElementById('cancelBookingId');
    const confirmBtn = document.getElementById('confirmCancelBtn');

    document.querySelectorAll('.btn-cancel-booking').forEach((btn) => {
      btn.addEventListener('click', () => {
        cancelId = btn.dataset.id;
        if (idDisplay) idDisplay.textContent = cancelId;
        new bootstrap.Modal(modal).show();
      });
    });

    confirmBtn?.addEventListener('click', () => {
      const card = document.querySelector(`[data-booking-id="${cancelId}"]`);
      if (card) {
        const badge = card.querySelector('.status-badge');
        if (badge) {
          badge.className = 'status-badge badge-cancelled';
          badge.textContent = 'Cancelled';
        }
        card.querySelector('.btn-cancel-booking')?.remove();
      }
      bootstrap.Modal.getInstance(modal)?.hide();
      showToast('Booking ' + cancelId + ' has been cancelled (demo).');
    });
  }

  /* ---------- Booking History Filter ---------- */
  function initBookingHistoryFilter() {
    const search = document.getElementById('bookingSearchInput');
    const status = document.getElementById('bookingStatusFilter');
    const list = document.getElementById('bookingHistoryList');
    if (!list) return;

    function filter() {
      const q = (search?.value || '').toLowerCase();
      const s = (status?.value || '').toLowerCase();
      list.querySelectorAll('.booking-card').forEach((card) => {
        const text = card.textContent.toLowerCase();
        const cardStatus = card.querySelector('.status-badge')?.textContent.toLowerCase() || '';
        const matchSearch = !q || text.includes(q);
        const matchStatus = !s || cardStatus.includes(s);
        card.style.display = matchSearch && matchStatus ? '' : 'none';
      });
    }

    search?.addEventListener('input', filter);
    status?.addEventListener('change', filter);
  }

  /* ---------- Price Range Label ---------- */
  function initPriceRange() {
    const range = document.getElementById('priceRange');
    const label = document.getElementById('priceRangeLabel');
    if (!range || !label) return;
    range.addEventListener('input', () => {
      label.textContent = 'Up to $' + range.value;
    });
  }

  /* ---------- Payment Page ---------- */
  function initPaymentPage() {
    const form = document.getElementById('paymentForm');
    const success = document.getElementById('paymentSuccess');
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!validateForm(form)) return;
      const btn = document.getElementById('payNowBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
      setTimeout(() => {
        form.classList.add('d-none');
        success?.classList.remove('d-none');
        showToast('Payment successful!');
      }, 1500);
    });
  }

  /* ---------- Contact & Newsletter ---------- */
  function initMiscForms() {
    document.getElementById('contactForm')?.addEventListener('submit', (e) => {
      if (window.LuxeApi) return;
      e.preventDefault();
      if (!validateForm(e.target)) return;
      showToast('Message sent! We will respond within 24 hours (demo).');
      e.target.reset();
    });

    document.getElementById('newsletterForm')?.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Subscribed to newsletter!');
      e.target.reset();
    });

    document.getElementById('forgotPasswordForm')?.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Password reset link sent to your email (demo).');
      bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'))?.hide();
    });

    document.getElementById('editProfileForm')?.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Profile updated successfully (demo).');
      bootstrap.Modal.getInstance(document.getElementById('editProfileModal'))?.hide();
    });

    document.getElementById('heroSearchForm')?.addEventListener('submit', (e) => {
      e.preventDefault();
      const dest = document.getElementById('searchDestination')?.value || '';
      window.location.href = config.baseUrl + '/hotels.php' + (dest ? '?q=' + encodeURIComponent(dest) : '');
    });
  }

  /* ---------- Sort Hotels (client-side on loaded grid) ---------- */
  function initHotelSort() {
    const sort = document.getElementById('sortHotels');
    const grid = document.getElementById('hotelsGrid');
    if (!sort || !grid) return;

    sort.addEventListener('change', () => {
      const cards = Array.from(grid.querySelectorAll('.hotel-card'));
      const parent = cards[0]?.parentElement;
      if (!parent) return;

      cards.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price) || 0;
        const priceB = parseFloat(b.dataset.price) || 0;
        const ratingA = parseFloat(a.querySelector('.rating-value')?.textContent) || 0;
        const ratingB = parseFloat(b.querySelector('.rating-value')?.textContent) || 0;
        switch (sort.value) {
          case 'price-low': return priceA - priceB;
          case 'price-high': return priceB - priceA;
          case 'rating': return ratingB - ratingA;
          default: return 0;
        }
      });

      cards.forEach((card) => {
        parent.appendChild(card.closest('[class*="col-"]') || card);
      });
    });
  }

  /* ---------- Init ---------- */
  document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initHeroSlider();
    initGallery();
    initPasswordToggle();
    initPasswordStrength();
    initFavorites();
    initStarRating();
    initAvailability();
    initBookingSummary();
    initBookingCancellation();
    initBookingHistoryFilter();
    initPriceRange();
    initPaymentPage();
    initMiscForms();
    initModals();
    initHotelSort();

    if (document.getElementById('hotelsGrid') && window.LuxeAjax) {
      window.LuxeAjax.loadHotels();
    }
  });
})();
