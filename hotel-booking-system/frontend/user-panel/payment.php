<?php
$pageTitle = 'Payment';
$bodyClass = 'page-payment';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="payment-steps mb-5">
                    <div class="step completed"><span>1</span> Booking</div>
                    <div class="step-line"></div>
                    <div class="step active"><span>2</span> Payment</div>
                    <div class="step-line"></div>
                    <div class="step"><span>3</span> Confirmation</div>
                </div>

                <div class="lux-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-lock text-gold fa-2x mb-3"></i>
                        <h2>Secure Payment</h2>
                        <p class="text-muted">Complete your payment to confirm your reservation</p>
                    </div>

                    <div class="payment-amount-box text-center mb-4">
                        <span class="text-muted">Amount Due</span>
                        <h2 class="text-gold mb-0" id="paymentAmount">$–</h2>
                        <small class="text-muted" id="paymentBookingRef">Loading booking…</small>
                    </div>

                    <form id="paymentForm">
                        <input type="hidden" id="hiddenBookingId" name="booking_id">
                        <div class="payment-methods row g-3 mb-4">
                            <div class="col-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="payMethod" value="visa" checked>
                                    <div class="payment-card-inner"><i class="fab fa-cc-visa"></i></div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="payMethod" value="mastercard">
                                    <div class="payment-card-inner"><i class="fab fa-cc-mastercard"></i></div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="payment-card-option">
                                    <input type="radio" name="payMethod" value="amex">
                                    <div class="payment-card-inner"><i class="fab fa-cc-amex"></i></div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cardholder Name</label>
                            <input type="text" class="form-control lux-input" id="cardName" placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Card Number</label>
                            <input type="text" class="form-control lux-input" id="cardNumber"
                                   placeholder="0000 0000 0000 0000" maxlength="19" required>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" class="form-control lux-input" placeholder="MM/YY" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control lux-input" placeholder="123" maxlength="4" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lux-primary w-100 btn-lg" id="payNowBtn">
                            <i class="fas fa-credit-card me-2"></i>Pay Now
                        </button>
                    </form>

                    <div class="payment-success d-none text-center py-4" id="paymentSuccess">
                        <i class="fas fa-check-circle text-gold fa-4x mb-3"></i>
                        <h3>Payment Successful!</h3>
                        <p class="text-muted" id="paySuccessMsg">Your booking is now confirmed.</p>
                        <a href="<?= $pagePath ?>/booking-history.php" class="btn btn-lux-primary mt-2">View Booking History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function(){
    // Read booking_id from URL query
    const params    = new URLSearchParams(window.location.search);
    const bookingId = params.get('booking_id');

    function loadBookingDetails(){
        if(!bookingId || !window.LuxeApi || !window.LuxeApi.getToken()) return;

        window.LuxeApi.get('/bookings').then(function(data){
            const bks     = data.bookings||[];
            const booking = bks.find(function(b){ return String(b.id) === String(bookingId); });
            if(!booking) return;
            document.getElementById('paymentAmount').textContent  = '$'+Number(booking.total_amount).toLocaleString();
            document.getElementById('paymentBookingRef').textContent = 'Booking #'+booking.booking_ref;
            document.getElementById('hiddenBookingId').value      = bookingId;
        }).catch(function(){});
    }

    loadBookingDetails();

    document.getElementById('paymentForm').addEventListener('submit', function(e){
        e.preventDefault();
        if(!bookingId){
            window.showToast && window.showToast('No booking selected','error');
            return;
        }
        const btn = document.getElementById('payNowBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing…';

        window.LuxeApi.post('/payments/process', {
            booking_id:     bookingId,
            card_number:    document.getElementById('cardNumber').value,
            payment_method: document.querySelector('input[name="payMethod"]:checked').value,
        }).then(function(data){
            document.getElementById('paymentForm').classList.add('d-none');
            document.getElementById('paymentSuccess').classList.remove('d-none');
            document.getElementById('paySuccessMsg').textContent =
                'Transaction ID: ' + (data.transaction_id||'') + '. Your booking is now confirmed!';
        }).catch(function(err){
            window.showToast && window.showToast(err.message||'Payment failed','error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay Now';
        });
    });

    // Card number formatting
    const cardInput = document.getElementById('cardNumber');
    if(cardInput){
        cardInput.addEventListener('input', function(){
            let v = this.value.replace(/\D/g,'').slice(0,16);
            this.value = v.replace(/(.{4})/g,'$1 ').trim();
        });
    }
})();
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
