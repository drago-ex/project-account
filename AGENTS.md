# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/project-account`
- **Type**: drago-tools-resource
- **Purpose**: Account management for Drago Project.
- **PHP**: >=8.3 <9
- **Runtime dependencies**: drago-ex/translator (^4.0), drago-ex/component (^3.0), drago-ex/project-auth (^1.0)
- **License**: MIT

## Repository Layout
- `permission.php`: basic access rules for `drago-ex/permission`, declared in `extra.drago-permission` and generated into the project by `create-permission --packages`.
- `resources/`: application resources contributed to the consuming Drago project.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- The account screen uses the user model of `project-auth`; changing the password generates a new user token, which ends other sessions, and signs the current session in again with it.
- Form limits must match the `users` columns (name 50, email 254 characters).

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include nette/tester (^2.5), phpstan/phpstan-nette (^2.0), drago-ex/project-tools (^1.0).

## Essential Commands

```bash
composer phpstan                # phpstan analyse
composer tester                 # tester tests -s -C
```

## Tests

Nette Tester tests are in `tests/`; run them with `composer tester`. `tests/bootstrap.php` autoloads `App\` classes from `resources/app`, then from `.dev-resource/app`, where `drago-install --dev` copies the resources of the dependencies (e.g. `drago-ex/project-auth`).

## Installation Hooks

Composer install and update hooks invoke Drago project setup/cleanup commands. Inspect those scripts and their effects before invoking Composer operations that trigger them; they may modify generated project files.

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Keep package-specific configuration, resources, and migrations in their established directories. Do not edit generated or consuming-project files without confirming they are part of this repository’s intended output.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
