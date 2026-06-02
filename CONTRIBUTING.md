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

1. Branch off `main`; keep changes small and focused.
2. Add or update a test in `tests/` for any behaviour change.
3. Use [Conventional Commits](https://www.conventionalcommits.org/)
   (`feat:`, `fix:`, `docs:`, `ci:`, `build:`, `chore:`, `ref:`).
4. Open the PR — the [template](.github/PULL_REQUEST_TEMPLATE.md) and CI guide the rest.

See [Project layout](README.md#project-layout) in the README for structure.
