# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Coforge (coforge.xyz) is a matchmaking and discovery platform where developers and entrepreneurs pitch their projects to find their perfect co-founder — their Woz to Steve, their Steve to Woz. Built with Laravel 12 + Vue 3 + Inertia.js.

It integrates `jonesrussell/northcloud-laravel` as a sidecar article classifier that ingests and categorizes content via Redis pub/sub, powering personalized feeds and contextual matching signals. Auth is via Laravel Fortify (including 2FA), queues via Laravel Horizon, deployment to coforge.xyz via Deployer with Caddy.

## Local Development (DDEV)

Local development uses DDEV (MariaDB 11.8, PHP 8.4, Node 22). All artisan/composer commands that need the database should be run through `ddev exec` or `ddev ssh`.

```bash
ddev start                    # Start the local environment
ddev exec php artisan migrate # Run migrations
ddev ssh                      # Shell into the container
```

## Commands

### Development
```bash
composer dev          # Runs server, Horizon, Pail (logs), and Vite concurrently
composer dev:ssr      # Same but with SSR instead of Vite dev server
```

### Testing
```bash
composer test                              # Lint check + all tests
php artisan test                           # Run all tests (Pest)
php artisan test --filter=RegistrationTest # Run a single test file
php artisan test tests/Feature/Auth        # Run a directory of tests
```

### Linting & Formatting
```bash
# PHP
composer lint          # Fix PHP style (Pint, Laravel preset)
composer test:lint     # Check PHP style without fixing

# TypeScript/Vue
npm run lint           # ESLint fix
npm run format         # Prettier fix
npm run format:check   # Prettier check
```

### Building
```bash
npm run build      # Production build
npm run build:ssr  # Production build with SSR
```

### Deployment
```bash
vendor/bin/dep deploy  # Deploy to coforge.xyz via Deployer
```

## Architecture

### Backend (PHP)

- **Auth**: Laravel Fortify handles all authentication (login, register, password reset, email verification, 2FA). Views are rendered as Inertia pages via `FortifyServiceProvider`. Custom actions live in `app/Actions/Fortify/`.
- **Controllers**: Currently only `Settings/` controllers (Profile, Password, TwoFactorAuthentication). Routes use Inertia inline rendering for simple pages.
- **Concerns**: Shared validation rule traits (`PasswordValidationRules`, `ProfileValidationRules`) used across requests and actions.
- **Form Requests**: Located in `app/Http/Requests/Settings/` — validation is extracted into request classes, not inline in controllers.
- **Middleware**: `HandleInertiaRequests` shares `auth.user`, `name`, and `sidebarOpen` props globally. `HandleAppearance` manages theme.
- **NorthCloud**: `jonesrussell/northcloud-laravel` provides article ingestion via Redis pub/sub. Models: `Article`, `NewsSource`, `Tag`. Admin at `/dashboard/articles` (requires `is_admin`). Config in `config/northcloud.php`. Commands: `articles:subscribe`, `articles:status`, `articles:stats`.
- **Queue**: Redis-backed via Horizon. Production uses up to 10 worker processes.
- **Database**: MariaDB via DDEV locally. SQLite in production (`database/database.sqlite`). Tests use in-memory SQLite.

### Frontend (TypeScript + Vue 3)

- **Inertia.js**: Pages in `resources/js/pages/` are resolved automatically. Page components receive props from Laravel controllers.
- **Layouts**: Two-tier layout system — `AppLayout.vue` and `AuthLayout.vue` are wrappers that delegate to concrete implementations in `layouts/app/` (sidebar, header variants) and `layouts/auth/` (simple, card, split variants).
- **UI Components**: shadcn-vue (new-york-v4 style) in `resources/js/components/ui/`. These are auto-generated — excluded from ESLint.
- **Wayfinder**: `@laravel/vite-plugin-wayfinder` generates typed route helpers in `resources/js/wayfinder/` from Laravel routes. Use these instead of hardcoded URLs.
- **Types**: Shared TypeScript types in `resources/js/types/` — `Auth`, `User`, `AppPageProps`, `BreadcrumbItem`, etc.
- **Composables**: Vue composables in `resources/js/composables/` (appearance/theme, current URL, initials, 2FA).
- **Path alias**: `@/` maps to `resources/js/`.

### Key Conventions

- **PHP style**: Laravel Pint preset (`pint.json` → `"preset": "laravel"`).
- **TS imports**: Use `type` imports for type-only imports (`@typescript-eslint/consistent-type-imports` enforced). Imports must be alphabetically ordered by group.
- **Tests**: Pest PHP with `RefreshDatabase` trait auto-applied to Feature tests via `tests/Pest.php`.
- **Dates**: `CarbonImmutable` is the default Date class (configured in `AppServiceProvider`).
- **Passwords**: Production enforces min 12 chars, mixed case, letters, numbers, symbols, uncompromised check.

### Deployment

Deployer (`deploy.php`) deploys to `coforge.xyz` as the `deployer` user. The deploy pipeline:
1. Standard Laravel recipe (vendors, migrations)
2. Builds frontend assets (`npm ci && npm run build:ssr`)
3. Installs systemd user services from `deploy/systemd-user/` (Horizon, Inertia SSR, scheduler)
4. Restarts services and PHP-FPM after symlink

Web server is Caddy with PHP 8.4-FPM.
