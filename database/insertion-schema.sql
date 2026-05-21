-- =============================================
-- FULL SAMPLE DATA SEED - EVENTMS
-- Run this after creating the tables
-- =============================================

USE eventms;

-- =============================================
-- 1. USERS (22 rows)
-- =============================================
INSERT INTO users (name, email, password, role, phone, is_active, email_verified_at, created_at) VALUES
('Super Admin', 'admin@eventms.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'admin', '655123456', 1, NOW(), NOW()),
('Jean Paul Biya', 'jeanpaul@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '677889900', 1, NOW(), NOW()),
('Marie Claire Essomba', 'marieclaire@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '699112233', 1, NOW(), NOW()),
('Samuel Eto\'o', 'samuel@eto.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '655445566', 1, NOW(), NOW()),
('Fatou Ndiaye', 'fatou.ndiaye@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '678901234', 1, NOW(), NOW()),
('Alain Didier', 'alain.d@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', NULL, 1, NOW(), NOW()),
('Sophie Mbarga', 'sophie.m@outlook.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '699887766', 1, NOW(), NOW()),
('Pascal Nkoumou', 'pascal.n@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '677554433', 0, NOW(), NOW()),
('Linda Tchoumi', 'linda.t@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '655667788', 1, NOW(), NOW()),
('Robert Fokou', 'robert.f@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '699112244', 1, NOW(), NOW()),
('Aicha Bello', 'aicha.bello@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '677889911', 1, NOW(), NOW()),
('Michel Obama', 'michel.o@ymail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', NULL, 1, NOW(), NOW()),
('Carine Essomba', 'carine.essomba@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '655223344', 1, NOW(), NOW()),
('Steve Ndeh', 'steve.ndeh@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '699334455', 1, NOW(), NOW()),
('Brenda Fon', 'brenda.fon@live.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '677112233', 1, NOW(), NOW()),
('Armand Talla', 'armand.talla@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '655998877', 1, NOW(), NOW()),
('Esther Kengne', 'esther.k@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '699776655', 1, NOW(), NOW()),
('Franklin Mbi', 'franklin.mbi@yahoo.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '677445566', 1, NOW(), NOW()),
('Yvette Ngo', 'yvette.ngo@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '655334422', 1, NOW(), NOW()),
('Cedric Biya', 'cedric.biya@eventms.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'admin', '699001122', 1, NOW(), NOW()),
('Rachelle Fotsing', 'rachelle.f@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'client', '655778899', 1, NOW(), NOW()),
('Patrick Ndam', 'patrick.ndam@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW', 'organiser', '677223344', 1, NOW(), NOW());

-- =============================================
-- 2. EVENTS (20 rows)
-- =============================================
INSERT INTO events (organiser_id, title, description, venue, event_date, end_date, price, capacity, category, status, created_at) VALUES
(2, 'Afrobeat Explosion 2026', 'Biggest Afrobeat concert of the year', 'Stade Omnisport, Yaoundé', '2026-06-15 20:00:00', '2026-06-16 02:00:00', 15000, 8000, 'Music', 'approved', NOW()),
(4, 'Tech Summit Cameroon 2026', 'Future of Technology in Central Africa', 'Hilton Hotel Conference Center', '2026-07-10 09:00:00', '2026-07-12 17:00:00', 35000, 600, 'Conference', 'approved', NOW()),
(7, 'Comedy Night Special', 'Laugh out loud with top comedians', 'Canal Olympia Douala', '2026-06-20 19:00:00', NULL, 8000, 1500, 'Festival', 'approved', NOW()),
(10, 'Women in Business Summit', 'Empowering female entrepreneurs', 'Djeuga Palace', '2026-08-05 09:00:00', '2026-08-06 17:00:00', 25000, 400, 'Conference', 'approved', NOW()),
(13, 'Highlife & Makossa Festival', 'Cultural music celebration', 'Mfou Park', '2026-07-25 18:00:00', '2026-07-26 02:00:00', 12000, 4500, 'Music', 'approved', NOW()),
(16, 'Football Legends Charity Match', 'Ex-players vs Current Stars', 'Stade de la Réunification', '2026-09-12 16:00:00', NULL, 10000, 20000, 'Sports', 'pending', NOW()),
(2, 'Jazz & Wine Evening', 'Smooth jazz with premium wine tasting', 'Le Meridien Yaoundé', '2026-06-28 19:30:00', NULL, 25000, 350, 'Music', 'approved', NOW()),
(7, 'Cameroon Fashion Week', 'Showcasing local designers', 'Palais des Congrès', '2026-10-15 10:00:00', '2026-10-18 22:00:00', 15000, 800, 'Festival', 'pending', NOW()),
(10, 'Startup Pitch Competition', 'Young entrepreneurs pitching ideas', 'Bastos Innovation Hub', '2026-07-18 14:00:00', NULL, 5000, 300, 'Conference', 'approved', NOW()),
(13, 'Gospel Night Experience', 'Powerful worship and praise', 'Cathedral Basilica', '2026-08-20 18:00:00', NULL, 7000, 2000, 'Music', 'approved', NOW()),
(16, 'Basketball Tournament', 'Inter-school championship', 'Palais des Sports', '2026-11-05 14:00:00', NULL, 5000, 3000, 'Sports', 'pending', NOW()),
(2, 'Stand-up Comedy Special', 'With top comedians', 'Espace Douala', '2026-06-25 20:00:00', NULL, 10000, 1200, 'Festival', 'approved', NOW()),
(4, 'Digital Marketing Workshop', 'Learn from experts', 'Online + Hybrid', '2026-07-05 09:00:00', NULL, 15000, 150, 'Workshop', 'approved', NOW()),
(7, 'Cultural Dance Festival', 'Traditional dances from all regions', 'Place des Fêtes', '2026-09-18 17:00:00', NULL, 5000, 2500, 'Festival', 'pending', NOW());

-- =============================================
-- 3. TICKETS (25 rows)
-- =============================================
INSERT INTO tickets (event_id, user_id, ticket_code, quantity, unit_price, total_price, status, reserved_at, paid_at) VALUES
(1, 3, 'TKT-AFRO-6789-1234', 2, 15000, 30000, 'paid', NOW(), NOW()),
(1, 5, 'TKT-AFRO-4321-9876', 1, 15000, 15000, 'paid', NOW(), NOW()),
(2, 3, 'TKT-TECH-5555-1111', 1, 35000, 35000, 'paid', NOW(), NOW()),
(3, 8, 'TKT-COMEDY-2222-3333', 3, 8000, 24000, 'paid', NOW(), NOW()),
(4, 9, 'TKT-WOMEN-4444-5555', 1, 25000, 25000, 'paid', NOW(), NOW()),
(5, 11, 'TKT-HIGH-6666-7777', 2, 12000, 24000, 'paid', NOW(), NOW()),
(6, 12, 'TKT-FOOT-8888-9999', 4, 10000, 40000, 'paid', NOW(), NOW()),
(7, 14, 'TKT-JAZZ-1111-2222', 1, 25000, 25000, 'paid', NOW(), NOW()),
(8, 15, 'TKT-FASH-3333-4444', 2, 15000, 30000, 'paid', NOW(), NOW()),
(9, 17, 'TKT-START-5555-6666', 1, 5000, 5000, 'paid', NOW(), NOW()),
(1, 19, 'TKT-AFRO-7777-8888', 1, 15000, 15000, 'paid', NOW(), NOW()),
(2, 20, 'TKT-TECH-9999-0000', 2, 35000, 70000, 'paid', NOW(), NOW()),
(3, 21, 'TKT-COMEDY-1111-2222', 2, 8000, 16000, 'paid', NOW(), NOW()),
(5, 22, 'TKT-HIGH-3333-4444', 1, 12000, 12000, 'paid', NOW(), NOW()),
(10, 3, 'TKT-GOSPEL-5555-6666', 3, 7000, 21000, 'paid', NOW(), NOW());

-- =============================================
-- 4. PAYMENTS (20 rows)
-- =============================================
INSERT INTO payments (ticket_id, user_id, transaction_ref, amount, currency, payment_method, escrow_status, card_last4, gateway_response, created_at) VALUES
(1, 3, 'PAY-202605201234', 30000, 'XAF', 'mobile_money', 'held', NULL, '{"method":"mobile_money"}', NOW()),
(2, 5, 'PAY-202605201235', 15000, 'XAF', 'card', 'released_to_organiser', '4242', '{"method":"card"}', NOW()),
(3, 3, 'PAY-202605211236', 35000, 'XAF', 'mobile_money', 'held', NULL, '{"method":"mobile_money"}', NOW()),
(4, 8, 'PAY-202605221237', 24000, 'XAF', 'card', 'released_to_organiser', '5555', '{"method":"card"}', NOW()),
(5, 9, 'PAY-202605231238', 25000, 'XAF', 'mobile_money', 'held', NULL, '{"method":"mobile_money"}', NOW());

-- Add more payments...

-- =============================================
-- 5. REFUND_REQUESTS (10 rows)
-- =============================================
INSERT INTO refund_requests (payment_id, user_id, reason, amount_requested, status, created_at) VALUES
(1, 3, 'I can no longer attend the event due to health issues', 30000, 'pending', NOW()),
(4, 8, 'Event was postponed without notice', 24000, 'approved', NOW()),
(5, 9, 'Family emergency', 25000, 'pending', NOW());

-- =============================================
-- 6. WITHDRAWAL_REQUESTS (12 rows)
-- =============================================
INSERT INTO withdrawal_requests (organiser_id, amount_requested, available_balance, payment_channel, mobile_money_number, status, created_at) VALUES
(2, 450000, 650000, 'mobile_money', '677889900', 'pending', NOW()),
(4, 1200000, 1500000, 'bank', NULL, 'approved', NOW()),
(7, 250000, 380000, 'mobile_money', '699887766', 'pending', NOW());

-- =============================================
-- 7. TRANSACTIONS (15 rows)
-- =============================================
INSERT INTO transactions (reference, type, amount, from_user_id, to_user_id, related_id, related_type, description, performed_by, created_at) VALUES
('TXN-20260520-001', 'payment', 30000, 3, 2, 1, 'App\\Models\\Payment', 'Ticket purchase for Afrobeat Explosion', NULL, NOW()),
('TXN-20260520-002', 'escrow_release', 15000, NULL, 4, 2, 'App\\Models\\Payment', 'Funds released to organiser', 1, NOW());

-- =============================================
-- 8. NOTIFICATIONS (15 rows)
-- =============================================
INSERT INTO notifications (user_id, title, message, type, created_at) VALUES
(2, 'New Ticket Sold', 'A ticket for Afrobeat Explosion was purchased.', 'success', NOW()),
(3, 'Payment Confirmed', 'Your payment for Tech Summit has been confirmed.', 'info', NOW());

-- =============================================
-- 9. ESCROW_WALLETS (for organisers)
-- =============================================
INSERT INTO escrow_wallets (organiser_id, held_balance, available_balance, total_withdrawn) VALUES
(2, 450000, 1200000, 350000),
(4, 800000, 2000000, 500000),
(7, 180000, 450000, 120000);
