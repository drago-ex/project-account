# Drago Project Account

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

## Security

- **Changing the email address requires the current password.** Password recovery goes to that address, so
  without it anyone with access to a signed-in session could take over the account.
- **Current password attempts are limited** to 5 failures per user in 15 minutes (`CurrentPasswordVerifier`,
  using the `RateLimiter` from `drago-ex/project-auth`).
- **New passwords** follow the same rules as registration (`PasswordPolicy` from `drago-ex/project-auth`):
  at least 12 characters, no required character classes, spaces allowed, at most 72 bytes.
- **Changing the password** replaces the user's session token, which signs out all other sessions; the current
  session stays signed in.

A new email address is not confirmed by a link sent to it. If your project needs that, add it to `ProfileFactory`.
