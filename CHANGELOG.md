# Changelog

All notable changes to this skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/); this project
tracks [Phel](https://github.com/phel-lang/phel-lang) releases rather than its
own versions.

## [Unreleased]

### Added

- Request validation with `phel.schema` (`module/schema.phel`).
- JSON request-body parsing and a `POST /greet` JSON endpoint.
- HTTP-method dispatch with `phel.match` in the ping handler.
- Production `build/Dockerfile.prod` (multi-stage, serves the compiled `out/`).
- `.editorconfig` and `composer check` / `composer format:check` scripts.
- CI: dependency caching, a formatting gate, and a build-verify step.

### Changed

- Bumped the dev `build/Dockerfile` to PHP 8.4.
- Committed `composer.lock` for reproducible installs.

### Removed

- Unused Phel export config (it pointed at non-existent directories).
