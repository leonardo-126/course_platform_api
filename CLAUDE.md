# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

Laravel 12 / PHP 8.2 backend for a courses / LMS platform. The project is at the **base structure** stage (`feat/estrutura base`): the domain layer (migrations + Eloquent models) is in place, but there are **no controllers, form requests, services, or working routes yet**. Expect to be adding the HTTP layer on top of an already-modeled domain.

## Common commands

```bash
composer install                       # install PHP deps
composer setup                         # full bootstrap: install, .env, key, migrate, npm
composer dev                           # serve + queue:listen + pail + vite (concurrently)
composer test                          # config:clear then artisan test
php artisan test --filter=TestName     # run a single test (or a single method)
php artisan test tests/Feature/X.php   # run a single test file
php artisan migrate:fresh --seed       # rebuild schema from migrations
vendor/bin/pint                        # format / lint (Laravel Pint)
```

Tests run against an in-memory SQLite (`phpunit.xml` overrides `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), so feature tests can freely migrate without touching the dev DB.

## Architecture

The domain is intentionally split into **five bounded contexts**, mirrored 1:1 in `database/migrations/` and `app/Models/`. When adding entities, place them in the matching context — do not flatten.

| Context | Tables / Models |
|---|---|
| **Identity** | `users`, `user_profiles`, `user_stats` |
| **Catalog** | `courses`, `course_authors` (pivot) |
| **Content** | `course_sections`, `lessons`, `lesson_questions`, `lesson_question_options` |
| **Learning** | `course_enrollments`, `lesson_progress`, `quiz_attempts`, `quiz_attempt_answers` |
| **Gamification** | `xp_transactions`, `achievements`, `user_achievements` |

### Domain rules baked into the schema

These are non-obvious invariants — read them before suggesting changes:

- **One `users` table for both students and authors.** Do not introduce a separate `teachers` / `instructors` table or a rigid role column. Authorship is expressed through the `course_authors` pivot, with `is_owner` / `can_view_student_progress` flags.
- **Co-authors do not get CRUD on a course.** They are analytics/visibility collaborators only. Any author-write logic must check `is_owner` on the pivot row.
- **XP is granted only on course completion** (see `xp_granted_at` on `course_enrollments`). Lesson and quiz events do not award XP directly. XP history lives in `xp_transactions` — treat it as the source of truth, not `user_stats` aggregates.
- **Quizzes have attempts + scores + history.** Use `quiz_attempts` / `quiz_attempt_answers` rather than collapsing onto `lesson_progress`.
- **Achievements are unlocked by flexible logic**, not hardcoded DB rules. The `achievements` table is a catalog; `user_achievements` is the join with `unlocked_at` and a `meta` JSON column for the snapshot of why it fired.
- **`Course → Lesson` uses `HasManyThrough`** via `course_sections`. Lessons never belong directly to a course.

### HTTP layer is not wired yet

- [bootstrap/app.php](bootstrap/app.php) only registers `web` and `console` routes. [routes/api.php](routes/api.php) exists but is **not loaded** — adding endpoints there will 404 until you also pass `api: __DIR__.'/../routes/api.php'` (and likely `apiPrefix`) to `withRouting()`. Expect to make that wiring change the first time API work starts.
- `app/Http/Controllers/` contains only the base `Controller.php`. There is no auth setup (no Sanctum / Passport installed yet) — confirm with the user before picking one.

## Project-specific skills

Two custom skills under [.github/skills/](.github/skills/) encode the team's conventions for this codebase. Read them before doing schema or architectural work:

- [course-platform-architect](.github/skills/course-platform-architect/SKILL.md) — bounded-context principles, MVP-first scoping, what to defer to phase 2 (payments, certificates, reviews, moderation, notifications).
- [course-platform-migrations](.github/skills/course-platform-migrations/SKILL.md) — migration conventions and the canonical blueprint at [references/course-platform-migration-blueprint.md](.github/skills/course-platform-migrations/references/course-platform-migration-blueprint.md).

Migration conventions used throughout existing files: `foreignId()->constrained()->cascadeOnDelete()`, slugs/pivots `unique()`, status as `string` defaulting to e.g. `'draft'`, composite indexes on foreign-key + status pairs (see `courses.index(['created_by', 'status'])`). Match these when adding new migrations.
