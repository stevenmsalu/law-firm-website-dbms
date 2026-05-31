# Zimba & Partners — Law Firm Website (DBMS)

PHP/MySQL law firm website with public pages, contact form, authentication, and case messaging.

## Quick start (XAMPP)

1. Copy this project into `htdocs` (e.g. `C:\xampp\htdocs\law-firm-website-dbms`).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open the setup URL in your browser:

   **http://localhost/law-firm-website-dbms/database/setup.php**

   This creates the database, tables, and default admin user in one step.

4. Visit the site:

   **http://localhost/law-firm-website-dbms/**

   (redirects to the public home page)

## Default admin login

| Field    | Value                 |
|----------|-----------------------|
| Email    | `admin@lawfirm.com`   |
| Password | `admin123`            |

Login: **http://localhost/law-firm-website-dbms/auth/login.php**

## Project layout

| Folder / file | Purpose |
|---------------|---------|
| `public/` | Public website pages and form handlers |
| `auth/` | Login, logout, and session checks |
| `dashboard/` | Admin, lawyer, and client areas |
| `database/` | **All database files** (see below) |
| `config/` | App paths and session (`app.php`) |
| `includes/` | Shared page layout (header, footer) |
| `assets/` | CSS, JavaScript, and images |
| `index.php` | Root redirect to the public home page |

### Database folder (`database/`)

| File | Purpose |
|------|---------|
| `config.php` | MySQL host, database name, user, and password |
| `schema.sql` | Table definitions |
| `setup.php` | One-time browser setup (schema + admin seed) |
| `connection.php` | PDO connection used by the running application |

**Edit credentials in one place:** `database/config.php`

**Application pages load the database with:**

```php
$pdo = require __DIR__ . '/../database/connection.php';
```

(Adjust `../` based on how deep the PHP file is in the folder tree.)

## Runtime folder (not committed)

- `uploads/` — case message attachments (created automatically when needed)