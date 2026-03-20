# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-02-23

### Added

- Pitch creation and management (one pitch per user)
- Pitch show page with interests management
- Landing page with co-founder matchmaking hero
- NorthCloud integration with Redis subscriber, articles sidebar nav, and shared composables
- Laravel Horizon for queue management
- Deployer-based deployment pipeline with systemd user services
- Caddyfile with TLS, encoding, static file handling, and error pages
- DDEV local development environment with Vite support
- Claude Code hooks, permissions, and skills

### Changed

- Swap display font to Plus Jakarta Sans
- Project renamed with updated composer.json, package-lock.json, and dependencies
- Hero copy updated from "Steve" to "Jobs" for clearer Apple reference
- Hardened deploy script with disabled view:cache and error suppression

### Fixed

- Caddy logging to proper log path
- NorthCloud Layer 8 channels and environment variable configuration
- Configurable SSR port with correct node type reference and env var
- Pint code style issues in test files
- Deployer shared files configuration
