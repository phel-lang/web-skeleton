# web-skeleton

Web app starter in [Phel](https://phel-lang.org/) (compiles to PHP). Needs PHP >= 8.5 and Composer. Sources are `.phel` files under `src/`; tests mirror them under `tests/`. Namespaces use a dot (`web-skeleton.controller.routes`).

Edit specs under `.agnostic-ai/`, then run `agnostic-ai sync`. Never edit generated files (`CLAUDE.md`, `AGENTS.md`, `.claude/`, `.codex/`, `.agents/`).

## Commands

- `composer install`: install dependencies.
- `composer run:dev`: dev server on http://localhost:8080, recompiles on every request.
- `composer test`: run the Phel tests.
- `composer format`: format `src/` and `tests/`.
- `composer lint`: run `phel lint`.
- `composer check`: format check, lint, and tests. Run before pushing; CI runs the same.
- `composer build`: compile `src/` into `out/`. CI verifies this too.
- `build/smoke.sh URL`: HTTP smoke test. Run it against `composer run:prod` or the production image after changing HTTP behavior or Docker files.
- `docker compose up --build`: development container on http://localhost:8080.

## Conventions

- Add or update a test in `tests/` for any behavior change.
- Keep the route table in `src/router.phel` as plain data, separate from the wiring.
- Handlers are `request -> response` functions in `src/controller/routes.phel`. Method dispatch lives in the route data (`:get`, `:post`).
- Build JSON responses with `web-skeleton.http.response` (`ok`, `bad-request`, `json-response`), not `phel.http/json-response`, which hides encode failures.
- Validate request input with `phel.schema` schemas in `src/module/schema.phel`.
- Branch off `main`; keep changes small.
- When a command, route or setting changes, update the README and the `Unreleased` section of `CHANGELOG.md`.
- Do not commit a `vendor` symlink; `vendor` is git-ignored.
- Use Conventional Commits: `feat:`, `fix:`, `docs:`, `ci:`, `build:`, `chore:`, `ref:`.
