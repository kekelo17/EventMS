-- ============================================================
-- EVENT MANAGEMENT SYSTEM - MySQL Schema
-- Compatible with XAMPP / MySQL 5.7+
-- ============================================================

CREATE DATABASE IF NOT EXISTS eventms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventms;

-- ─── USERS ──────────────────────────────────────────────────
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    role            ENUM('client','organiser','admin') NOT NULL DEFAULT 'client',
    phone           VARCHAR(30) NULL,
    avatar          VARCHAR(255) NULL,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    email_verified_at DATETIME NULL,
    remember_token  VARCHAR(100) NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ─── EVENTS ──────────────────────────────────────────────────
CREATE TABLE events (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organiser_id    BIGINT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    description     TEXT NOT NULL,
    venue           VARCHAR(255) NOT NULL,
    event_date      DATETIME NOT NULL,
    end_date        DATETIME NULL,
    price           DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    capacity        INT UNSIGNED NOT NULL DEFAULT 100,
    tickets_sold    INT UNSIGNED NOT NULL DEFAULT 0,
    banner_image    VARCHAR(255) NULL,
    category        VARCHAR(100) NULL,
    status          ENUM('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
    rejection_reason TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organiser_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─── TICKETS ─────────────────────────────────────────────────
CREATE TABLE tickets (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id        BIGINT UNSIGNED NOT NULL,
    user_id         BIGINT UNSIGNED NOT NULL,
    ticket_code     VARCHAR(64) NOT NULL UNIQUE,
    qr_code         TEXT NULL,                   -- base64 QR data or path
    quantity        INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price      DECIMAL(12,2) NOT NULL,
    total_price     DECIMAL(12,2) NOT NULL,
    status          ENUM('reserved','paid','cancelled','refunded','used') NOT NULL DEFAULT 'reserved',
    reserved_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paid_at         DATETIME NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─── PAYMENTS (Escrow) ───────────────────────────────────────
CREATE TABLE payments (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id           BIGINT UNSIGNED NOT NULL,
    user_id             BIGINT UNSIGNED NOT NULL,
    transaction_ref     VARCHAR(100) NOT NULL UNIQUE,    -- simulated gateway ref
    amount              DECIMAL(12,2) NOT NULL,
    currency            VARCHAR(10) NOT NULL DEFAULT 'XAF',
    payment_method      ENUM('card','mobile_money','bank_transfer') NOT NULL DEFAULT 'card',
    -- Escrow States
    escrow_status       ENUM('held','released_to_organiser','refunded_to_client','partial_refund') NOT NULL DEFAULT 'held',
    -- Gateway simulation fields
    card_last4          CHAR(4) NULL,
    card_brand          VARCHAR(30) NULL,
    gateway_response    JSON NULL,
    -- Admin action
    released_by         BIGINT UNSIGNED NULL,
    released_at         DATETIME NULL,
    refunded_by         BIGINT UNSIGNED NULL,
    refunded_at         DATETIME NULL,
    refund_amount       DECIMAL(12,2) NULL DEFAULT 0.00,
    notes               TEXT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id)   REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (released_by) REFERENCES users(id)   ON DELETE SET NULL,
    FOREIGN KEY (refunded_by) REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

-- ─── REFUND REQUESTS ─────────────────────────────────────────
CREATE TABLE refund_requests (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id      BIGINT UNSIGNED NOT NULL,
    user_id         BIGINT UNSIGNED NOT NULL,
    reason          TEXT NOT NULL,
    amount_requested DECIMAL(12,2) NOT NULL,
    status          ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    admin_note      TEXT NULL,
    processed_by    BIGINT UNSIGNED NULL,
    processed_at    DATETIME NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id)    REFERENCES payments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)       REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (processed_by)  REFERENCES users(id)    ON DELETE SET NULL
) ENGINE=InnoDB;

-- ─── WITHDRAWAL REQUESTS ─────────────────────────────────────
CREATE TABLE withdrawal_requests (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organiser_id    BIGINT UNSIGNED NOT NULL,
    event_id        BIGINT UNSIGNED NULL,          -- specific event or NULL for all
    amount_requested DECIMAL(12,2) NOT NULL,
    available_balance DECIMAL(12,2) NOT NULL,      -- snapshot at request time
    bank_name       VARCHAR(100) NULL,
    account_number  VARCHAR(100) NULL,
    account_name    VARCHAR(100) NULL,
    mobile_money_number VARCHAR(30) NULL,
    payment_channel ENUM('bank','mobile_money') NOT NULL DEFAULT 'mobile_money',
    status          ENUM('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
    admin_note      TEXT NULL,
    processed_by    BIGINT UNSIGNED NULL,
    processed_at    DATETIME NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organiser_id)  REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (event_id)      REFERENCES events(id)  ON DELETE SET NULL,
    FOREIGN KEY (processed_by)  REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

-- ─── TRANSACTIONS LOG ────────────────────────────────────────
CREATE TABLE transactions (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference       VARCHAR(100) NOT NULL,
    type            ENUM('payment','refund','withdrawal','escrow_release') NOT NULL,
    amount          DECIMAL(12,2) NOT NULL,
    from_user_id    BIGINT UNSIGNED NULL,
    to_user_id      BIGINT UNSIGNED NULL,
    related_id      BIGINT UNSIGNED NULL,          -- payment_id / refund_request_id etc
    related_type    VARCHAR(100) NULL,              -- 'App\Models\Payment' etc
    status          ENUM('pending','completed','failed','reversed') NOT NULL DEFAULT 'pending',
    description     TEXT NULL,
    performed_by    BIGINT UNSIGNED NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (to_user_id)   REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ─── NOTIFICATIONS ───────────────────────────────────────────
CREATE TABLE notifications (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    message         TEXT NOT NULL,
    type            VARCHAR(50) NOT NULL DEFAULT 'info',  -- info, success, warning, danger
    is_read         TINYINT(1) NOT NULL DEFAULT 0,
    link            VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─── ESCROW WALLET (Virtual per organiser) ───────────────────
CREATE TABLE escrow_wallets (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organiser_id    BIGINT UNSIGNED NOT NULL UNIQUE,
    held_balance    DECIMAL(12,2) NOT NULL DEFAULT 0.00,   -- money in escrow
    available_balance DECIMAL(12,2) NOT NULL DEFAULT 0.00, -- released, can withdraw
    total_withdrawn DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organiser_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─── INDEXES ─────────────────────────────────────────────────
CREATE INDEX idx_events_status       ON events(status);
CREATE INDEX idx_events_organiser    ON events(organiser_id);
CREATE INDEX idx_tickets_user        ON tickets(user_id);
CREATE INDEX idx_tickets_event       ON tickets(event_id);
CREATE INDEX idx_payments_escrow     ON payments(escrow_status);
CREATE INDEX idx_transactions_ref    ON transactions(reference);

-- ─── SEED: Default Admin ─────────────────────────────────────
-- Password: Admin@1234  (bcrypt hash)
INSERT INTO users (name, email, password, role, is_active, email_verified_at) VALUES
('Super Admin', 'admin@eventms.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uRPY85OVW',
 'admin', 1, NOW());

INSERT INTO escrow_wallets (organiser_id, held_balance, available_balance)
SELECT id, 0.00, 0.00 FROM users WHERE role='admin';
