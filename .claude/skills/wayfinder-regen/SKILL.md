---
name: wayfinder-regen
description: Regenerate Wayfinder route helpers after route changes
user-invocable: false
---

After modifying any file in `routes/`, run:

```bash
ddev exec php artisan wayfinder:generate
```

This regenerates typed route helpers in `resources/js/wayfinder/`. Without this, TypeScript imports for routes will break.
