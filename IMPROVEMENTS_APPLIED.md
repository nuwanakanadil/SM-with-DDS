# Improvements Applied

This version includes the manual UI, code quality, performance, and security improvements requested.

## Security
- Protected student routes with `role:student`.
- Disabled profile self-account deletion route.
- Removed the delete-account form from the profile page.
- Updated registration tests so public self-registration stays disabled.

## Permissions
- Updated `LmsRolePermissionSeeder` to use `App\Enums\Permissions` and `App\Enums\UserTypes`.
- Seeder now assigns full admin/staff/student permission sets consistently.
- Form requests now use the `Permissions` enum instead of raw permission strings.

## Code quality
- Added `AssessmentService` and moved assessment CRUD logic out of the controller.
- Added `AnalysisService` and moved dashboard analysis logic out of the controller.
- Cleaned up `User` model formatting.
- Updated `stubs/model.stub` to match Spatie Activity Log v5 imports used by the real models.

## Result handling
- Updated duplicate result validation to check soft-deleted records.
- Updated `AssessmentResultService` to restore/update a trashed result for the same student + assessment instead of failing on the unique constraint.

## Performance
- Added useful indexes to students, assessments, and assessment_results migrations.
- Updated student results query to sort through SQL instead of sorting in PHP collections.

## UI/theme
- Updated the app CSS theme tokens to a blue academic palette.
- Updated login page to use the shared UI components and a blue branded background.
- Replaced several hardcoded colors with theme classes.

## After extracting
Run:

```bash
composer install
npm install
php artisan optimize:clear
php artisan permissions:sync
php artisan migrate:fresh --seed
php artisan test
npm run build
```

If you do not want to reset the database, run `php artisan migrate` instead of `migrate:fresh --seed`, but note that edited existing migrations only apply automatically on a fresh database.
