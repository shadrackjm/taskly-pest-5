# Taskly: Testing Laravel 13 with Pest 5

Code for the video course **Pest Testing in Laravel: The Only Guide You Need** (Testing Laravel with Pest 5:
The Complete Course) on the Code with SJM YouTube channel:
https://www.youtube.com/watch?v=ps1pyQq5MQ8

Taskly is a small task manager (sign in, create, edit, complete and search tasks) with a full Pest 5 test
suite: unit, feature, database, auth, fakes and mocks, validation, architecture, browser (Playwright),
snapshot, stress, coverage, mutation, Rector/PHPStan and AI evals. This repo is the finished code at the end
of episode 20.

## What you need
- **PHP 8.4 or newer** (Pest 5 will not install on PHP 8.3). Laravel Herd works.
- Composer, Node.js 22+ and npm
- SQLite (built into PHP, nothing to install)
- Windows, macOS or Linux

## Setup
```bash
git clone https://github.com/shadrackjm/taskly-pest-5.git
cd taskly-pest-5
composer install
copy .env.example .env        # macOS/Linux: cp .env.example .env
php artisan key:generate
php -r "touch('database/database.sqlite');"   # creates the empty SQLite database file
php artisan migrate
```

For the browser tests (episode 12) install Node packages and the Playwright browser once:
```bash
npm install
npx playwright install chromium
```

To open the app in the browser:
```bash
npm run build
php artisan migrate:fresh --seed
php artisan serve             # then open http://127.0.0.1:8000
```
Sign in with the seeded user: `test@example.com` / `password`.

## Run the tests
```bash
php artisan test              # the whole suite
./vendor/bin/pest             # the same, with Pest directly
./vendor/bin/pest --filter=priority          # only tests whose name matches
./vendor/bin/pest tests/Feature/TaskCreationTest.php
./vendor/bin/pest --parallel                 # episode 16
```
On Windows PowerShell write `vendor\bin\pest` instead of `./vendor/bin/pest`.

The **evals** in `tests/Evals` are skipped unless you run `./vendor/bin/pest --evals` with an AI provider key
in `.env` (episode 19). The **stress test** in `tests/Stress` is not part of the normal suite: it loads
`APP_URL/login`, so set `APP_URL` in `.env` to the address the app runs at (for example `http://127.0.0.1:8000`
with `php artisan serve`, or `http://taskly.test` on Herd), start the app, then run
`./vendor/bin/pest tests/Stress` (episode 13).

### Coverage, mutation and the Tia engine (episodes 14 and 15)
These need a coverage driver. On Laravel Herd: **Herd -> PHP -> the 8.4 row -> turn Xdebug on** (turn it
off again afterwards, it makes every run slower).
```bash
./vendor/bin/pest --coverage
./vendor/bin/pest --mutate --path=app
./vendor/bin/pest --tia       # first run records, later runs only run the tests your change affects
```

### Code quality (episode 17)
```bash
./vendor/bin/phpstan analyse
./vendor/bin/rector process --dry-run
./vendor/bin/pint --test
```
The GitHub Actions workflow in `.github/workflows/tests.yml` runs the suite and these checks on every push.

## What is where
| Path | What it is | Episodes |
|---|---|---|
| `app/Support/Calculator.php` | Plain logic for the first unit tests and TDD | 1-5 |
| `app/Models/Task.php`, `app/Enums/TaskPriority.php` | The task model and a backed enum | 3, 7 |
| `app/Policies/TaskPolicy.php` | Only the owner may see or change a task | 8 |
| `app/Events`, `app/Jobs`, `app/Mail`, `app/Services/HolidayService.php` | Things the tests fake | 9 |
| `app/Http/Requests` | Form requests with validation rules | 10 |
| `app/Agents/TaskTriageAgent.php` | A Laravel AI agent, tested with evals | 19 |
| `tests/Unit`, `tests/Feature` | Unit and feature tests | 1-10, 20 |
| `tests/Datasets` | Shared datasets | 5 |
| `tests/Arch` | Architecture rules with `arch()` | 11 |
| `tests/Browser` | Real Chrome tests with Playwright | 12 |
| `tests/Feature/SnapshotTest.php`, `tests/Stress` | Snapshot and stress tests | 13 |
| `tests/Evals` | AI evals (skipped by default) | 19 |

Versions used: Laravel 13.29, PHP 8.4, Pest 5.1.3, PHPUnit 13, Playwright, SQLite.
Docs: https://pestphp.com/docs

Subscribe to Code with SJM for more Laravel courses.
