# Suplement Store — Online prodavnica suplemenata za teretanu

Aplikacija za online prodaju suplemenata za teretanu, kreirana kao projekat iz predmeta Web programiranje. Implementirana je MVC arhitektura u čistom PHP-u sa Twig templating sistemom.

---

## Funkcionalnosti

- Pregled proizvoda po kategorijama i brendovima sa pretragom i prikazom podataka po stranicama
- Tri nivoa korisnika: **Admin**, **Menadžer** i **Korisnik**
- **Guest** korisnici mogu pregledati proizvode bez registracije
- **Admin** — CRUD operacije nad proizvodima, kategorijama, brendovima i korisnicima, izvoz izveštaja u Excel i PDF
- **Menadžer** — upravljanje narudžbinama i korisnicima, odgovaranje na poruke
- **Korisnik** — korpa, narudžbine, slanje poruka
- Logovanje korisničkih akcija u log fajl

---

## Tehnologije

- PHP 8.3
- MySQL
- Apache (WAMP)
- Twig templating sistem
- Bootstrap 5
- Composer
- PHPUnit
- Docker

---

## Preduslovi

- Instaliran i pokrenut WAMP
- Instaliran Composer
- Instaliran Docker Desktop (opciono)

---

## Pokretanje projekta

### 1. Kloniranje repozitorijuma

```bash
git clone https://github.com/mladenm11111/suplement-store.git
```

Zatim projekat postavite u folder `C:\wamp64\www\`.

### 2. Kreiranje baze podataka

Pokrenite WAMP i otvorite phpMyAdmin na adresi `http://localhost/phpmyadmin`. U sekciji SQL pokrenite upit iz fajla `database/suplement_store.sql`.

### 3. Podešavanje konfiguracije

U folderu `app/config/` kopirajte fajl `config-example.php` i preimenujte ga u `config.php`. Zatim podesite parametre:

```php
<?php

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'suplement_store';
const DB_PORT = '3306';
const BASE_URL = 'http://localhost/suplement-store/public/';
```

### 4. Instalacija zavisnosti

```bash
composer install
```

### 5. Pokretanje aplikacije

Otvorite browser i idite na `http://localhost/suplement-store/public/`.

---

## Pokretanje u Dockeru

```bash
docker-compose up --build
```

Aplikacija će biti dostupna na `http://localhost:8080`.

---

## Podrazumevani admin nalog

Nakon kreiranja baze, dodajte admin korisnika pokretanjem sledećeg SQL upita u phpMyAdmin-u:

```sql
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

- Email: `admin@admin.com`
- Lozinka: `password`

---

## Pokretanje testova

```bash
php vendor/bin/phpunit
```