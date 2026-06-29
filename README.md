# Phel Web Skeleton

[![CI](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml/badge.svg)](https://github.com/phel-lang/web-skeleton/actions/workflows/ci.yml)

[Phel](https://phel-lang.org/) is a functional Lisp that compiles to PHP. This
skeleton is the fastest way to start a small Phel web app: routed HTTP server,
JSON + HTML responses, middleware, request validation, a 404 handler, and tests
— in a handful of `.phel` files.

**Included:** `phel.router` · `phel.http` · `phel.html` · `phel.json` ·
`phel.schema` (validation) · `phel.test` · error-handling middleware · CI · Docker.

## Quick start

Requires PHP **>= 8.4** and [Composer](https://getcomposer.org/).

```bash
composer install
composer run:dev    # http://localhost:8080 — recompiles every request
```

Sample routes:

| Route              | Shows                                          |
| ------------------ | ---------------------------------------------- |
| `GET /`            | HTML page (`phel.html`)                        |
| `GET /ping`        | JSON response (`phel.json`)                    |
| `POST /ping`       | Per-method handler; echoes parsed JSON body    |
| `GET /greet/:name` | Path param validated with `phel.schema`        |
| `POST /greet`      | JSON body parsed + schema-validated            |
| `GET /nope`        | Custom 404 handler                             |

## Commands

```bash
composer run:dev       # dev server, recompiles every request
composer run:prod      # build once, run compiled PHP
composer build         # AOT-compile src/ into out/
composer test          # run phel tests
composer format        # format src/ and tests/
composer check         # format:check + test (run before pushing)
composer repl          # interactive Phel REPL
```

## Project layout

```
src/
  app.phel               ; IO entry point (request in, response out)
  router.phel            ; route table (data) + wired handler + middleware
  config.phel            ; env-resolved configuration map
  middleware.phel        ; exception → 500, logger, server-header
  http/response.phel     ; ok/bad-request/not-found over phel.http
  controller/routes.phel ; request handlers (per method, parsing, validation)
  module/greet.phel      ; pure domain code
  module/schema.phel     ; request schemas (phel.schema, Malli-style vectors)
  view/main.phel         ; HTML view (phel.html)
tests/                   ; mirrors src/, uses phel.test
public/index.php         ; web entry — serves compiled out/ if present
phel-config.php          ; Phel build / format config
```

Namespaces use the dot separator (e.g. `web-skeleton.controller.routes`).

## Routing

The route table is plain data in `src/router.phel`, separate from wiring so it
can be inspected and tested on its own:

```phel
(def routes
  [["/" {:handler ctrl/index-handler}]
   ["/ping" {:name ::ping
             :get  {:handler ctrl/ping-get-handler}
             :post {:handler ctrl/ping-post-handler}}]
   ["/greet/{name}" {:name ::greet
                     :get  {:handler ctrl/greet-handler}}]])
```

Method dispatch lives in the route data (`:get` / `:post`), not the handler —
the router answers `405` itself. `r/handler` wraps the router into a
`request -> response` function and accepts `:middleware`, `:not-found`,
`:method-not-allowed`, and `:not-acceptable` options.

> The skeleton uses `r/compiled-router`, which precompiles the Symfony matcher
> at macro-expansion (~3x faster matching). It needs a statically-known route
> table; switch to `r/router` if you build routes from runtime values.

**Add a route:** write a `request -> response` handler in
`src/controller/routes.phel`, register it in `routes`, add a test. That's the loop.

```phel
(defn time-handler [_req] (resp/ok {:now (php/time)}))
;; ["/time" {:name ::time :get {:handler ctrl/time-handler}}]
```

Use the helpers in `web-skeleton.http.response` (`resp/ok`, `resp/bad-request`,
`resp/not-found`) or `phel.http`'s `h/json-response` / `h/html-response` for an
explicit status.

## Request validation (`phel.schema`)

Schemas are Malli-style vectors in `module/schema.phel`. Validate at the edge of
a handler — `sc/conform` coerces and returns the value, or `sc/invalid-marker`
on failure:

```phel
(def greet-params [:map [:name [:and :string [:re "/^.{1,50}$/"]]]])

(let [result (sc/conform greet-params {:name name})]
  (if (= result sc/invalid-marker)
    (bad-request (sc/human-readable-explain (sc/explain greet-params {:name name})))
    (ok (:name result))))
```

`phel.schema` also offers `validate`, `coerce`, `generate`, and `instrument!`.

## Request bodies

`phel.http` decodes the body into `:parsed-body` — form fields for
urlencoded/multipart, decoded JSON for `application/json`. Query string is
`:query-params`. Handlers just read the map:

```phel
(defn greet-post-handler [req]
  (greet-response (or (:parsed-body req) {})))
```

`:parsed-body` is `nil` for an empty/malformed body, so `(or … {})` gives a safe
default and the schema reports the missing field.

## Middleware

A 2-arg function `(fn [handler request] ...)`, composed via `:middleware` on
`r/handler` (global) or a route. First entry is outermost — `wrap-exception`
goes first to catch throws and answer a JSON `500`:

```phel
(defn wrap-exception [handler request]
  (try
    (handler request)
    (catch \Throwable e
      (php/error_log (str "[err] " (php/-> e (getMessage))))
      (h/json-response 500 {:error "internal server error"}))))
```

## AI assistants

Agent-agnostic — no tool-specific files committed. Generate adapters with Phel's
installer (output is `.gitignore`d, so each dev installs their own):

```bash
vendor/bin/phel agent-install --auto    # agents already used in this project
vendor/bin/phel agent-install claude     # or: cursor, codex, gemini, copilot, aider
vendor/bin/phel agent-install --check    # catch drift after composer update
```

## Docker

```bash
# Dev — mounts source, recompiles per request
docker compose up -d --build
docker exec -ti -u dev phel_web_skeleton bash && composer install

# Prod — multi-stage build → slim runtime serving compiled out/
docker build -f build/Dockerfile.prod -t phel-web-skeleton .
docker run --rm -p 8080:8080 phel-web-skeleton
```

## More

- [Contributing](CONTRIBUTING.md) · [Changelog](CHANGELOG.md)
- [Phel docs](https://phel-lang.org/documentation/getting-started/) ·
  [Phel on GitHub](https://github.com/phel-lang/phel-lang)
</content>
</invoke>
