# Drago Project Account

The account page of the signed-in user in Drago Project: changing the name, the email address and the password.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- [Drago Project](https://github.com/drago-ex/project)
- [Project Auth](https://github.com/drago-ex/project-auth)

## Installation

```bash
composer require drago-ex/project-account
```

[Project Tools](https://github.com/drago-ex/project-tools) copies the files of the package into the project,
where you adapt them. The page has its own entry point `assets/account.js`; its npm dependencies (theme switcher,
Font Awesome) are installed by `npm install` of the project.

## Usage

```latte
{link :Account:}
```

The `Account` presenter is outside the front and backend modules, so both can link it. It has two forms:
the profile (name and email) and the password change.

- Changing the email address requires the current password; the new address applies after it is confirmed by
  a link, and the previous address gets a notice.
- Wrong current passwords are limited to 5 per user in 15 minutes.
- New passwords follow the rules of Project Auth; changing the password signs out all other sessions.

## Permissions

The rules are in `Account/acl.neon`: the resource `Account`, for signed-in users.
