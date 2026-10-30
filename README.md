# Marks & Ranking System

Laravel 12 + Vue/Inertia application for student records, exam marks, Walala/Mathale analysis, per-exam rankings, and public result lookup.

## Main workflows

- Exams can target Walala, Mathale, or both groups and include academic year, term, and exam type.
- Ranks are calculated per exam: one rank inside the student's group and one across both groups in the same grade.
- Admin/staff can enter marks individually or with the bulk-entry grid and export students/results as CSV.
- The Data Quality page identifies unassigned students, affected legacy results, draft-exam results, and recent audit activity.
- Student accounts can use the private dashboard, result history, and placement pages.
- Public lookup supports an optional per-student numeric access code stored as a secure hash.

## Local setup

Requirements: PHP 8.2+, Composer, Node.js 20+, and MySQL 8+.

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm run build
composer run dev
```

For the included Docker database:

```bash
docker compose up -d --wait database
php artisan migrate
```

MySQL is exposed on port `3307` and phpMyAdmin on `http://localhost:8081` by default. Never commit `.env` or real database dumps.

## Importing a production copy safely

Use a separate local database name, import the dump there, update only the local `.env`, then run:

```bash
php artisan config:clear
php artisan migrate
```

Keep the original local database until the imported copy has been verified. Avoid running development commands against production credentials.

## Verification

```bash
php artisan test
npm run build
```

## Deployment checklist

1. Back up the database and uploaded files.
2. Put the application in maintenance mode.
3. Deploy code and run `composer install --no-dev --optimize-autoloader`.
4. Run `php artisan migrate --force` and `npm ci && npm run build` (or deploy prebuilt assets).
5. Run `php artisan optimize`, restart queue workers, then leave maintenance mode.
6. Verify public lookup, admin login, bulk marks, rankings, and the Data Quality page.

Schedule automated encrypted database backups and test a restore regularly. Restrict backup access separately from application access.

## Data migration notes

The academic-scope migration infers an existing exam's group when all assigned result records belong to one valid group. Exams containing both groups—or only unassigned legacy students—remain scoped to both groups. Review these on the Exams and Data Quality pages after deployment.
