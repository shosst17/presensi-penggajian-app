# Presensi & Penggajian (HRIS)

A comprehensive **Human Resources Information System (HRIS)** built with Laravel 12, featuring attendance tracking, overtime management, leave/furlough requests, loan administration, and automated payroll processing.

## 🎯 Features

### Core Modules
- **Attendance** — Clock in/out with photo verification; late tracking; attendance history
- **Overtime** — Request, approve, and track overtime with automated pay calculation
- **Leave/Furlough** — Multi-type leave (sick, permission, annual) with H-3 advance notice; manager approval flow
- **Loan Management** — Employee loan requests with approval workflow; installment deductions from salary
- **Payroll** — Automated monthly salary generation with allowances, overtime, and deductions
- **Employees** — Central employee directory with role, department, and position management
- **Departments & Positions** — Organizational structure; position-based salary defaults
- **Office Locations** — Multi-office support with geolocation-based attendance radius

### Role-Based Access
- **Staff** — View own records; submit requests; view personal payslips
- **Manager** — Approve requests from their department
- **Admin** — Full system access; generate payroll; manage master data
- **Director** — System oversight (view-only or approve high-level requests)

### Advanced Features
- **Real-time Notifications** — Staff notified of approvals/rejections instantly
- **Profile Management** — Update avatar, contact info, change password
- **Attendance Reports** — Recap and export reports by month/year
- **PDF Payslips** — Generate downloadable salary slips
- **DataTables Integration** — Interactive, searchable listings with export (copy, CSV, Excel, PDF, print)

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| **Framework** | Laravel 12 |
| **Frontend** | Blade templates, Bootstrap 5, Tailwind CSS 4, AdminLTE 4 |
| **Database** | SQLite (dev/test), MySQL/PostgreSQL (production-ready) |
| **Build Tool** | Vite |
| **Tables** | DataTables 1.13.7 with Bootstrap 5 styling |
| **PDF** | DomPDF (payroll slips, reports) |
| **Queue** | Laravel Queue (sync in dev, Redis/database in production) |
| **Testing** | PHPUnit 11 (in-memory SQLite) |

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite or MySQL

### Installation (Recommended)

```bash
# Clone repository
git clone <repository-url>
cd presensi-penggajian-app

# Run automated setup (installs deps, migrates, seeds)
composer run setup
```

### Manual Installation (Step-by-Step)

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate app key
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate

# Create database and run migrations
php artisan migrate

# Install frontend dependencies and build assets
npm install
npm run build
```

### Development

```bash
# Start dev server with concurrent processes (Laravel, queue, logs, Vite)
composer run dev
```

This runs:
- `php artisan serve` — Laravel dev server (http://localhost:8000)
- `php artisan queue:listen` — Background job listener
- `php artisan pail` — Real-time logs
- `npm run dev` — Vite hot-reload

Alternatively, run each in separate terminals:
```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Testing

```bash
# Run all tests (PHPUnit uses in-memory SQLite)
composer run test

# Or with artisan
php artisan test

# Run specific test suite
php artisan test tests/Unit
php artisan test tests/Feature
```

---

## 📁 Project Structure

```
app/
├── Http/Controllers/        # Request handlers (CRUD, approvals, business logic)
│   ├── AttendanceController.php
│   ├── OvertimeController.php
│   ├── LeaveController.php
│   ├── LoanController.php
│   ├── PayrollController.php
│   └── ...
├── Models/                  # Eloquent ORM models
│   ├── User.php
│   ├── Attendance.php
│   ├── Leave.php
│   ├── OvertimeRequest.php
│   ├── Loan.php
│   ├── Payroll.php
│   └── ...
└── Notifications/           # Notification classes

routes/
├── web.php                  # Web routes (middleware, resource controllers, grouping)

resources/
├── views/
│   ├── layouts/admin.blade.php   # Master layout with navbar, sidebar, DataTables init
│   ├── attendance/
│   ├── leave/
│   ├── overtime/
│   ├── loan/
│   ├── payroll/
│   └── ...
├── js/
│   ├── app.js              # Bootstrap, axios config
│   └── bootstrap.js        # ES module setup
└── sass/
    └── app.scss            # Tailwind, custom CSS

database/
├── migrations/             # Schema definitions
│   ├── 01_create_master_data.php
│   ├── 02_create_users_table.php
│   ├── 03_create_hr_tables.php
│   ├── 04_create_finance_tables.php
│   └── ...
└── seeders/                # Database seeding (roles, departments, etc.)

tests/
├── Feature/                # Integration tests (API, routes, workflows)
└── Unit/                   # Unit tests (models, helpers, calculations)

config/
├── app.php                 # App name, timezone, locale
├── database.php            # DB connections (sqlite, mysql, pgsql)
├── queue.php               # Queue connection (sync, redis, database)
└── ...
```

---

## 📋 Key Workflows

### 1. Attendance Check-In/Out
1. Staff visits `/attendance`
2. System checks geolocation (within office radius)
3. Staff uploads photo and confirms check-in
4. System records time, photo, and calculates late minutes if applicable
5. Manager views recap in `/attendance/recap`

### 2. Leave Request & Approval
1. Staff submits leave request (`/leave/create`) with type, dates, and optional attachment
2. H-3 validation: non-sick leave requires 3-day advance notice
3. Notification sent to manager
4. Manager reviews pending requests (`/leave`)
5. Manager approves or rejects with optional note
6. Staff notified; status updates to `approved_manager` or `rejected`

### 3. Payroll Generation
1. Admin visits `/payroll` and clicks "HITUNG GAJI BULAN INI"
2. System generates payroll for all non-admin employees:
   - Base salary + position allowance
   - Daily meal/transport allowance (per attendance day)
   - Overtime pay (approved overtime × hourly rate)
   - Deductions (loan installments, penalties)
   - **THP** (Take Home Pay) = Base + Allowances + Overtime − Deductions
3. Records saved; staff can view/download slip from `/payroll`

### 4. Loan/Kasbon Request
1. Staff requests loan with amount and tenure
2. System validates:
   - No active loan exists
   - Monthly installment ≤ 30% of base salary
3. Notification to manager
4. Manager approves → status = `active`, funds disbursed
5. Deducted from monthly payroll installments until `paid_off`

---

## 🔐 Authentication & Authorization

- **Login** — Standard Laravel auth (`/login`, `/register`)
- **Roles** — Stored in `users.role` column: `staff`, `manager`, `admin`, `director`
- **Role Checks** — Use `Auth::user()->hasRole('role_name')` or middleware:
  ```php
  // In controller
  if (!Auth::user()->hasRole('admin')) {
      abort(403);
  }
  ```
- **Routes** — Grouped by middleware `auth` (all logged-in users)

---

## 📊 Database Schema Highlights

### Core Tables
- **users** — Employee accounts, roles, department/position FK
- **employee_salaries** — Base salary, allowances per employee
- **departments** — Organizational structure
- **positions** — Job titles; base salary defaults
- **office_locations** — Multi-office support; geofencing radius

### Transaction Tables
- **attendance** — Daily check-in/out logs, photos, lateness tracking
- **overtime_requests** — Pending/approved overtime; duration in minutes
- **leaves** — Leave applications; status tracking (pending/approved/rejected)
- **loans** — Employee loans; tenor, installment, remaining balance
- **payroll** — Monthly payslips; calculated allowances, overtime, deductions
- **audit_logs** — System action audit trail

---

## 🎨 UI & Styling

- **Layout** — AdminLTE 4 (responsive, mobile-friendly)
- **Components** — Bootstrap 5 (cards, modals, alerts, forms)
- **Styling** — Tailwind CSS 4 (utility-first)
- **Tables** — DataTables 1.13.7 (search, sort, pagination, export buttons)
- **Icons** — Bootstrap Icons (bi-*)

---

## ⚙️ Configuration

### Key Environment Variables (`.env`)
```env
APP_NAME="SmartGeo HRIS"
APP_ENV=local
APP_KEY=<generated-by-setup>
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
# Or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=presensi_penggajian
# DB_USERNAME=root
# DB_PASSWORD=secret

QUEUE_CONNECTION=sync  # (use 'redis' or 'database' in production)
```

### Database Connections
- **Development** — SQLite (`database/database.sqlite`)
- **Testing** — In-memory SQLite (`:memory:`)
- **Production** — MySQL or PostgreSQL (configure in `.env`)

---

## 🧪 Testing

```bash
# Run all tests
composer run test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test tests/Unit
php artisan test tests/Feature

# Run specific test
php artisan test --filter=testStaffCanSubmitLeave
```

Tests use **in-memory SQLite** for speed and isolation. Migrations run automatically before test suite.

---

## 📝 Common Tasks

### Add a New Module
1. Create migration: `php artisan make:migration create_module_table`
2. Create model: `php artisan make:model Module`
3. Create controller: `php artisan make:controller ModuleController --resource`
4. Define routes in `routes/web.php`
5. Create Blade views in `resources/views/module/`
6. Add to sidebar navigation in `resources/views/layouts/admin.blade.php`

### Generate Database Seeder
```bash
php artisan make:seeder ModuleSeeder
# Edit database/seeders/ModuleSeeder.php
# Add to DatabaseSeeder.php
php artisan db:seed
```

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Export Data (DataTables)
- Click export buttons on any datatable-enabled page (Copy, CSV, Excel, PDF, Print)
- PDF payslips: `/payroll/{id}` → download button

---

## 🐛 Troubleshooting

### "No database connection"
- Ensure `database/database.sqlite` exists or MySQL is running
- Run: `touch database/database.sqlite` (SQLite) or verify MySQL creds
- Run migrations: `php artisan migrate`

### "Method 'hasRole' not found"
- `User` model must define `hasRole(string $role): bool` method
- Check `app/Models/User.php` has the method or uses a role trait (e.g., Spatie)

### DataTables warning "Incorrect column count"
- Ensure table `<thead>` and `<tbody>` have matching column counts
- Only tables with `datatable` class initialize; others are ignored
- Check `resources/views/layouts/admin.blade.php` initializer

### Queue jobs not running
- Ensure `QUEUE_CONNECTION=sync` in `.env` (dev/test)
- For production, set `QUEUE_CONNECTION=redis` or `database` and start listener: `php artisan queue:listen`

### Assets not loading (CSS/JS)
- Run: `npm run build` (production) or `npm run dev` (development with hot-reload)
- Clear browser cache (Ctrl+Shift+Delete)

---

## 📦 Dependencies

### PHP (Composer)
- `laravel/framework: ^12.0`
- `laravel/tinker: ^2.10.1`
- `laravel/ui: ^4.6`
- `barryvdh/laravel-dompdf` (PDF generation)
- Development: `phpunit/phpunit: ^11.5.3`, `laravel/pail: ^1.2.2`

### Node (NPM)
- `bootstrap: ^5.2.3`
- `tailwindcss: ^4.0.0`
- `vite: ^7.0.7`
- `laravel-vite-plugin: ^2.0.0`
- `sass: ^1.56.1`

### CDN (loaded via layout)
- DataTables: `cdn.datatables.net/1.13.7/`
- Bootstrap 5: `cdn.jsdelivr.net/npm/bootstrap@5.3.2/`
- AdminLTE 4: `cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/`
- Bootstrap Icons: `cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/`

---

## 📞 Support & Contributing

- **Issues** — Report bugs or feature requests in GitHub Issues
- **Pull Requests** — Follow Laravel coding standards; include tests for new features
- **Documentation** — Update `README.md` for significant changes

---

## 📄 License

This project is licensed under the MIT License — see [LICENSE](LICENSE) file for details.

---

## 👨‍💼 Project Maintainers

- Built with Laravel 12 best practices
- Designed for scalable HRIS deployments
- Open to community contributions

---

## 🎓 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templating](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Laravel Testing](https://laravel.com/docs/testing)
- [DataTables Documentation](https://datatables.net/)
- [AdminLTE Documentation](https://adminlte.io/)

---

**Last Updated:** December 2025  
**Version:** 1.0.0
