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

## Database configuration

Connection settings are in `config/database.php` (host, database name, user, password). They must match the values used in `database/setup.php`.

## Project layout

- `public/` — public website pages
- `auth/` — login and logout
- `dashboard/` — admin, lawyer, and client areas
- `database/` — `schema.sql` and browser setup (`setup.php`)
- `config/` — app paths and database connection
- `includes/` — shared layout and DB include
- `assets/` — CSS, JavaScript, and images
