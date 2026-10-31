# Phel Web Skeleton

[![CI](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml/badge.svg)](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml)

A small web app starter in [Phel](https://phel-lang.org/), a functional Lisp that compiles to PHP.

You get routing, JSON and HTML responses, middleware, request validation, tests, CI, and Docker images for development and production, in a handful of `.phel` files.

## Quick start

You need PHP 8.5 or newer and [Composer](https://getcomposer.org/).

```bash
composer install
composer run:dev    # http://localhost:8080, recompiles on every request
```

Try the sample routes:

| Request            | What it shows                                         |
| ------------------ | ----------------------------------------------------- |
| `GET /`            | HTML page built with `phel.html`                      |
| `GET /ping`        | JSON response                                         |
| `POST /ping`       | Echoes the parsed JSON body                           |
| `GET /greet/:name` | Path parameter checked with `phel.schema`             |
| `POST /greet`      | JSON body checked with `phel.schema`                  |
| anything else      | Custom `404`; a wrong method on a known path is `405` |

## Commands

```bash
composer run:dev       # dev server, recompiles on every request
composer run:prod      # compile once, serve the compiled PHP (one request at a time)
composer build         # compile src/ into out/
composer test          # run the tests
composer format        # format src/ and tests/
composer lint          # phel lint (fails on unused requires, imports, bindings)
composer check         # format check, lint, and tests: run before pushing
composer repl          # interactive Phel REPL
build/smoke.sh URL     # HTTP smoke test against a running server or container
```

## Project layout

```
src/
  app.phel                 entry point: request in, response out
  router.phel              route table (plain data), global middleware, HEAD support
  config.phel              configuration read from the environment
  middleware.phel          exception, logger, Server header, security headers
  http/response.phel       ok, bad-request, not-found and friends
  controller/routes.phel   request handlers
  module/greet.phel        domain code
  module/schema.phel       request schemas
  view/main.phel           HTML view
tests/                     mirrors src/
public/index.php           web entry: serves out/ when it exists, else src/
build/                     Dockerfiles, Caddyfile, smoke test
phel-config.php            Phel build and format settings
phel-lint.phel             lint rule severities
```

Namespaces use dots, for example `web-skeleton.controller.routes`.

## Configuration

Read once from the environment in `src/config.phel`.

| Variable        | Default             | Effect                       |
| --------------- | ------------------- | ---------------------------- |
| `APP_NAME`      | `phel-web-skeleton` | Application name             |
| `SERVER_HEADER` | `phel-web-skeleton` | Value of the `Server` header |

## Add a route

1. Write a `request -> response` handler in `src/controller/routes.phel`.
2. Register it in `routes` in `src/router.phel`.
3. Add a test.

```phel
(defn time-handler [_req] (resp/ok {:now (php/time)}))

;; in `routes`:
["/time" {:name ::time :get {:handler ctrl/time-handler}}]
```

The route table is plain data, so tests can inspect it without starting the app.

Method dispatch lives in the route data (`:get`, `:post`), not in the handler.

The router answers `404` and `405` itself.

`HEAD` is served as `GET` with an empty body, so no route needs a `:head` entry.

The router is `r/compiled-router`, which precompiles the matcher and needs a route table known at compile time.

Use `r/router` if you build routes from runtime values.

## Responses

Build responses with `web-skeleton.http.response`: `resp/ok`, `resp/bad-request`, `resp/not-found`, or `resp/json-response` for any status.

These helpers encode JSON with `JSON_THROW_ON_ERROR`.

A body that cannot be encoded becomes a JSON `500` through `wrap-exception`, not an empty `200`.

## Request input

`phel.http` decodes the body into `:parsed-body`: form fields for urlencoded and multipart bodies, decoded JSON for `application/json`.

It is `nil` for an empty or malformed body, so handlers use `(or (:parsed-body req) {})`.

The query string is in `:query-params`.

Validate input at the edge of a handler with a `phel.schema` schema from `src/module/schema.phel`:

```phel
(def greet-params
  [:map [:name [:and :string [:re "/^.{1,50}$/Du"]]]])

(let [result (sc/conform greet-params params)]
  (if (= result sc/invalid-marker)
    (resp/bad-request (sc/human-readable-explain (sc/explain greet-params params)))
    (resp/ok {:name (:name result)})))
```

The `u` modifier counts characters, not bytes, and rejects invalid UTF-8.

The `D` modifier stops `$` from matching before a trailing newline.

## Middleware

A middleware is a function `(fn [handler request] ...)`.

The global chain is `global-middleware` in `src/router.phel`.

It wraps the whole router through `mw/compose`, so `404`, `405`, and `500` responses pass through it too.

The router's own `:middleware` option skips those, so the skeleton does not use it for the global chain.

Per-route `:middleware` still works.

The first entry is the outermost:

| Middleware              | What it does                                                                                    |
| ----------------------- | ----------------------------------------------------------------------------------------------- |
| `wrap-server-header`    | Sets `Server` from `SERVER_HEADER`                                                              |
| `wrap-security-headers` | Adds `nosniff`, `X-Frame-Options`, `Referrer-Policy`, and a CSP; a handler's own headers win    |
| `wrap-exception`        | Turns an uncaught error into a JSON `500` and logs the class, location, and trace               |
| `wrap-logger`           | Logs `METHOD path` for every request                                                            |

The skeleton serves plain HTTP, so add `Strict-Transport-Security` where you terminate TLS.

## Docker

Development mounts the source, installs dependencies, and recompiles per request:

```bash
docker compose up --build              # http://localhost:8080
docker compose exec app composer test
```

Production is a multi-stage build that serves the compiled `out/`:

```bash
docker build -f build/Dockerfile.prod -t phel-web-skeleton .
docker run --rm -p 8080:8080 phel-web-skeleton
build/smoke.sh http://localhost:8080
```

The production image runs [FrankenPHP](https://frankenphp.dev/) (Caddy plus PHP) as an unprivileged user.

It serves `public/` directly and sends everything else to `public/index.php`.

`build/Caddyfile` limits request bodies to 8 MB (a larger body reaches the handler as an empty body) and closes connections after 30 s.

PHP stops a request after 25 s, so it fails before Caddy drops the connection.

PHP runs with production settings: `display_errors` and `expose_php` off, errors on stderr.

To change them, add a file to `$PHP_INI_DIR/conf.d/`.

The image's own file is `00-skeleton.ini`, so later names win.

## AI assistants

Instructions for AI coding tools live in `.agnostic-ai/` and sync to Claude Code and Codex with [agnostic-ai](https://agnostic-ai.org/).

The generated files (`CLAUDE.md`, `AGENTS.md`, `.claude/`, `.agents/`) are git-ignored, so generate them after cloning:

```bash
agnostic-ai sync          # write the tool files
agnostic-ai sync --check  # confirm they match .agnostic-ai/
```

Edit `.agnostic-ai/AGNOSTIC_AI.md` and `agnostic-ai.yaml`, never the generated files.

To support another tool, add it under `targets` in `agnostic-ai.yaml`.

## More

- [Contributing](CONTRIBUTING.md)
- [Changelog](CHANGELOG.md)
- [Phel documentation](https://phel-lang.org/documentation/getting-started/)
- [Phel on GitHub](https://github.com/phel-lang/phel-lang)
