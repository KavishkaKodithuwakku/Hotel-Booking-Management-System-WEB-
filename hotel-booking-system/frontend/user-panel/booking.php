<?php
$pageTitle = 'Book Your Stay';
$bodyClass = 'page-booking';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="page-hero page-hero-sm">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <h1 class="page-hero-title">Complete Your <span class="text-gold">Booking</span></h1>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <form id="bookingForm" novalidate>
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Guest Details -->
                    <div class="lux-card p-4 mb-4">
                        <h4 class="mb-4"><i class="fas fa-user text-gold me-2"></i>Guest Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control lux-input" name="firstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control lux-input" name="lastName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control lux-input" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="tel" class="form-control lux-input" name="phone" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Special Requests</label>
                                <textarea class="form-control lux-input" name="requests" rows="3" placeholder="Early check-in, dietary requirements, etc."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Stay Details -->
                    <div class="lux-card p-4 mb-4">
                        <h4 class="mb-4"><i class="fas fa-calendar-alt text-gold me-2"></i>Stay Details</h4>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Check-in *</label>
                                <input type="date" class="form-control lux-input" name="checkIn" id="bookingCheckIn" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Check-out *</label>
                                <input type="date" class="form-control lux-input" name="checkOut" id="bookingCheckOut" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Guests *</label>
                                <select class="form-select lux-input" name="guests" required>
                                    <option value="1">1 Guest</option>
                                    <option value="2" selected>2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Room Type *</label>
                                <select class="form-select lux-input" name="roomType" id="roomTypeSelect" required>
                                    <option value="deluxe">Deluxe King Room — $349/night</option>
                                    <option value="executive">Executive Suite — $549/night</option>
                                    <option value="presidential">Presidential Suite — $1,299/night</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods UI -->
                    <div class="lux-card p-4">
                        <h4 class="mb-4"><i class="fas fa-credit-card text-gold me-2"></i>Payment Method</h4>
                        <div class="payment-methods row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="paymentMethod" value="card" checked>
                                    <div class="payment-card-inner">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Credit Card</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="paymentMethod" value="paypal">
                                    <div class="payment-card-inner">
                                        <i class="fab fa-paypal"></i>
                                        <span>PayPal</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="paymentMethod" value="apple">
                                    <div class="payment-card-inner">
                                        <i class="fab fa-apple-pay"></i>
                                        <span>Apple Pay</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div id="cardPaymentFields">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control lux-input" name="cardName" placeholder="John Doe">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control lux-input" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expiry</label>
                                    <input type="text" class="form-control lux-input" name="cardExpiry" placeholder="MM/YY">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control lux-input" name="cardCvv" placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="col-lg-4">
                    <div class="booking-summary lux-card p-4 sticky-top">
                        <h4 class="mb-4">Booking Summary</h4>
                        <div class="summary-hotel mb-3">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80" alt="Hotel" class="summary-img">
                            <div>
                                <h6 class="mb-0" id="summaryHotelName">Grand Luxe Resort</h6>
                                <small class="text-muted">Paris, France</small>
                            </div>
                        </div>
                        <hr>
                        <ul class="summary-list">
                            <li><span>Room</span><span id="summaryRoom">Deluxe King</span></li>
                            <li><span>Nights</span><span id="summaryNights">5</span></li>
                            <li><span>Guests</span><span id="summaryGuests">2</span></li>
                            <li><span>Room rate</span><span id="summaryRate">$349</span></li>
                            <li><span>Taxes & fees</span><span id="summaryTax">$175</span></li>
                        </ul>
                        <hr>
                        <div class="summary-total d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong class="text-gold" id="summaryTotal">$1,920</strong>
                        </div>
                        <button type="submit" class="btn btn-lux-primary w-100 btn-lg mt-4" id="confirmBookingBtn">
                            <i class="fas fa-lock me-2"></i>Confirm Booking
                        </button>
                        <p class="text-center text-muted small mt-3 mb-0">
                            <i class="fas fa-shield-alt text-gold"></i> Secure checkout — demo UI only
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Booking Confirmation Modal -->
<div class="modal fade lux-modal" id="bookingConfirmModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lux-card text-center p-4">
            <div class="confirm-icon mb-3"><i class="fas fa-check-circle text-gold"></i></div>
            <h3>Booking Confirmed!</h3>
            <p class="text-muted">Your reservation has been successfully placed.</p>
            <p class="booking-ref">Reference: <strong id="bookingRefDisplay">BK-XXXX</strong></p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="<?= $pagePath ?>/booking-history.php" class="btn btn-lux-primary">View Bookings</a>
                <a href="<?= $pagePath ?>/index.php" class="btn btn-lux-outline">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
