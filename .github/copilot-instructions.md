<!-- Copilot / AI agent instructions for the Presensi-Penggajian app -->

# Copilot instructions — Presensi & Penggajian (Laravel)

Short, action-oriented guidance to help an AI agent be productive in this repo.

1. Project overview:

-   This is a Laravel (v12) monolith. Key folders: `app/` (controllers, models), `routes/` (web.php), `resources/views` (Blade views), `database/` (migrations, seeders).
-   Main modules: `attendance`, `overtime`, `leave`, `loan`, `payroll`, `employees`, `departments`, `positions`, `office`, `profile` (see `routes/web.php`).

2. Typical data flow & conventions:

-   Controllers live in `app/Http/Controllers` and follow simple CRUD + approval patterns (example: `LeaveController.php`).
-   Models live in `app/Models` and use Eloquent. Common patterns: role checks (`$user->hasRole('staff'|'manager')`), `with()` relations, `whereHas()` filters.
-   File uploads are stored via `->store(..., 'public')` (e.g. leave attachments). These are saved under `storage/app/public`.
-   Route names use dot prefixes: `leave.index`, `leave.create`, `leave.store`, `leave.approve`, `leave.reject`.

3. Developer workflows & exact commands:

-   Install & initial setup (recommended):
    -   `composer run setup` (runs `composer install`, copies `.env`, generates app key, runs migrations, runs `npm install` and `npm run build`).
    -   If you prefer step-by-step: `composer install` then `php -r "file_exists('.env') || copy('.env.example', '.env');"` then `php artisan key:generate` then `php artisan migrate` then `npm install` and `npm run dev`.
-   Dev runner (single-command): `composer run dev` (uses `npx concurrently` to run `php artisan serve`, queues and vite). On Windows PowerShell that runs the same script.
-   Tests: `composer run test` or `php artisan test`. PHPUnit is configured to use in-memory SQLite for tests (see `phpunit.xml` — `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).

4. What to look at first when making changes:

-   Routes & access: `routes/web.php` to see route groups, middleware, and resource controllers.
-   Controller examples: `app/Http/Controllers/LeaveController.php`, `AttendanceController.php`, `OvertimeController.php` for approval flows and request validation.
-   Views: `resources/views/*` (module subfolders like `leave/`, `attendance/`), follow Blade templates and pass compacted variables like `compact('data')`.
-   Migrations & seeders: `database/migrations` and `database/seeders/DatabaseSeeder.php` for data model shape and default data.

5. Project-specific patterns to follow in PRs:

-   Preserve existing status strings and DB fields when updating approval flows (e.g. `status` values in `LeaveController`: `pending`, `approved_manager`, `rejected`).
-   Use existing route names when linking views or redirects (`route('leave.index')`).
-   File uploads: use the `public` disk as existing code does, and return view paths consistent with stored path handling.

6. Integration points & external deps:

-   PHP packages managed by `composer.json` (Laravel 12, tinker, ui). Frontend via Vite (`vite` in `package.json`) and `laravel-vite-plugin`.
-   Background jobs use Laravel queue (scripts run `php artisan queue:listen` in dev). Tests run with `QUEUE_CONNECTION=sync`.

7. Quick examples (copy-paste patterns):

-   Approve pattern (controller):
    -   `Leave::findOrFail($id)->update(['status' => 'approved_manager','approved_by' => Auth::id()]);`
-   Store file attachment:
    -   `$path = $request->file('attachment')->store('leave_attachments','public');`

8. When you are uncertain:

-   Prefer reading `routes/web.php`, the related Controller in `app/Http/Controllers`, and the related Blade view in `resources/views/<module>` before changing behavior.
-   Run `composer run test` to validate behavior changes — unit & feature tests use in-memory sqlite.

If anything here is unclear or you want a different emphasis (more CI, more frontend, or deeper DB notes), tell me which area to expand.
