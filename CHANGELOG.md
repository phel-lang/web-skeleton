# Changelog

All notable changes to this skeleton are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/). This project tracks [Phel](https://github.com/phel-lang/phel-lang) releases and has no versions of its own.

## [Unreleased]

### Added

- `HEAD` requests are served as `GET` without a body.
- Security headers on every response: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, and a Content-Security-Policy.
- Global middleware now wraps the whole router, so `404`, `405`, and `500` responses get the `Server` header, security headers, logging, and the exception guard.
- `wrap-exception` logs the exception class, location, and trace.
- Request validation with `phel.schema`, a `POST /greet` JSON endpoint, and JSON body parsing.
- `web-skeleton.http.response` helpers (`ok`, `bad-request`, `not-found`, `json-response`) and `web-skeleton.config`, an environment-based configuration map.
- `build/smoke.sh` and CI jobs that smoke-test the compiled build and the production image.
- `composer lint` (`phel lint`, with unused code as an error) in `composer check` and CI.
- `composer validate --strict` in CI.
- Instructions for Claude Code and Codex in `.agnostic-ai/`, synced with agnostic-ai.
- `.editorconfig`, `composer check`, and `composer format:check`.

### Changed

- The production image runs FrankenPHP instead of `php -S`, with an 8 MB body limit and timeouts. It runs as an unprivileged user with production PHP settings. It is about half the size.
- The development `docker compose` setup now serves the app on port 8080.
- A JSON body that cannot be encoded now gives a JSON `500`, not an empty `200`.
- The greet name rule counts characters, rejects invalid UTF-8, and rejects a trailing newline.
- Routing uses `r/compiled-router`, and the router answers `405` from the per-method route data.
- `app.phel` is a thin entry point that also catches errors from request parsing and response output.
- `phel-config.php` uses `ProjectLayout::Flat` and optimization level 2.
- Requires `phel-lang/phel-lang` `^0.54` and PHP 8.5.
- `composer.lock` is committed.

### Removed

- Unused Phel export config.
- The `phel agent-install` steps in the README, replaced by agnostic-ai.

### Fixed

- `GET /greet/{name}` no longer answers an empty `200` for invalid UTF-8.
- The dev `docker compose` setup no longer starts an unreachable php-fpm.
