# Drago Project Account

Account management for Drago Project.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/project-account/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
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
