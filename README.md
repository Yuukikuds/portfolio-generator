# PortfolioCraft - Online Portfolio Template Generator (Laravel)

PortfolioCraft lets a user create a professional portfolio, choose one of three templates, preview it, and edit or delete it later.
This version is written in **PHP (Laravel) and Blade only**. It uses the same **Railway PostgreSQL** database and the same **Railway** deployment as the earlier Node.js version.

## Objectives

- Build a complete full-stack web application with real CRUD operations.
- Store portfolio data online so it survives refreshes and restarts.
- Show the same portfolio data in three clearly different templates.
- Deploy the application publicly on Railway.

## Features

- Home page, portfolio form, template selection, preview, and manage pages
- Personal information, profile picture, education, skills, projects, work experience, and social links (multiple entries with Add / Remove buttons)
- Server-side validation with friendly error messages
- Exactly three templates: **Simple**, **Modern**, **Creative**
- Edit, change template, and delete (with a confirmation page)
- Light and dark mode (saved in a cookie, no JavaScript needed)
- Responsive layout for desktop, tablet, and mobile
- Accessible forms (labels, keyboard focus, alt text)

## Technologies

| Part | Technology |
|---|---|
| Language and framework | PHP 8.2+ and Laravel |
| Views | Blade templates (no JavaScript framework) |
| Styling | Plain CSS (`public/css/app.css`, `public/css/templates.css`) |
| Database | Railway PostgreSQL (via Eloquent / PDO) |
| Deployment | Railway |
| Source control | Git and GitHub |

## Architecture

```
Browser -> Railway web service (Laravel, PHP)
              |-- routes/web.php        pages and forms
              |-- Blade views           HTML pages and the three templates
              `-- Eloquent model        Portfolio
                        |
                        v
                  Railway PostgreSQL  (DB_URL, server side only)
```

## Folder structure (the files PortfolioCraft adds to Laravel)

```
app/
  Http/Controllers/PortfolioController.php   all page logic (create, edit, preview, delete ...)
  Models/Portfolio.php                       the portfolios table
  Providers/AppServiceProvider.php           shared helpers, HTTPS and database setup
  Support/PortfolioForm.php                  form data, validation, profile picture
  Support/Tpl.php                            small helpers for the templates
  Support/Icons.php                          inline SVG icons
database/
  schema.sql                                 the portfolios table (same as the Node.js version)
  migrations/2026_10_08_000000_create_portfolios_table.php
public/css/app.css, templates.css            design system, dark mode, and the three template designs
resources/views/
  layouts/app.blade.php                      page layout
  partials/                                  navbar, footer, flash messages, stepper, avatar
  components/                                input, textarea, select
  home.blade.php
  portfolios/                                form, templates, preview, index (Manage), confirm-delete
  templates/                                 simple, modern, creative
  errors/                                    404, 419, 500, 503
routes/web.php                               all routes
```

## Database

One table, `portfolios`, created from `database/schema.sql`. The repeating sections (education, skills, projects, work_experience, social_links) are **JSONB arrays**. The profile picture is cropped and compressed on the server and stored as a data URL, so it works the same locally and on Railway.

The migration uses `CREATE TABLE IF NOT EXISTS`, so portfolios created by the earlier Node.js version are kept and still appear on the Manage page.

## Routes

| Method | URL | Purpose |
|---|---|---|
| GET | `/` | Home |
| GET | `/create`, POST `/portfolios` | Create (CREATE) |
| GET | `/edit/{id}`, PUT `/portfolios/{id}` | Edit (UPDATE) |
| GET | `/manage` | List portfolios (READ) |
| GET | `/templates/{id}`, POST `/templates/{id}` | Choose a template |
| GET | `/preview/{id}`, PATCH `/preview/{id}` | Preview, save the template |
| GET | `/portfolios/{id}/delete`, DELETE `/portfolios/{id}` | Confirm and delete (DELETE) |
| GET | `/api/health`, `/api/portfolios`, `/api/portfolios/{id}` | Read-only JSON for testing |
| GET | `/up` | Laravel health check |

## Local installation (Windows)

1. Install PHP: `winget install PHP.PHP.8.4`, then open a new Command Prompt.
2. Install packages: `composer install`
3. Copy `.env.example` to `.env`, set `DB_URL` to your Railway **public** connection URL, then run `php artisan key:generate`.
4. Create the table: `php artisan migrate --force`
5. Start the site: `php artisan serve`, then open http://127.0.0.1:8000

## Environment variables

| Variable | Meaning |
|---|---|
| `APP_KEY` | Laravel encryption key (`php artisan key:generate --show`) |
| `APP_ENV`, `APP_DEBUG` | `local` / `true` on your computer, `production` / `false` on Railway |
| `APP_URL` | The site address |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | Railway PostgreSQL connection URL (never commit it). On Railway use `${{Postgres.DATABASE_URL}}` |
| `LOG_CHANNEL` | `stderr` on Railway so logs appear in the dashboard |

## Railway deployment

1. Push this project to GitHub.
2. In the existing Railway web service, clear any custom Node.js build or start commands.
3. Set the variables above (`APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=pgsql`, `DB_URL=${{Postgres.DATABASE_URL}}`, `LOG_CHANNEL=stderr`).
4. Deploy. Railway detects Laravel from `composer.json`, installs packages, and starts the site.

## Testing

- `/up` and `/api/health` should both report that the app and database are running.
- Create, edit, preview, change template, and delete a portfolio, then check the data in the Railway database.
- Test the light and dark switch, and the layout at phone width.

## Troubleshooting

| Problem | Fix |
|---|---|
| 500 error on Railway | Set `APP_KEY` and check the deploy logs |
| `could not find driver` | Enable `pdo_pgsql` and `pgsql` in `php.ini` |
| Database connection refused | Locally use the **public** Railway URL in `DB_URL`; on Railway use `${{Postgres.DATABASE_URL}}` |
| Photos are not resized | Enable the `gd` extension (locally in `php.ini`, on Railway set `RAILPACK_PHP_EXTENSIONS=gd`) |
| Styles missing | Run `php artisan optimize:clear` and refresh |
