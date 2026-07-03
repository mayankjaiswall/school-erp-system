# EduERP - School ERP System

EduERP is a Laravel 11 based school management system. It provides separate portals for super admins, principals, teachers, and parents so each role can manage the school data relevant to them.

The application currently includes school administration, user and role management, teacher/student/parent management, class and subject setup, attendance, exams, marks, remarks, report cards, profile management, and PDF report card export.

## Technology Stack

- PHP 8.2+
- Laravel 11
- MySQL or MariaDB
- Blade views
- Bootstrap Icons and custom Blade/CSS layouts
- Vite for frontend assets
- `barryvdh/laravel-dompdf` for PDF generation

## Main Features

- Public landing page and login flow
- Role based dashboard access using `auth` and `role` middleware
- Super admin panel for schools, roles, users, and subscription plans
- Principal panel for teachers, classes, students, parents, subjects, teacher-subject assignment, attendance review, exams, results, report cards, and school users
- Teacher panel for assigned class view, students, attendance entry/reporting, marks entry, remarks, and report cards
- Parent panel for children, attendance, results, report cards, remarks, and profile/password management
- Shared account profile and password screens
- Student and teacher import template/download flows
- Uploaded profile photos served from Laravel public storage

## Roles

The seeded roles are:

- `super_admin`
- `principal`
- `admin`
- `hod`
- `teacher`
- `parent`
- `student`

Route access is controlled by [EnsureRole.php](app/Http/Middleware/EnsureRole.php).

## Project Structure

```text
app/
  Http/Controllers/
    Admin/        Super admin controllers
    Principal/    Principal panel controllers
    Teacher/      Teacher panel controllers
    Parent/       Parent panel controller
    Auth/         Login/logout controller
  Models/         Eloquent models
  Services/       Import services for students and teachers

database/
  migrations/     Database schema
  seeders/        Roles, super admin, and sample parent/student links

resources/views/
  admin/          Super admin screens
  principal/      Principal panel screens
  teacher/        Teacher panel screens
  parent/         Parent panel screens
  account/        Shared profile/password screens
  layouts/        Role-specific layouts and shared account menu

routes/
  web.php         Main route loader and shared account routes
  frontend.php    Public home/login/logout routes
  principal.php   Principal portal routes
  teacher.php     Teacher portal routes
  parent.php      Parent portal routes
  admin.php       Admin dashboard route
```

## Installation

1. Clone the repository and enter the project directory.

```bash
git clone <repository-url>
cd school-erp-system
```

2. Install PHP dependencies.

```bash
composer install
```

3. Install frontend dependencies.

```bash
npm install
```

4. Create the environment file.

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

5. Generate the Laravel application key.

```bash
php artisan key:generate
```

6. Configure database settings in `.env`.

```env
APP_NAME=EduERP
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_erp_system
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations and seeders.

```bash
php artisan migrate --seed
```

8. Create the public storage link for uploaded files.

```bash
php artisan storage:link
```

9. Build frontend assets.

```bash
npm run build
```

For active frontend development, use:

```bash
npm run dev
```

10. Start the Laravel development server.

```bash
php artisan serve
```

Open the application at:

```text
http://127.0.0.1:8000
```

## Seeded Login

The default super admin account is created by [SuperAdminSeeder.php](database/seeders/SuperAdminSeeder.php).

```text
Email: admin@eduerp.com
Password: password
```

After login, the super admin can create schools, roles, and school users such as principals.

## Route Map

Public routes:

- `/` - landing page
- `/login` - login form
- `/logout` - logout POST route

Shared authenticated account routes:

- `/account/profile`
- `/account/password`

Super admin routes:

- `/admin/dashboard`
- `/admin/schools`
- `/admin/roles`
- `/admin/users`
- `/admin/subscription-plans`

Principal routes:

- `/principal/dashboard`
- `/principal/teachers`
- `/principal/classes`
- `/principal/students`
- `/principal/parents`
- `/principal/subjects`
- `/principal/teacher-subjects`
- `/principal/attendance`
- `/principal/exams`
- `/principal/reports/results`
- `/principal/report-cards`
- `/principal/users`

Teacher routes:

- `/teacher/dashboard`
- `/teacher/profile`
- `/teacher/students`
- `/teacher/attendance`
- `/teacher/marks`
- `/teacher/report-cards`
- `/teacher/remarks`

Parent routes:

- `/parent/dashboard`
- `/parent/children`
- `/parent/attendance`
- `/parent/results`
- `/parent/report-cards`
- `/parent/remarks`
- `/parent/profile`

## Important Development Notes

- User role access depends on the `roles.slug` value. If you add a new role, update routing, middleware usage, and layouts as needed.
- Profile photos and other uploaded public files are stored under `storage/app/public` and served through `public/storage`. Run `php artisan storage:link` after setup.
- Report card PDF generation uses DomPDF. Related logic is in [ReportCardController.php](app/Http/Controllers/ReportCardController.php) and [pdf.blade.php](resources/views/report-cards/pdf.blade.php).
- Student import logic is in [StudentImportService.php](app/Services/StudentImportService.php).
- Teacher import logic is in [TeacherImportService.php](app/Services/TeacherImportService.php).
- Role specific layouts are in `resources/views/layouts`.

## Common Commands

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan route:list
php artisan optimize:clear
npm run dev
npm run build
```

## Testing

Run the PHPUnit test suite with:

```bash
php artisan test
```

The current tests are starter examples. Add feature tests around role access, attendance, marks, imports, and report card generation when changing those areas.

## Contribution Guidelines

- Keep controller changes scoped to the role area they belong to.
- Prefer existing Blade/layout patterns before creating new UI patterns.
- Use migrations for database changes.
- Validate all form requests in controllers before saving.
- Do not commit `.env`, uploaded files, or generated cache files.
- Run `php artisan test` and `npm run build` before opening a pull request when possible.
