# Agent Guide

`drago-ex/project-account` (type drago-tools-resource, PHP >=8.3 <9): the account page of the signed-in user
(profile and password). The workspace rules are in `../AGENTS.md`; dependencies are in `composer.json`.

## Layout
- `resources/app/Presentation/Account`: `AccountPresenter`, `@layout.latte`, `Factory/` (profile, password,
  `CurrentPasswordVerifier`), `conf.neon`, `acl.neon` (resource `Account`, signed-in users), `Translate/cs.neon`.
- `resources/assets/account.js` + `.scss`: an own Vite entry; `package.json` (with `name`) brings its npm
  dependencies through `npm-loader.js` of drago-ex/project.

## How it works
- It uses the user model of project-auth (repository, password policy, email verification). Changing the password
  generates a new user token, which ends the other sessions, and signs the current session in again with it.
- Form limits must match the `users` columns of project-auth (name 50, email 254 characters).
- project-auth must not know this package (no `class_exists()` checks there).

## Commands
```bash
composer phpstan
composer tester
```
