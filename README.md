# Project File Organization

This project separates files by purpose:

- `public/`: public-facing pages and form handlers
- `auth/`: login, logout, and session checks
- `dashboard/`: admin, lawyer, and client areas
- `includes/`: shared layout and database connection
- `config/`: application and database settings
- `database/`: SQL schema and seed scripts
- `scripts/setup/`: one-time setup (e.g. `init_db.php`)
- `assets/`: CSS, JavaScript, and images
- `storage/logs/`: runtime log output (not committed)
- `uploads/`: case attachments and uploaded files (not committed)
