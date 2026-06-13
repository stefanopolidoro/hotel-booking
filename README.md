# HotelBooking

Sistema di prenotazione camere in PHP puro, costruito come progetto didattico
per imparare il pattern MVC senza framework.

## Stack

- PHP 8.1+
- MySQL 8
- Composer (autoloader PSR-4)
- Laravel Herd (sviluppo locale)
- CSS puro, nessuna dipendenza frontend

## Requisiti

- PHP >= 8.1 con estensioni: `pdo`, `pdo_mysql`, `mbstring`, `fileinfo`
- MySQL >= 8.0
- Composer
- Un web server con mod_rewrite abilitato (Apache) o equivalente

## Installazione

### 1. Clona il repository

```bash
git clone https://github.com/tuonome/hotel-booking.git
cd hotel-booking
```

### 2. Installa le dipendenze

```bash
composer install
```

### 3. Crea il database e importa lo schema

```bash
mysql -u root -p -e "CREATE DATABASE hotel_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p hotel_booking < database/schema.sql
```

### 4. Configura il progetto

```bash
cp config/config.php.example config/config.php
```

Modifica `config/config.php` con i tuoi valori:

```php
define('APP_URL',     'https://hotel-booking.test');
define('APP_ENV',     'development');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('ADMIN_EMAIL', 'admin@hotel.test');
define('ADMIN_PASSWORD', '...');  // genera con: php -r "echo password_hash('tuapassword', PASSWORD_BCRYPT);"
```

### 5. Crea la cartella dei log

```bash
mkdir -p storage/logs
```

### 6. Configura il web server

Con **Laravel Herd**: aggiungi il progetto come sito da `~/Herd/hotel-booking`.

Con **Apache** tradizionale: punta il `DocumentRoot` alla cartella `public/`.

## Struttura del progetto

```
hotel-booking/
├── app/                  ← applicazione (Controllers, Models, Views)
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── src/                  ← mini-framework riusabile
│   ├── Core/             ← Database, Router, BaseModel, BaseController, helpers
│   └── Exceptions/
├── config/               ← configurazione (esclusa da git)
├── database/             ← schema SQL
├── public/               ← document root
│   ├── assets/
│   └── index.php
└── storage/logs/         ← log degli errori (esclusi da git)
```

## Funzionalità

**Lato pubblico**
- Home con form di ricerca per date e numero ospiti
- Lista camere disponibili con filtro per date
- Dettaglio camera con calcolo totale in tempo reale
- Form prenotazione con validazione server-side
- Pagina di conferma con token univoco

**Pannello admin** — `/admin/login`
- Autenticazione con sessione e password BCrypt
- Dashboard con statistiche e prenotazioni recenti
- CRUD camere con upload foto
- Lista prenotazioni con filtri per stato e ricerca
- Dettaglio prenotazione e cambio stato

## Sicurezza implementata

- Protezione CSRF su tutti i form POST
- Output sempre escapato con `e()` nelle view
- Prepared statements PDO su tutte le query
- Validazione tipo MIME reale sugli upload
- `password_hash` BCrypt per le credenziali admin
- `session_regenerate_id()` al login
- Header HTTP di sicurezza su ogni risposta
- Cookie di sessione `HttpOnly` e `SameSite=Lax`

## Convenzioni del codice

- `declare(strict_types=1)` in ogni file PHP
- Namespace PSR-4: `App\` per `app/`, `Src\` per `src/`
- Commit con Conventional Commits (`feat:`, `fix:`, `refactor:`, `docs:`)
