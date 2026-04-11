# Project Utility File Organization

This project now separates utility files by purpose:

- `scripts/setup/`: one-time environment/bootstrap scripts.
  - Example: `create_admin.php` belongs here (it seeds required admin data, not a test).
- `scripts/debug/`: temporary troubleshooting scripts used while diagnosing issues.
- `tests/manual/`: repeatable verification scripts/pages used to validate behavior (e.g., DB connection checks).
- `storage/logs/`: runtime/debug output logs.

## Should test files go under debug?

No. Keep them separate:

- Put **tests** in `tests/` because they are intended to be re-run and can stay in the project.
- Put **debug scripts** in `scripts/debug/` because they are ad-hoc diagnostics and usually temporary.

This separation makes the project cleaner and easier to grade/maintain for university-level full-stack work.
