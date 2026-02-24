---
name: ddev-test
description: Run Pest tests through DDEV container
---

Run tests inside the DDEV container where the database is available.

## Commands
- All tests: `ddev exec php artisan test`
- Filter: `ddev exec php artisan test --filter={argument}`
- Specific dir: `ddev exec php artisan test tests/Feature/Auth`
- Full suite with lint: `ddev exec composer test`

Always use `ddev exec` prefix. Never run `php artisan test` directly on the host.
