# Stage ET2S Production Dashboard

## Overview
This repo contains the Symfony 5.3 web application built during the 2021 ET2S summer internship. The app digitizes the shop-floor quality workflow: engineers define the products and their manufacturing steps, operators log every step result (OK/NOK) with comments, and team leads get a dashboard that highlights success/failure rates in real time.

## Core Features
- **Operational dashboard** – Chart.js bar charts plus DataTables summarize success vs failure per step and per product, with drill-down views for any product ID.
- **Product & step catalogs** – CRUD screens let admins maintain the master data for `Product` (code + description) and `ProdSteps` (description + unique color used in charts).
- **Quality check capture** – Operators record step executions (`ProdHistory`) including serial number, operator id, comments, timestamp, and pass/fail validation. Duplicate submissions on the same step/serial are blocked.
- **History search & export** – Filter the full production history by product ID or serial number and export/print the resulting table through the built-in HTML-to-pdfmake integration.
- **User management & security** – Symfony Security handles authentication, role-based navigation (admin-only user management), and password reset flows.

## Tech Stack
- PHP 7.4+/Symfony 5.3, Doctrine ORM, Twig, Symfony Security & Reset-Password bundles
- Front-end assets: Bootstrap (Gentelella theme), Font Awesome, Chart.js, jQuery DataTables, pdfmake/html-to-pdfmake
- Persistence: MySQL/MariaDB (configurable through `DATABASE_URL`)

## Getting Started
1. **Clone & install PHP deps**
   ```bash
   git clone <repo-url> && cd summer-internship-2021
   composer install
   ```
2. **Configure environment**
   ```bash
   cp .env .env.local
   # edit .env.local: set DATABASE_URL, MAILER_DSN, APP_ENV, etc.
   ```
3. **Prepare the database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
4. **(Optional) rebuild front assets** – Static vendor bundles are committed, but if you need to tweak them run `npm install` (or `yarn`) inside `public/` and use the provided `gulpfile.js`.
5. **Run the app**
   ```bash
   symfony server:start
   # or
   php -S 127.0.0.1:8000 -t public
   ```
6. **Create a user**
   - Use the `/user/new` form (requires an existing admin account) **or**
   - Manually insert a user row, hashing the password via `php bin/console security:hash-password`.

## Daily Workflow
1. Admin defines/updates products (`/product`) and the ordered production steps (`/prod/steps`) assigning unique colors for reporting.
2. Operators go to **Quality Check** (`/prod/history/new`) to log each step outcome, serial number, and comments.
3. The **Home dashboard** visualizes OK/NOK percentages per step and per product, and links to per-product stats pages.
4. Leads review the **Product History** listing, filter by product or serial, and export PDF-ready reports directly from the UI.

## Useful Commands
- Clear cache: `php bin/console cache:clear`
- Run tests: `php bin/phpunit`
- List routes: `php bin/console debug:router`

## Troubleshooting
- **Charts show “No data to show”** – Load `ProdHistory` data first; the dashboard requires at least one history record.
- **Login loop** – Ensure sessions directory is writable (`var/`) and that `APP_ENV`/`APP_SECRET` are set.
- **Password reset emails fail** – verify `MAILER_DSN` in `.env.local`, the Reset Password bundle uses it directly.
