# Stop Kamenolomu - Bukov u Hořoviček (Laravel)

Website for the nature preservation campaign against planned quarry development on Tobiášův vrch near Bukov u Hořoviček, Czech Republic.

## Stack

- Laravel 12 (PHP 8.2+)
- Blade templates
- Tailwind CSS (CDN)
- Alpine.js
- JSON-driven news data (`public/data/news.json`)

## Local Run

1. Install dependencies:

```bash
composer install
```

2. Create environment file:

```bash
cp .env.example .env
php artisan key:generate
```

3. Start the app:

```bash
php artisan serve
```

App runs at `http://127.0.0.1:8000`.

## Routes

- `/` - Homepage
- `/about` - O projektu
- `/news` - Aktuality
- `/petition` - Petice
- `/contact` - Kontakt

Legacy URLs with `.html` are redirected with `301`:

- `/index.html`
- `/about.html`
- `/news.html`
- `/petition.html`
- `/contact.html`

## Project Structure

- Views: `resources/views/*.blade.php`
- Static assets: `public/css`, `public/js`, `public/images`
- News data: `public/data/news.json`

## Adding News

Edit `public/data/news.json` and add a new entry:

```json
{
  "id": "unique-slug",
  "title": "Název článku",
  "date": "2025-12-06",
  "excerpt": "Krátký popis...",
  "image": "/images/news-placeholder.svg",
  "content": "Celý text článku...",
  "link": "https://optional-external-link.cz"
}
```
