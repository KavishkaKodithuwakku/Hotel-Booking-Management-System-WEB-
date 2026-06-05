<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Hotels';
$bodyClass = 'page-hotels';
$extraCss = ['https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'];
$extraJs = [
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    $assetPath . '/js/map.js',
];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="page-hero page-hero-sm">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <h1 class="page-hero-title">Luxury <span class="text-gold">Hotels</span></h1>
        <p class="page-hero-subtitle">Discover exceptional stays around the world</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <button class="btn btn-lux-outline w-100 d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                    <i class="fas fa-filter me-2"></i>Filters
                </button>
                <div class="d-none d-lg-block sticky-sidebar">
                    <?php $sidebarType = 'filter'; require __DIR__ . '/includes/sidebar.php'; ?>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hotels-toolbar mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <form id="hotelSearchForm" class="search-inline">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control lux-input" id="hotelSearchInput" placeholder="Search hotels by name or location...">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="results-count" id="resultsCount">Loading hotels...</span>
                            <select class="form-select lux-input d-inline-block w-auto ms-2" id="sortHotels">
                                <option value="recommended">Recommended</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Highest Rated</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="hotels-view-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h2 class="hotels-map-heading mb-0"><i class="fas fa-map-marked-alt text-gold me-2"></i>Hotel Locations</h2>
                    <div class="btn-group hotels-view-toggle" id="hotelsViewToggle" role="group" aria-label="View mode">
                        <button type="button" class="btn btn-lux-outline btn-sm active" data-view="both">Map & List</button>
                        <button type="button" class="btn btn-lux-outline btn-sm" data-view="map">Map Only</button>
                        <button type="button" class="btn btn-lux-outline btn-sm" data-view="list">List Only</button>
                    </div>
                </div>

                <div class="lux-map-card lux-card mb-4" id="hotelsMapWrap">
                    <div id="hotelsMap" class="lux-map-container" aria-label="Map showing hotel locations"></div>
                    <p class="lux-map-hint mb-0"><i class="fas fa-info-circle me-1"></i> Click a marker for details. Use the <i class="fas fa-map-marked-alt"></i> button on a hotel card to locate it on the map.</p>
                </div>

                <div id="hotelsListWrap">
                <div class="loading-overlay d-none" id="hotelsLoading">
                    <div class="lux-spinner"></div>
                </div>
                <div class="row g-4" id="hotelsGrid"></div>
                </div>
                <div class="empty-state d-none" id="hotelsEmpty">
                    <i class="fas fa-hotel fa-3x text-gold mb-3"></i>
                    <h4>No hotels found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Offcanvas -->
<div class="offcanvas offcanvas-start lux-offcanvas" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <?php $sidebarType = 'filter'; require __DIR__ . '/includes/sidebar.php'; ?>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
