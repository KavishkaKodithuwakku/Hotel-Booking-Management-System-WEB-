<?php
$pageTitle = 'Contact Support';
$bodyClass = 'page-contact';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="page-hero page-hero-sm">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <h1 class="page-hero-title">Contact <span class="text-gold">Support</span></h1>
        <p class="page-hero-subtitle">We're here to help 24/7</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="contact-info lux-card p-4 h-100">
                    <h4 class="mb-4">Get in Touch</h4>
                    <div class="contact-item mb-4">
                        <div class="contact-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <strong>Phone</strong>
                            <p class="mb-0 text-muted">+1 (800) LUXE-STAY</p>
                        </div>
                    </div>
                    <div class="contact-item mb-4">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <strong>Email</strong>
                            <p class="mb-0 text-muted">support@luxestay.com</p>
                        </div>
                    </div>
                    <div class="contact-item mb-4">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <strong>Head Office</strong>
                            <p class="mb-0 text-muted">100 Park Avenue, New York, NY 10017</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <strong>Hours</strong>
                            <p class="mb-0 text-muted">24/7 Customer Support</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="lux-card p-4 p-md-5">
                    <h4 class="mb-4">Send us a Message</h4>
                    <form id="contactForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control lux-input" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control lux-input" name="email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subject *</label>
                                <select class="form-select lux-input" name="subject" required>
                                    <option value="">Select a topic</option>
                                    <option value="booking">Booking Inquiry</option>
                                    <option value="cancellation">Cancellation</option>
                                    <option value="payment">Payment Issue</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control lux-input" name="message" rows="5" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-lux-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mt-5 pt-5">
            <h3 class="text-center section-title mb-5">Frequently Asked <span class="text-gold">Questions</span></h3>
            <div class="accordion lux-accordion" id="faqAccordion">
                <?php
                $faqs = [
                    ['q' => 'How do I modify my booking?', 'a' => 'Log in to your account, go to Booking History, and select the booking you wish to modify. Contact support for complex changes.'],
                    ['q' => 'What is the cancellation policy?', 'a' => 'Cancellation policies vary by hotel. Free cancellation is available on select properties up to 48 hours before check-in.'],
                    ['q' => 'Is my payment information secure?', 'a' => 'Yes. We use industry-standard encryption. This demo does not process real payments.'],
                ];
                foreach ($faqs as $i => $faq):
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                            <?= $faq['q'] ?>
                        </button>
                    </h2>
                    <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                        <div class="accordion-body"><?= $faq['a'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/components/footer.php'; ?>
