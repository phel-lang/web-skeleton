# Phel Web Skeleton

[Phel](https://phel-lang.org/) is a functional Lisp that compiles to PHP. This
skeleton is the fastest way to start a small web app written in Phel: a
routed HTTP server, JSON + HTML responses, middleware, a 404 handler, and a
test suite — all in a handful of `.phel` files.

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
| `POST /ping`      | Same handler dispatched on a different HTTP method           |
| `GET /greet/:name`| Path parameter extracted from the URL                        |
| `GET /nope`       | Custom 404 handler                                           |

## Project layout

```
src/
  app.phel               ; ns web-skeleton.app — wires the router + middleware
  middleware.phel        ; logger + server-header middleware examples
  controller/routes.phel ; request handlers (HTML/JSON responses)
  module/greet.phel      ; pure domain code
  view/main.phel         ; HTML view built with phel.html
tests/
  controller/routes-test.phel
  module/greet-test.phel
public/
  index.php              ; entry point — serves compiled out/ if present
phel-config.php          ; Phel build / format / export config
```

Phel namespaces use the modern dot separator (e.g. `web-skeleton.controller.routes`).

## Commands

```bash
composer run:dev   # dev server, recompiles every request (no out/ dir)
composer run:prod  # builds once and runs the compiled PHP
composer build     # AOT-compile src/ into out/
composer test      # run phel tests
composer format    # format src/ and tests/
composer repl      # interactive Phel REPL
```

## Routing

Routes live in `src/app.phel` and use the built-in `phel.router`:

```phel
(r/router
 [["/" {:handler routes/index-handler}]
  ["/ping" {:name ::ping
            :get  {:handler routes/ping-handler}
            :post {:handler routes/ping-handler}}]
  ["/greet/{name}" {:name ::greet
                    :get  {:handler routes/greet-handler}}]])
```

`r/handler` wraps the router into a `request -> response` function and accepts
options for global `:middleware`, a `:not-found` handler,
`:method-not-allowed`, and `:not-acceptable`.

> `r/compiled-router` is a faster, macro-expanded alternative — use it when
> handlers are referenced by keyword/name (not as raw function values), since
> the macro embeds the route table into the compiled code.

## Middleware

Middleware is a 2-arg function `(fn [handler request] ...)`. Compose it via
`:middleware` on `r/handler` (global) or on a route's `:middleware` key.

```phel
(defn wrap-server-header [handler request]
  (let [response (handler request)]
    (update response :headers assoc :server "phel-web-skeleton")))
```

## Tests

Tests live in `tests/` and use `phel.test`:

```bash
composer test
```

## Docker

```bash
docker compose up -d --build
docker exec -ti -u dev phel_web_skeleton bash
composer install
```

## Learn more

- [Phel documentation](https://phel-lang.org/documentation/getting-started/)
- [Phel on GitHub](https://github.com/phel-lang/phel-lang)
