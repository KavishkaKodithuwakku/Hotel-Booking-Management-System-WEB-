-- Sync migrations — run once after schema.sql (safe to re-run)
USE luxestay_db;

-- Add admin reply to contact messages (ignore errors if column exists)
ALTER TABLE contact_messages
    ADD COLUMN reply_message TEXT DEFAULT NULL;
ALTER TABLE contact_messages
    ADD COLUMN replied_by INT UNSIGNED DEFAULT NULL;
ALTER TABLE contact_messages
    ADD COLUMN replied_at DATETIME DEFAULT NULL;

-- Add admin_reply to reviews (ignore errors if column exists)
ALTER TABLE reviews
    ADD COLUMN admin_reply TEXT DEFAULT NULL;
ALTER TABLE reviews
    ADD COLUMN replied_at DATETIME DEFAULT NULL;

-- Track notification reads
CREATE TABLE IF NOT EXISTS notification_reads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    notification_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_notif_user (notification_id, user_id),
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Activity log
CREATE TABLE IF NOT EXISTS activity_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_id INT UNSIGNED DEFAULT NULL,
    actor_role VARCHAR(30) DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT UNSIGNED DEFAULT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_actor (actor_id)
) ENGINE=InnoDB;
