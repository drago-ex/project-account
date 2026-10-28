# Drago Project Account

Account management for Drago Project.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- [Drago Project](https://github.com/drago-ex/project)

## Installation

```bash
composer require drago-ex/project-account
```

### npm Installation

The account management UI requires the theme switcher for proper functionality:
```bash
npm install theme-switcher-compostrap
```

## Files copied into the project

The files are copied into the project by [drago-ex/project-tools](https://github.com/drago-ex/project-tools),
as set in `extra.drago-tools` of this package's `composer.json`.

## Usage

The package adds an `Account` presenter for managing the current user account.
It is intentionally outside the frontend and backend modules, so the same account
screen can be linked from both parts of the application.

The presenter uses `drago-ex/project-auth` services for the current user,
repository access and password hashing.

The account screen provides two forms:

- profile information update
- password change with current password verification

## Security
- Changing the email address requires the current password.
- Current password attempts are limited to 5 failures per user in 15 minutes.
- New passwords follow the rules of `drago-ex/project-auth` (at least 12 characters).
- Changing the password signs out all other sessions.
- A new email address applies after it is confirmed by a link; the previous address gets a notice.

---

## Optional add-ons

The package works without them. Set them up by hand as described below when the project needs them.

### Permission provider

With [drago-ex/permission](https://github.com/drago-ex/permission), the access rules from [`bin/create-account-permission`](bin/create-account-permission)
are generated into the project by:

```bash
php vendor/bin/drago-commands
```
