-- LuxeStay — Seed data (run after schema.sql)
USE luxestay_db;

-- Admin: admin@luxestay.com / admin123
-- Customer demo: user@luxestay.com / user123
INSERT INTO users (first_name, last_name, email, password, phone, role, loyalty_tier, status) VALUES
('Admin', 'User', 'admin@luxestay.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 800 0001', 'super_admin', 'Platinum', 'active'),
('Sarah', 'Mitchell', 'user@luxestay.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0101', 'customer', 'Gold', 'active'),
('James', 'Chen', 'james@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0102', 'customer', 'Silver', 'active'),
('Emma', 'Wilson', 'emma@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+44 7700 900123', 'customer', 'Gold', 'active');

-- password hash above is bcrypt for "password" - we'll use install script to set admin123
-- Hotels
INSERT INTO hotels (name, location, address, lat, lng, destination, image, description, rating, reviews_count, price_from, stars, status) VALUES
('Grand Luxe Resort', 'Paris, France', '12 Avenue des Champs-Élysées, 75008 Paris, France', 48.8698, 2.3073, 'paris', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80', 'Experience unparalleled luxury in the heart of Paris.', 4.90, 412, 349.00, 5, 'active'),
('Azure Palm Dubai', 'Dubai, UAE', 'Sheikh Zayed Road, Downtown Dubai, UAE', 25.1972, 55.2744, 'dubai', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80', 'Iconic luxury tower with desert views.', 4.80, 289, 429.00, 5, 'active'),
('Ocean Pearl Maldives', 'Maldives', 'North Male Atoll, Maldives', 4.7060, 73.3287, 'maldives', 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80', 'Overwater villas in paradise.', 4.90, 156, 599.00, 5, 'active'),
('Tokyo Imperial Tower', 'Tokyo, Japan', '1-1-1 Uchisaiwaicho, Chiyoda City, Tokyo', 35.6745, 139.7595, 'tokyo', 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&q=80', 'Modern elegance in central Tokyo.', 4.70, 198, 279.00, 4, 'active'),
('Manhattan Elite Suites', 'New York, USA', '768 5th Avenue, New York, NY 10019', 40.7648, -73.9748, 'new-york', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80', 'Fifth Avenue luxury living.', 4.60, 334, 389.00, 5, 'active'),
('Riviera Palace Nice', 'Nice, France', '15 Promenade des Anglais, 06000 Nice', 43.6959, 7.2654, 'paris', 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80', 'Mediterranean glamour on the Riviera.', 4.50, 87, 219.00, 4, 'active'),
('Sahara Oasis Resort', 'Marrakech, Morocco', 'Avenue Mohammed VI, Marrakech', 31.6295, -7.9811, 'dubai', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80', 'Moroccan palace with spa sanctuary.', 4.40, 112, 189.00, 4, 'active'),
('Alpine Crown Chalet', 'Zermatt, Switzerland', 'Bahnhofstrasse 55, 3920 Zermatt', 46.0207, 7.7491, 'paris', 'https://images.unsplash.com/photo-1578683010236-d716f9a3fdf7?w=800&q=80', 'Alpine luxury at the foot of the Matterhorn.', 4.80, 76, 459.00, 5, 'active');

INSERT INTO hotel_amenities (hotel_id, amenity) VALUES
(1,'pool'),(1,'spa'),(1,'wifi'),(2,'pool'),(2,'spa'),(2,'wifi'),(3,'pool'),(3,'spa'),
(4,'wifi'),(4,'spa'),(5,'wifi'),(5,'pool'),(6,'pool'),(6,'wifi'),(7,'spa'),(7,'wifi'),(8,'spa'),(8,'wifi');

-- Rooms
INSERT INTO rooms (hotel_id, room_code, name, room_type, size_sqm, beds, price_per_night, capacity, image, status) VALUES
(1, 'R101', 'Deluxe King Room', 'Deluxe', '45 m²', '1 King Bed', 349.00, 2, 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80', 'available'),
(1, 'R102', 'Executive Suite', 'Suite', '72 m²', '1 King + Living', 549.00, 2, 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80', 'occupied'),
(2, 'R201', 'Royal Villa', 'Villa', '120 m²', '2 King Beds', 899.00, 4, 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80', 'available'),
(3, 'R301', 'Overwater Bungalow', 'Bungalow', '80 m²', '1 King Bed', 599.00, 2, 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=600&q=80', 'occupied');

INSERT INTO room_amenities (room_id, amenity) VALUES
(1,'WiFi'),(1,'Mini Bar'),(1,'City View'),(2,'WiFi'),(2,'Butler'),(2,'Eiffel View');

-- Sample bookings & payments
INSERT INTO bookings (booking_ref, user_id, hotel_id, room_id, check_in, check_out, guests, total_amount, status) VALUES
('BK-2026-4521', 2, 1, 1, '2026-06-01', '2026-06-04', 2, 1047.00, 'confirmed'),
('BK-2026-4518', 3, 2, 3, '2026-05-28', '2026-06-02', 2, 2145.00, 'pending'),
('BK-2026-4512', 4, 3, 4, '2026-06-15', '2026-06-20', 2, 3594.00, 'confirmed');

INSERT INTO payments (transaction_id, booking_id, user_id, amount, payment_method, card_last4, status, paid_at) VALUES
('TXN-88421', 1, 2, 1047.00, 'visa', '4242', 'paid', NOW()),
('TXN-88418', 2, 3, 2145.00, 'mastercard', '5555', 'pending', NULL),
('TXN-88409', 3, 4, 3594.00, 'paypal', NULL, 'paid', NOW());

INSERT INTO notifications (audience, channel, subject, message, status, sent_at) VALUES
('all', 'email,in_app', 'Summer Sale — 20% Off', 'Book your luxury escape with exclusive summer rates.', 'sent', NOW()),
('recent_bookers', 'email', 'Booking Confirmation Reminder', 'Please review your upcoming reservation details.', 'sent', NOW());

INSERT INTO reviews (hotel_id, user_id, rating, comment, status) VALUES
(1, 2, 5, 'Stunning property with impeccable service.', 'approved'),
(1, 3, 5, 'Every detail was perfect. Will return.', 'approved');
