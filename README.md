# Sunnemeerten
## Scrum Project voor Noorderpoort

### Over dit project
Wij hebben de kans gekregen om de **Sunnemeerten app** te ontwikkelen nadat we als winnaars uit de **Deepdive** bij Noorderpoort zijn gekomen! Dit is een geweldige mogelijkheid om onze vaardigheden in **Scrum**, **ontwikkeling** en **samenwerking** toe te passen en een echte impact te maken.

### Wat is de Sunnemeerten app?
De Sunnemeerten app is bedoeld om informatie te vergaren aan eventuele gebruikers. Dit project zal ons niet alleen helpen bij het verfijnen van onze technische vaardigheden, maar ook bij het leren werken in een echte softwareontwikkelingsomgeving.

### Onze werkwijze
We werken volgens de **Scrum-methode**, wat betekent dat we:
- Werken in **sprints**
- Dagelijkse **stand-ups** houden
- Taken verdelen met behulp van een **product backlog**
- Regelmatig **retrospectives** houden om te leren en te verbeteren

### Team
Ons team bestaat uit een groepje gedreven studenten van Noorderpoort die samen werken aan dit innovatieve project. Samen zorgen we ervoor dat de Sunnemeerten app ontwikkeld wordt.

---

## Lokaal draaien

Nodig: PHP 8.1+ (bijvoorbeeld via XAMPP) en Node.js.

```bash
npm install
npm run dev
```

Open daarna http://localhost:6969. De eerste keer wordt er automatisch een lokale SQLite database (`storage/database.sqlite`) aangemaakt met nepdata en foto's. Je hoeft dus geen MySQL te installeren.

- `npm run dev` – bouwt de CSS en start de server op poort 6969
- `npm run watch` – bouwt de CSS opnieuw zodra je een bestand opslaat (in een tweede terminal)
- `npm run seed` – zet de lokale database terug naar de nepdata

### Testaccounts (alleen lokaal)

| Rol | E-mail | Wachtwoord |
| --- | --- | --- |
| Beheerder | admin@sunnemeerten.test | admin1234 |
| Gebruiker | anna@sunnemeerten.test | wachtwoord |

Andere testgebruikers staan in `database/seed.php`. `nieuw@sunnemeerten.test` heeft zijn e-mailadres nog niet bevestigd.

### Mails en GPS lokaal testen

- **Mails** worden lokaal niet verstuurd maar opgeslagen. Bekijk ze via de knop *Mailbox (dev)* linksonder of op http://localhost:6969/?page=mailbox.
- **Live GPS**: log in als beheerder, ga naar Beheer → Live GPS en klik op *Simuleer parade*. Open in een ander tabblad de Route-pagina om de parade te zien bewegen.

## Hoe de code in elkaar zit

```
public/            alles wat de browser mag zien (webroot)
  index.php        elke pagina loopt via dit bestand: ?page=naam
  uploads/         geüploade foto's (niet in git)
libs/
  core.php         instellingen, sessie, routing (PAGES) en helpers zoals e(), redirect(), csrfField()
  db.php           databaseverbinding (PDO) en dbAll(), dbOne(), dbRun(), dbInsert()
  auth.php         inloggen, rollen en validatie van accounts
  posts.php        posts, foto's en reacties ophalen
  upload.php       foto's controleren, verkleinen en opslaan
  mail.php         mails maken en versturen (SMTP) of lokaal opslaan
resources/views/   de pagina's: naam.view.php
database/          tabellen voor MySQL (live) en SQLite (lokaal) + nepdata
scripts/dev.php    start de lokale server
```

Een nieuwe pagina maken:
1. Maak `resources/views/naam.view.php` (kijk naar `login.view.php` als voorbeeld).
2. Zet de naam in `PAGES` in `libs/core.php` met wie hem mag zien: `guest`, `user` of `admin`.
3. Zet bovenaan de PHP-logica (formulier verwerken), daarna `header.view.php`, de HTML en `footer.view.php`.
4. Elk formulier krijgt `<?= csrfField() ?>` en elke POST begint met `verifyCsrf()`. Zet alles wat je toont door `e()`.

## Live zetten

1. **Hosting**: een pakket met PHP 8.1+ en MySQL (bijvoorbeeld Antagonist, Vimexx of Hostinger) en een domeinnaam. Zet HTTPS (Let's Encrypt) aan.
2. **Webroot**: laat het domein naar de map `public/` wijzen, niet naar de hoofdmap. Zo kan niemand `libs/`, `config.php` of `database/` openen.
3. **Bestanden**: zet de code op de server met `git clone`/`git pull` of via FTP. Draai lokaal `npm run build` en upload ook `public/css/style.css`.
4. **Database**: maak een MySQL database aan en importeer `database/mysql.sql` (via phpMyAdmin).
5. **Instellingen**: kopieer `config.example.php` naar `config.php` op de server en vul in: `'debug' => false`, `'driver' => 'mysql'` en de databasegegevens van je host.
6. **Uploads**: zorg dat de map `public/uploads/` schrijfbaar is. Zet in de PHP-instellingen van je host `upload_max_filesize` op 20M en `post_max_size` op 120M.
7. **Eerste beheerder**: registreer een account via de app en zet in phpMyAdmin bij dat account `is_admin` op `1`. Daarna kun je andere beheerders aanwijzen via Beheer → Gebruikers.
8. **Mail**: maak bij je host een mailadres aan (bijvoorbeeld noreply@jouwdomein.nl) en vul in `config.php` bij `mail` in: `'driver' => 'smtp'`, de SMTP-server, poort (587 met `tls` of 465 met `ssl`), gebruikersnaam en wachtwoord. Zet `app_url` op het echte adres (bijvoorbeeld `https://sunnemeerten.nl`), anders werken de links in de mails niet.
9. **Kaart**: standaard gebruikt de kaart OpenStreetMap. Verwacht je veel bezoekers, neem dan een gratis Mapbox-account en zet de Mapbox-link met je token bij `map.tile_url` (zie `config.example.php`).
10. **Live GPS**: de locatie van een telefoon kan alleen via **HTTPS** worden gelezen. Open op de dag van de parade op de telefoon die meeloopt Beheer → Live GPS, log in als beheerder en druk op Start. Laat het scherm aan en de telefoon aan de oplader.
11. **Testen**: doorloop op je telefoon registreren (en de bevestigingsmail), inloggen, foto plaatsen, goedkeuren, een nieuwsbericht, aanmelden voor een werkplaats en de live GPS.
