# Contributing

Thanks for helping improve the Phel Web Skeleton.

## Setup

You need PHP 8.5 or newer and [Composer](https://getcomposer.org/).

```bash
composer install
composer run:dev    # http://localhost:8080
```

To work inside Docker instead, run `docker compose up --build`.

If you use an AI coding tool, run `agnostic-ai sync` once to generate its instruction files from `.agnostic-ai/`.

## Before you push

```bash
composer check    # format check, lint, and tests: the same gate as CI
```

If the format check fails, run `composer format`.

Changes to the Docker images or HTTP behavior also need the smoke test:

```bash
composer run:prod                # builds, then serves on :8080
build/smoke.sh http://localhost:8080    # in another terminal
```

CI runs the same script against the compiled build and the production image.

## Pull requests

1. Branch off `main`. Keep the change small and focused.
2. Add or update a test in `tests/` for any behavior change.
3. Use [Conventional Commits](https://www.conventionalcommits.org/): `feat:`, `fix:`, `docs:`, `ci:`, `build:`, `chore:`, `ref:`.
4. Update the README when a command, route, or setting changes, and add a line under `Unreleased` in the changelog.
5. Open the PR. The template asks what changed and how you checked it.

See the [project layout](README.md#project-layout) for where things live.
