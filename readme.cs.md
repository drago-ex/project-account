# Drago Project Account

Správa účtu pro Drago Project.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-account.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-account)
[![Coding Style](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-account/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- [Drago Project](https://github.com/drago-ex/project)

## Instalace

```bash
composer require drago-ex/project-account
```

### Instalace přes npm

Rozhraní správy účtu potřebuje ke správné funkci přepínač motivů:
```bash
npm install theme-switcher-compostrap
```

## Soubory kopírované do projektu

Soubory do projektu kopíruje [drago-ex/project-tools](https://github.com/drago-ex/project-tools)
podle nastavení v `extra.drago-tools` v `composer.json` tohoto balíčku.

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

---

## Volitelné doplňky

Balíček funguje i bez nich. Pokud je projekt potřebuje, nastavte je ručně podle popisu níže.

### Provider oprávnění

S [drago-ex/permission](https://github.com/drago-ex/permission) se pravidla přístupu z [`bin/create-account-permission`](bin/create-account-permission)
vygenerují do projektu příkazem:

```bash
php vendor/bin/drago-commands
```
