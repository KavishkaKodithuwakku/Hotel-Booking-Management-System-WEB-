/**
 * LuxeStay — Hotel location maps (Leaflet + OpenStreetMap)
 */
(function () {
  'use strict';

  const config = window.LUXE_CONFIG || { baseUrl: '', assetPath: '' };

  const GOLD = '#c9a227';
  const NAVY = '#1a2744';

  function hasCoords(hotel) {
    return typeof hotel.lat === 'number' && typeof hotel.lng === 'number';
  }

  function directionsUrl(hotel) {
    return (
      'https://www.google.com/maps/dir/?api=1&destination=' +
      encodeURIComponent(hotel.lat + ',' + hotel.lng)
    );
  }

  function createMarkerIcon() {
    return L.divIcon({
      className: 'lux-map-marker',
      html: '<span class="lux-map-marker-pin"><i class="fas fa-hotel"></i></span>',
      iconSize: [36, 42],
      iconAnchor: [18, 42],
      popupAnchor: [0, -42],
    });
  }

  function popupContent(hotel) {
    const detailUrl = config.baseUrl + '/hotel-details.php?id=' + hotel.id;
    const address = hotel.address || hotel.location;
    return (
      '<div class="lux-map-popup">' +
      '<img src="' +
      hotel.image +
      '" alt="" class="lux-map-popup-img">' +
      '<h6>' +
      hotel.name +
      '</h6>' +
      '<p class="lux-map-popup-loc"><i class="fas fa-map-marker-alt"></i> ' +
      address +
      '</p>' +
      '<p class="lux-map-popup-price">From <strong>$' +
      hotel.price +
      '</strong>/night</p>' +
      '<div class="lux-map-popup-actions">' +
      '<a href="' +
      detailUrl +
      '" class="btn btn-lux-primary btn-sm">View Details</a>' +
      '<a href="' +
      directionsUrl(hotel) +
      '" class="btn btn-lux-outline btn-sm" target="_blank" rel="noopener">Directions</a>' +
      '</div></div>'
    );
  }

  function fitMapToHotels(map, markersLayer, hotels) {
    const withCoords = hotels.filter(hasCoords);
    if (!withCoords.length) return;

    if (withCoords.length === 1) {
      map.setView([withCoords[0].lat, withCoords[0].lng], 14);
      return;
    }

    const bounds = L.latLngBounds(withCoords.map((h) => [h.lat, h.lng]));
    map.fitBounds(bounds, { padding: [48, 48], maxZoom: 12 });
  }

  const LuxeMap = {
    map: null,
    markersLayer: null,

    initMapContainer(containerId, options) {
      const el = document.getElementById(containerId);
      if (!el || typeof L === 'undefined') return null;

      if (this.map) {
        this.map.remove();
        this.map = null;
        this.markersLayer = null;
      }

      const map = L.map(containerId, {
        scrollWheelZoom: options.scrollWheelZoom !== false,
        zoomControl: true,
      });

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
      }).addTo(map);

      const markersLayer = L.layerGroup().addTo(map);

      this.map = map;
      this.markersLayer = markersLayer;

      setTimeout(() => map.invalidateSize(), 100);

      return map;
    },

    setMarkers(hotels, options) {
      if (!this.map || !this.markersLayer) return;

      options = options || {};
      this.markersLayer.clearLayers();

      const valid = (hotels || []).filter(hasCoords);
      valid.forEach((hotel) => {
        const marker = L.marker([hotel.lat, hotel.lng], { icon: createMarkerIcon() });
        marker.bindPopup(popupContent(hotel), { maxWidth: 280, className: 'lux-leaflet-popup' });
        marker.addTo(this.markersLayer);

        marker.on('click', () => {
          document.dispatchEvent(
            new CustomEvent('hotelMapSelect', { detail: { hotelId: hotel.id } })
          );
        });
      });

      if (options.fitBounds !== false) {
        fitMapToHotels(this.map, this.markersLayer, valid);
      }

      if (options.highlightId) {
        const target = valid.find((h) => String(h.id) === String(options.highlightId));
        if (target) {
          this.map.setView([target.lat, target.lng], 15);
          valid.forEach((h) => {
            if (String(h.id) === String(options.highlightId)) {
              const layers = this.markersLayer.getLayers();
              layers.forEach((layer) => {
                const latlng = layer.getLatLng();
                if (latlng.lat === target.lat && latlng.lng === target.lng) {
                  layer.openPopup();
                }
              });
            }
          });
        }
      }
    },

    highlightHotel(hotelId) {
      if (!this.markersLayer) return;
      const layers = this.markersLayer.getLayers();
      layers.forEach((layer) => {
        layer.closePopup();
      });
      const hotel = (window.__luxeHotelsOnMap || []).find((h) => String(h.id) === String(hotelId));
      if (hotel && hasCoords(hotel)) {
        this.map.setView([hotel.lat, hotel.lng], 14, { animate: true });
        layers.forEach((layer) => {
          const ll = layer.getLatLng();
          if (ll.lat === hotel.lat && ll.lng === hotel.lng) {
            layer.openPopup();
          }
        });
      }
    },
  };

  async function fetchHotels() {
    if (window.LuxeAjax?.fetchHotels) {
      return window.LuxeAjax.fetchHotels();
    }
    if (window.LuxeApi) {
      const data = await window.LuxeApi.get('/hotels');
      return data.hotels || [];
    }
    const res = await fetch(config.assetPath + '/data/hotels.json');
    const data = await res.json();
    return data.hotels || [];
  }

  function initHotelsPageMap() {
    const mapEl = document.getElementById('hotelsMap');
    if (!mapEl) return;

    LuxeMap.initMapContainer('hotelsMap', {});

    document.addEventListener('hotelsLoaded', (e) => {
      const hotels = e.detail?.hotels || [];
      window.__luxeHotelsOnMap = hotels;
      LuxeMap.setMarkers(hotels);
    });

    document.addEventListener('hotelMapSelect', (e) => {
      const card = document.querySelector('[data-hotel-id="' + e.detail.hotelId + '"]');
      if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        card.classList.add('hotel-card-highlight');
        setTimeout(() => card.classList.remove('hotel-card-highlight'), 2000);
      }
    });

    const grid = document.getElementById('hotelsGrid');
    grid?.addEventListener(
      'mouseenter',
      (e) => {
        const card = e.target.closest('[data-hotel-id]');
        if (card?.dataset?.hotelId) LuxeMap.highlightHotel(card.dataset.hotelId);
      },
      true
    );

    fetchHotels().then((hotels) => {
      window.__luxeHotelsOnMap = hotels;
      if (!document.getElementById('hotelsGrid')?.innerHTML?.trim()) {
        LuxeMap.setMarkers(hotels);
      }
    });

    initViewToggle();
  }

  function initViewToggle() {
    const toggle = document.getElementById('hotelsViewToggle');
    const mapWrap = document.getElementById('hotelsMapWrap');
    const listWrap = document.getElementById('hotelsListWrap');
    if (!toggle || !mapWrap || !listWrap) return;

    toggle.querySelectorAll('[data-view]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const view = btn.dataset.view;
        toggle.querySelectorAll('[data-view]').forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');

        if (view === 'map') {
          mapWrap.classList.remove('d-none');
          listWrap.classList.add('d-none');
          LuxeMap.map?.invalidateSize();
        } else if (view === 'list') {
          mapWrap.classList.add('d-none');
          listWrap.classList.remove('d-none');
        } else {
          mapWrap.classList.remove('d-none');
          listWrap.classList.remove('d-none');
          LuxeMap.map?.invalidateSize();
        }
      });
    });
  }

  async function initHotelDetailMap() {
    const mapEl = document.getElementById('hotelLocationMap');
    if (!mapEl) return;

    const params = new URLSearchParams(window.location.search);
    const hotelId = params.get('id') || '1';

    const hotels = await fetchHotels();
    const hotel = hotels.find((h) => String(h.id) === String(hotelId)) || hotels[0];
    if (!hotel || !hasCoords(hotel)) return;

    const nameEl = document.getElementById('hotelDetailName');
    const locEl = document.getElementById('hotelDetailLocation');
    const addrEl = document.getElementById('hotelDetailAddress');
    const dirEl = document.getElementById('hotelDirectionsLink');

    if (nameEl && hotel.name) nameEl.textContent = hotel.name;
    if (locEl) {
      locEl.innerHTML =
        '<i class="fas fa-map-marker-alt text-gold me-1"></i> ' + (hotel.location || '');
    }
    if (addrEl && hotel.address) addrEl.textContent = hotel.address;
    if (dirEl) {
      dirEl.href = directionsUrl(hotel);
      dirEl.classList.remove('d-none');
    }

    LuxeMap.initMapContainer('hotelLocationMap', { scrollWheelZoom: false });
    LuxeMap.setMarkers([hotel], { fitBounds: false, highlightId: hotel.id });
    LuxeMap.map.setView([hotel.lat, hotel.lng], 15);
  }

  window.LuxeMap = LuxeMap;

  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('hotelsMap')) initHotelsPageMap();
    if (document.getElementById('hotelLocationMap')) initHotelDetailMap();
  });
})();
