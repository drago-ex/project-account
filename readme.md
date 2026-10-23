# Drago Project Account

English | [Čeština](readme.cs.md)

Account management for Drago Project.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Bootstrap
- Naja
- Node.js
- Drago Project core packages

## Installation

```bash
composer require drago-ex/project-account
```

### npm Installation

The account management UI requires the theme switcher for proper functionality:
```bash
npm install theme-switcher-compostrap
```

## Usage

The package adds an `Account` presenter for managing the current user account.
It is intentionally outside the frontend and backend modules, so the same account
screen can be linked from both parts of the application.

The presenter uses `drago-ex/project-auth` services for the current user,
repository access and password hashing.

The account screen provides two forms:

- profile information update
- password change with current password verification

## Permission provider

With [drago-ex/permission](https://github.com/drago-ex/permission), the access rules from [`permission.php`](permission.php)
are generated into the project by:

```bash
php vendor/bin/create-permission --packages
```

## Security
- Changing the email address requires the current password.
- Current password attempts are limited to 5 failures per user in 15 minutes.
- New passwords follow the rules of `drago-ex/project-auth` (at least 12 characters).
- Changing the password signs out all other sessions.
- A new email address applies after it is confirmed by a link; the previous address gets a notice.
