# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

URL shortener written in plain PHP (no Composer, no framework, no tests, no linter). Per the README it is a take-home test assignment, not production code. README is in Russian; user-facing strings are too.

## Running

```shell
php -S localhost:8080        # run from the repo root
```

- The SQLite path is relative (`./App/sqlite.db`), so the server **must be started from the repo root** (cwd matters).
- `config.php` → `app_url` must match the address the server is running on; it is used to build the returned short link.
- nginx/apache need every request rewritten to `index.php` (see README). The built-in server routes to `index.php` only when the URI has no matching file, which works here.
- `App/sqlite.db` is gitignored in `.gitignore` but is currently tracked in git; the `short_links` table is auto-created on first connection (`SqliteDB`).

## Architecture

Single front controller: `index.php` defines a PSR-4-ish `Autoloader` (namespace `App\...` → file path from repo root), registers routes, and calls `Router::route($method, $uri)`.

- `App/Core/Router.php` — custom router. Routes are stored in a map keyed by URI pattern (so the same URI can't be registered for two methods). Exact matches are looked up first; otherwise it scans patterns for a single `{param}` segment with equal segment count and passes `['param' => value]` to the action. Note the param-route branch does not check that the class/method exist, unlike the exact-match branch. Errors are emitted through `Helpers::errorResponse` (echo + `exit`), not exceptions.
- `App/Core/Helpers.php` — `errorResponse` (static), `getConfig`, and `jsonResponse` (wraps payload in an extra array, so the JSON is `[{...}]` — `lib/main.js` reads `data[0]`). It `require`s `config.php`, which sets the global `$config`.
- `App/Controller/ShortUrlController.php` — `show` (serves `view/index.html`), `create` (POST `/api/short_url`, form-encoded `url`; validates with `FILTER_VALIDATE_URL`, dedupes by URL), `get` (GET `/{url_id}`, redirects).
- `App/Models/ShortUrl.php` + `App/Core/SqliteDB.php` — SQLite3 access; the short id is the autoincrement row id (`/1`, `/2`, ...).
- Frontend: `view/index.html`, `lib/main.js` (fetch to the API, clipboard copy), `lib/main.css`.

## Known pitfalls (in existing code)

- SQL is built by string interpolation in `ShortUrl` (injection risk) — use prepared statements if touching it.
- `ShortUrlController::get` reads `$_GET['url']` without `isset` and, if set, redirects to it directly (open redirect); it also calls `http_redirect`, which requires the pecl_http extension and is normally undefined.
- `create` returns `json_encode(...)` error bodies via `errorResponse`, which does not set a JSON content type.
