# Phel Web Skeleton

[![CI](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml/badge.svg)](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml)

[Phel](https://phel-lang.org/) is a functional Lisp that compiles to PHP. This
skeleton is the fastest way to start a small web app written in Phel: a
routed HTTP server, JSON + HTML responses, middleware, request validation, a
404 handler, and a test suite — all in a handful of `.phel` files.

**Batteries included:** `phel.router` · `phel.http` · `phel.html` · `phel.json`
· `phel.schema` (validation) · `phel.test` · error-handling middleware · CI · Docker.

## Requirements

- PHP **>= 8.4**
- [Composer](https://getcomposer.org/)

## Quick start

```bash
composer install
composer run:dev    # http://localhost:8080 — recompiles every request
```

Then open one of the sample routes:

| Route             | What it shows                                                |
| ----------------- | ------------------------------------------------------------ |
| `GET /`           | HTML page rendered with `phel.html`                          |
| `GET /ping`       | JSON response (`phel.json`)                                  |
| `POST /ping`      | Per-method handler; echoes the parsed JSON body              |
| `GET /greet/:name`| Path parameter validated with `phel.schema`                  |
| `POST /greet`     | JSON body parsed + schema-validated (`{"name": "..."}`)      |
| `GET /nope`       | Custom 404 handler                                           |

## Project layout

```
src/
  app.phel               ; ns web-skeleton.app — IO entry point (request in, response out)
  router.phel            ; route table (as data) + wired app handler + middleware
  config.phel            ; env-resolved configuration map
  middleware.phel        ; exception → 500, logger, server-header middleware
  http/response.phel     ; reusable response builders (html/json/ok/bad-request/not-found)
  controller/routes.phel ; request handlers (one per method, body parsing, validation)
  module/greet.phel      ; pure domain code
  module/schema.phel     ; request schemas (phel.schema, Malli-style vectors)
  view/main.phel         ; HTML view built with phel.html
tests/
  router-test.phel       ; end-to-end routing (404 / 405 / dispatch)
  middleware-test.phel
  controller/routes-test.phel
  module/greet-test.phel
  module/schema-test.phel
public/
  index.php              ; web entry — serves compiled out/ if present
phel-config.php          ; Phel build / format config
```

Phel namespaces use the modern dot separator (e.g. `web-skeleton.controller.routes`).

## Commands

```bash
composer run:dev       # dev server, recompiles every request (no out/ dir)
composer run:prod      # builds once and runs the compiled PHP
composer build         # AOT-compile src/ into out/
composer test          # run phel tests
composer format        # format src/ and tests/
composer format:check  # fail if anything is unformatted (used in CI)
composer check         # format:check + test (run before pushing)
composer repl          # interactive Phel REPL
```

## Request validation (`phel.schema`)

Schemas are plain Malli-style vectors, kept in `module/schema.phel`. Validate
request data at the edge of a handler with `phel.schema` directly —
`sc/conform` coerces and returns the value, or `sc/invalid-marker` on failure:

```phel
(def greet-params
  [:map [:name [:and :string [:re "/^.{1,50}$/"]]]])

;; in a handler (see conform-or-error in controller/routes.phel)
(let [result (sc/conform greet-params {:name name})]
  (if (= result sc/invalid-marker)
    (bad-request (sc/human-readable-explain (sc/explain greet-params {:name name})))
    (ok (:name result))))
```

`phel.schema` also offers `validate`, `coerce`, `generate` (test data), and
`instrument!` for runtime function-contract checks.

## Reading request bodies

`phel.http` decodes the request body into `:parsed-body` for you: form fields
(`$_POST`) for `application/x-www-form-urlencoded` / `multipart/form-data`, and
the decoded JSON for `application/json`. Query string lives on `:query-params`
(`$_GET`). Handlers just read the map — no manual `php://input` plumbing:

```phel
(defn greet-post-handler [req]
  (greet-response (or (:parsed-body req) {})))
```

`:parsed-body` is `nil` for an empty or malformed body, so `(or … {})` gives a
safe default and the schema reports the missing field.

## Routing

The route table is a plain value in `src/router.phel`, kept separate from the
wiring so it can be inspected and tested on its own:

```phel
(def routes
  [["/" {:handler ctrl/index-handler}]
   ["/ping" {:name ::ping
             :get  {:handler ctrl/ping-get-handler}
             :post {:handler ctrl/ping-post-handler}}]
   ["/greet/{name}" {:name ::greet
                     :get  {:handler ctrl/greet-handler}}]])
```

Dispatch on the HTTP method belongs in the route data (`:get` / `:post`), not
in the handler — the router answers `405` itself for unsupported methods.

`r/handler` wraps the router into a `request -> response` function and accepts
options for global `:middleware`, a `:not-found` handler,
`:method-not-allowed`, and `:not-acceptable`.

> `r/compiled-router` is a faster, macro-expanded alternative — use it when
> handlers are referenced by keyword/name (not as raw function values), since
> the macro embeds the route table into the compiled code.

### Add your own route

1. Write a handler in `src/controller/routes.phel` — a `request -> response`
   function. Use the builders in `web-skeleton.http.response` (`resp/ok`,
   `resp/json`, `resp/html`, `resp/bad-request`, `resp/not-found`):

   ```phel
   (defn time-handler [_req]
     (resp/ok {:now (php/time)}))
   ```

2. Register it in the `routes` table in `src/router.phel`:

   ```phel
   ["/time" {:name ::time :get {:handler ctrl/time-handler}}]
   ```

3. Add a test in `tests/controller/routes-test.phel` and run `composer test`.

That's the whole loop: handler → route → test.

## Middleware

Middleware is a 2-arg function `(fn [handler request] ...)`. Compose it via
`:middleware` on `r/handler` (global) or on a route's `:middleware` key. The
first entry in the vector is the outermost wrapper, so `wrap-exception` goes
first to catch anything the inner handlers throw and answer a JSON `500`:

```phel
(defn wrap-exception [handler request]
  (try
    (handler request)
    (catch \Throwable e
      (php/error_log (str "[err] " (php/-> e (getMessage))))
      (resp/json 500 {:error "internal server error"}))))

(defn wrap-server-header [handler request]
  (let [response (handler request)]
    (update response :headers assoc :server (:server-header cfg/config))))
```

## Tests

Tests live in `tests/` and use `phel.test`:

```bash
composer test
```

## Docker

Development (mounts the source, recompiles per request):

```bash
docker compose up -d --build
docker exec -ti -u dev phel_web_skeleton bash
composer install
```

Production (multi-stage build → slim runtime serving compiled `out/`):

```bash
docker build -f build/Dockerfile.prod -t phel-web-skeleton .
docker run --rm -p 8080:8080 phel-web-skeleton
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md). Changes are tracked in
[CHANGELOG.md](CHANGELOG.md).

## Learn more

- [Phel documentation](https://phel-lang.org/documentation/getting-started/)
- [Phel on GitHub](https://github.com/phel-lang/phel-lang)
