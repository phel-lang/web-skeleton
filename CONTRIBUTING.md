# Contributing

Thanks for helping improve the Phel Web Skeleton.

## Setup

```bash
composer install
composer run:dev    # http://localhost:8080
```

## Before you push

```bash
composer check      # runs format:check + tests (same as CI)
```

If formatting fails, run `composer format` to fix it.

## Pull requests

1. Branch off `main`.
2. Keep changes small and focused.
3. Add or update a test in `tests/` for any behaviour change.
4. Use [Conventional Commits](https://www.conventionalcommits.org/) for messages
   (`feat:`, `fix:`, `docs:`, `ci:`, `build:`, `chore:`, `ref:`).
5. Open the PR — the [template](.github/PULL_REQUEST_TEMPLATE.md) and CI will
   guide the rest.

## Project layout

See the [Project layout](README.md#project-layout) section in the README.
