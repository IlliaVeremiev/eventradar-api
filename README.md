# EventRadar API

Backend for [EventRadar UI](https://github.com/IlliaVeremiev/eventradar-ui). Discovers and serves local events by
combining web search, AI-powered content extraction, and a REST API.

## How it works

1. **Search** — [SearXNG](https://searxng.github.io/searxng/) finds relevant event URLs for a given location
2. **Scrape** — [Firecrawl](https://www.firecrawl.dev/) fetches each page and converts it to clean Markdown
3. **Extract** — An LLM (Gemini via [Prism PHP](https://prism.echolabs.dev/)) parses the Markdown and pulls out
   structured event data
4. **Store** — Events are persisted in PostgreSQL and served through a paginated REST API

## Tech Stack

- **PHP 8.5** / **Laravel 12**
- **PostgreSQL** — primary database
- **Redis** — queue, cache, sessions
- **Prism PHP** — LLM abstraction layer (Gemini)
- **Firecrawl** — web scraping to Markdown
- **SearXNG** — self-hosted meta search engine
- **Filament** — admin panel
- **Pest** — testing

## API Endpoints

| Method | Endpoint           | Description                        |
|--------|--------------------|------------------------------------|
| GET    | `/api/events`      | Search & filter events (paginated) |
| GET    | `/api/events/{id}` | Get event by ID                    |

Query parameters for `/api/events`: `query`, `place`, `date`, `future`, `page`, `size`

## Getting Started

### Prerequisites

- PHP 8.5+
- Composer
- Docker (for PostgreSQL and Redis)
- A running SearXNG instance
- A running Firecrawl instance
- Gemini API key

### 1. Clone & install

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure environment

Edit `.env` and fill in the required values:

```env
# Database
DB_DATABASE=eventradar
DB_USERNAME=eventradar
DB_PASSWORD=eventradar

# Redis
REDIS_PASSWORD=null

# External services
SEARXNG_URL=http://localhost:8080
FIRECRAWL_URL=http://localhost:3002
GEMINI_API_KEY=your_key_here
```

### 3. Start the database and Redis

```bash
docker compose up -d
```

This starts PostgreSQL 18 and Redis 8 with the credentials from your `.env`.

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the dev server

```bash
composer dev
```

This runs the Laravel server, queue worker, and Vite concurrently.
