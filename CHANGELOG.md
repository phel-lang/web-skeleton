# Changelog

All notable changes to this skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/); this project
tracks [Phel](https://github.com/phel-lang/phel-lang) releases rather than its
own versions.

## [Unreleased]

### Added

- Request validation with `phel.schema` (`module/schema.phel`).
- JSON request-body parsing and a `POST /greet` JSON endpoint.
- `web-skeleton.http.response`: reusable response builders (`html`, `json`,
  `ok`, `bad-request`, `not-found`) shared by every controller.
- `web-skeleton.config`: env-resolved configuration map.
- `wrap-exception` middleware: catches uncaught handler errors and answers a
  JSON `500` instead of leaking a stack trace.
- `web-skeleton.router`: route table kept as plain data, separate from wiring.
- REPL `(comment …)` blocks for driving handlers and the app without a server.
- Tests: end-to-end `router-test` (404/405/dispatch) and `middleware-test`.
- Production `build/Dockerfile.prod` (multi-stage, serves the compiled `out/`).
- `.editorconfig` and `composer check` / `composer format:check` scripts.
- CI: dependency caching, a formatting gate, and a build-verify step.

### Changed

- Method dispatch moved from `phel.match` in the ping handler to the router's
  per-method route data; the router now answers `405` itself.
- Controllers build responses via `web-skeleton.http.response` instead of
  duplicating private `json-response` / `html-response` helpers.
- `app.phel` is now a thin IO entry point; routing lives in `router.phel`.
- Tests build requests with `phel.http/request-from-map` instead of raw maps.
- Bumped the dev `build/Dockerfile` to PHP 8.4.
- Committed `composer.lock` for reproducible installs.

### Removed

- Unused Phel export config (it pointed at non-existent directories).
