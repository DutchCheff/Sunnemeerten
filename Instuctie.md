# Project Setup & Development Guide

## 1. Pagina aanmaken

- Ga naar de map `Views/`.
- Maak een bestand aan met de naam: `pagina.view.php`  
  Voorbeeld: `login.view.php`

- Zorg ervoor dat het bestand deze structuur volgt:

  Bovenaan (eerste regel of vlak erna):

  ```php
  <?php require_once '../resources/views/header.view.php'; ?>
  ```
  Onderaan (laatste regel van het bestand):
  ```php
  <?php require_once '../resources/views/footer.view.php'; ?>
  ```
  Deze `require_once` statements zorgen ervoor dat de ``<html>``, ``<head>``, en ``<body>`` tags, alsook gedeelde scripts en layouts automatisch worden geladen.

## 2. Lokale PHP-server starten

- Open een geïntegreerde terminal en navigeer naar de map `public/`:

    ```bash
    cd public
    php -S localhost:8000
    ```
## 3. Pagina bekijken in de browser

- Open de volgende URL in je browser:
    ```
    http://localhost:8000/public?page=<page-name>
    ```
    of
    ```
    http://localhost:8000/?page=<page-name>
    ```
    Vervang `<page-name>` door de naam van de pagina die je hebt aangemaakt (zonder .view.php).

    Voorbeeld:
    ```
    http://localhost:8000/public?page=login
    ```
    Let op: dit is geen live server. Je moet de pagina handmatig verversen (refresh) om veranderingen te zien.

## 4. Tailwind CSS gebruiken

1. Open een nieuwe terminal in de hoofdmap van het project (niet in /public).
2. Start de Tailwind compiler met het volgende commando:
    ```bash
    npm run watch
    ```
3. Als dit niet werkt, installeer eerst de projectafhankelijkheden:
    ```bash
    npm install
    ```

## Oude tekst 

De pagina die je wilt maken komt in de folder Views met deze naam: (naam).view.php voorbeeld: login.view.placeholder-purple-50
Vervolgens open je een intergrated terminal in /public en voer je het volgende in: php -S localhost:8000
Hierna kan je in de browser naar het volgende toegaan: localhost:8000/public?page=(naam) voorbeeld: localhost:8000/public?page=login
dit is geen live server dus om veranderingen te zien moet je refreshen
vervolgens in de pagina die je aan het maken bent zet je