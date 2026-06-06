-- Elimina le tabelle se esistono già, nell'ordine corretto
-- (prima bookings perché ha una foreign key verso rooms)
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;

-- ── Tabella rooms ─────────────────────────────────────────────

CREATE TABLE rooms (
    id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name           VARCHAR(100)    NOT NULL,
    description    TEXT            NOT NULL,
    price_per_night DECIMAL(8,2)   NOT NULL,
    capacity       TINYINT UNSIGNED NOT NULL DEFAULT 1,
    size_sqm       DECIMAL(5,1)    NOT NULL,
    amenities      TEXT            NULL,
    image          VARCHAR(255)    NULL,
    is_active      TINYINT(1)      NOT NULL DEFAULT 1,
    created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                   ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tabella bookings ──────────────────────────────────────────

CREATE TABLE bookings (
    id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    room_id        INT UNSIGNED    NOT NULL,
    token          VARCHAR(32)     NOT NULL,
    first_name     VARCHAR(100)    NOT NULL,
    last_name      VARCHAR(100)    NOT NULL,
    email          VARCHAR(150)    NOT NULL,
    phone          VARCHAR(20)     NULL,
    check_in       DATE            NOT NULL,
    check_out      DATE            NOT NULL,
    guests         TINYINT UNSIGNED NOT NULL DEFAULT 1,
    total_price    DECIMAL(10,2)   NOT NULL,
    status         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
    notes          TEXT            NULL,
    created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                   ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE  KEY uk_token        (token),
    INDEX        idx_room_id    (room_id),
    INDEX        idx_status     (status),
    INDEX        idx_check_in   (check_in),

    CONSTRAINT fk_bookings_room
        FOREIGN KEY (room_id) REFERENCES rooms (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Dati di esempio ───────────────────────────────────────────

INSERT INTO rooms (name, description, price_per_night, capacity, size_sqm, amenities, is_active)
VALUES
    ('Camera Standard',
     'Camera confortevole con vista sul giardino, letto matrimoniale e bagno privato.',
     89.00, 2, 18.0,
     'WiFi,TV,Aria condizionata,Bagno privato',
     1),

    ('Camera Superior',
     'Spaziosa camera con balcone panoramico, letto king-size e dotazioni di lusso.',
     139.00, 2, 26.0,
     'WiFi,TV,Aria condizionata,Bagno privato,Balcone,Minibar',
     1),

    ('Suite Junior',
     'Suite con zona living separata, vasca idromassaggio e vista mare.',
     220.00, 3, 42.0,
     'WiFi,TV,Aria condizionata,Bagno privato,Balcone,Minibar,Vasca idromassaggio,Zona living',
     1),

    ('Camera Singola',
     'Camera compatta e funzionale, ideale per viaggiatori solitari.',
     65.00, 1, 14.0,
     'WiFi,TV,Aria condizionata,Bagno privato',
     1);