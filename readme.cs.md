# Drago Project Account

Správa účtu pro Drago Project.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Bootstrap
- Naja
- Node.js
- Základní balíčky Drago Project

## Instalace

```bash
composer require drago-ex/project-account
```

### Instalace přes npm

Rozhraní správy účtu potřebuje ke správné funkci přepínač motivů:
```bash
npm install theme-switcher-compostrap
```

## Použití

Balíček přidává presenter `Account` pro správu účtu přihlášeného uživatele.
Záměrně stojí mimo moduly veřejné části i administrace, takže na stejnou
stránku účtu lze odkázat z obou částí aplikace.

Presenter používá služby `drago-ex/project-auth` pro přihlášeného uživatele,
přístup k repozitáři a hashování hesel.

Stránka účtu nabízí dva formuláře:

- úpravu údajů profilu
- změnu hesla s ověřením současného hesla

## Bezpečnost
- Změna e-mailové adresy vyžaduje současné heslo.
- Pokusy o zadání současného hesla jsou omezené na 5 neúspěchů na uživatele za 15 minut.
- Nová hesla se řídí pravidly `drago-ex/project-auth` (nejméně 12 znaků).
- Změna hesla odhlásí všechny ostatní relace.
- Nová e-mailová adresa platí až po potvrzení odkazem; původní adresa dostane upozornění.

## Provider oprávnění

S [drago-ex/permission](https://github.com/drago-ex/permission) se pravidla přístupu z [`permission.php`](permission.php)
vygenerují do projektu příkazem:

```bash
php vendor/bin/create-permission --packages
```
