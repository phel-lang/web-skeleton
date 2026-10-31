# Project instructions

CLAUDE.md, AGENTS.md, and the other AI tool files are generated from `.agnostic-ai/`. Edit `.agnostic-ai/AGNOSTIC_AI.md` and the source specs, then run `agnostic-ai sync`.

## Project

Phel Web Skeleton: a small web app starter in [Phel](https://phel-lang.org/), a functional Lisp that compiles to PHP. Requires PHP >= 8.5 and Composer. Sources are `.phel` files under `src/`; tests mirror them under `tests/`. Namespaces use the dot separator (`web-skeleton.controller.routes`).

## Commands

- `composer install`: install dependencies.
- `composer run:dev`: dev server on http://localhost:8080, recompiles every request.
- `composer test`: run the Phel tests.
- `composer format`: format `src/` and `tests/`.
- `composer lint`: run `phel lint`.
- `composer check`: format check, lint, and tests. Run it before pushing; CI runs the same.
- `composer build`: compile `src/` into `out/`. CI verifies this too.

## Conventions

- Add or update a test in `tests/` for any behavior change.
- Keep the route table in `src/router.phel` as plain data, separate from the wiring.
- Handlers are `request -> response` functions in `src/controller/routes.phel`. Method dispatch lives in the route data (`:get`, `:post`), not in the handler.
- Build responses with `web-skeleton.http.response` (`ok`, `bad-request`, `not-found`) or `phel.http`.
- Validate request input with `phel.schema` schemas in `src/module/schema.phel`.
- Branch off `main` and keep changes small and focused.
- Use Conventional Commits: `feat:`, `fix:`, `docs:`, `ci:`, `build:`, `chore:`, `ref:`.
