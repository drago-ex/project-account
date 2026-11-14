# Agent Guide

`drago-ex/project-account` (type drago-tools-resource, PHP >=8.3 <9): the account page of the signed-in user
(profile and password). The workspace rules are in `../AGENTS.md`; dependencies are in `composer.json`.

## Layout
- `resources/app/Presentation/Account`: `AccountPresenter`, `@layout.latte`, `Factory/` (profile, password,
  `CurrentPasswordVerifier`), `conf.neon`, `acl.neon` (resource `Account`, signed-in users), `Translate/cs.neon`.
- `resources/assets/account.js` + `.scss`: an own Vite entry; `package.json` (with `name`) brings its npm
  dependencies through `npm-loader.js` of drago-ex/project.
- The page takes the frame of the compostrap module `dashboard` without a sidebar (`navbar navbar-content`, `content-box`); the three
  `account-*` classes of `account.scss` only set the width and the footer. The user menu of the navbar (`navbar-user`: website,
  administration, theme as `btn-group-segmented` with `data-theme-value`, sign out) replaces the dropdown widgets; the link to the
  administration is shown by `Privilege::isAllowed()`, which is false also in a project without the backend.
- The forms stand next to each other (no tabs). The new password is written in the template with the strength meter (`strength` +
  `strength-text`), like in project-auth: the names of the levels and `PasswordPolicy::MinLength` are data attributes of the body, which
  `account.js` passes to `Strength.init()`.
- The name is shown in two snippets (`user` in the navbar, `identity` above the forms). After the profile is saved the presenter calls
  `User::refreshStorage()`, so the identity is read again from the database, and redraws both; their `{var}` lines are inside the
  snippets, because a snippet drawn alone does not see the variables of the template around it.

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
