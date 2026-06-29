# Changelog

All notable changes to this skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/); this project
tracks [Phel](https://github.com/phel-lang/phel-lang) releases rather than its
own versions.

## [Unreleased]

### Added

- Request validation with `phel.schema` (`module/schema.phel`).
- JSON request-body parsing and a `POST /greet` JSON endpoint.
- `web-skeleton.http.response`: semantic helpers (`ok`, `bad-request`,
  `not-found`) over `phel.http`'s `json-response` / `html-response`.
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

- `phel-config.php` uses `PhelConfig::forProject(ProjectLayout::Flat)` instead
  of the verbose builder, dropping the src/tests/vendor/format/temp lines now
  covered by the layout preset; enabled `->withOptimizationLevel(2)`.
- `app.phel` pipes the request through the handler with thread-first `->`.
- Method dispatch moved from `phel.match` in the ping handler to the router's
  per-method route data; the router now answers `405` itself.
- Controllers build responses via `phel.http`'s `json-response` /
  `html-response` (added upstream in phel-lang/phel-lang#2271) plus the
  `web-skeleton.http.response` sugar, instead of a duplicated private helper.
- `app.phel` is now a thin IO entry point; routing lives in `router.phel`.
- Tests build requests with `phel.http/request-from-map` instead of raw maps.
- Bumped the dev `build/Dockerfile` to PHP 8.4.
- Committed `composer.lock` for reproducible installs.

### Removed

- Unused Phel export config (it pointed at non-existent directories).
