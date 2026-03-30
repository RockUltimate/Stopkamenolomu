# Stop Kamenolomu - Bukov u Hořoviček

Website for the nature preservation campaign against planned quarry development on Tobiášův vrch near Bukov u Hořoviček, Czech Republic.

## Tech Stack

- **HTML + Tailwind CSS** (CDN) — lightweight, no build step
- **Alpine.js** — reactive UI components
- **Nginx** — Docker container serving static files
- **News data** — JSON-driven articles (`data/news.json`)

## Running Locally

```bash
docker-compose up -d
```

Site will be available at **http://localhost:8085**

## Pages

- **Domů** (`/`) — Homepage with hero, key facts, latest news, and call to action
- **O projektu** (`/about.html`) — Detailed info about the quarry threat, timeline, environmental impact
- **Aktuality** (`/news.html`) — News and blog articles loaded from `data/news.json`
- **Petice** (`/petition.html`) — Petition info, signature count, signing locations
- **Kontakt** (`/contact.html`) — Contact form and addresses

## Adding News

Edit `data/news.json` and add a new entry:

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
