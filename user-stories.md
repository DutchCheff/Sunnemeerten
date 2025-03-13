# Functioneel Ontwerp

Versienummer :
Auteur :
Studentnummer :
Klas :
Datum :

<div style="page-break-after: always;"></div>

## Versiebeheer

| Datum      | Versie | Wie   | Wijzigingen |
| ---------- | ------ | ----- | ----------- |
| 2025-03-11 | 1.0    | Wiebe     |         
| 2025-03-13 | 1.0.1  | Wiebe & Laurens     | Admin pagina beschreven|             |
|            |        |       |             |

<div style="page-break-after: always;"></div>

## Inhoudsopgave

- [Functioneel Ontwerp](#functioneel-ontwerp)
  - [Versiebeheer](#versiebeheer)
  - [Inhoudsopgave](#inhoudsopgave)
  - [Inleiding](#inleiding)
    - [Over dit document](#over-dit-document)
    - [Over het project en de opdrachtgever](#over-het-project-en-de-opdrachtgever)
  - [Actoren](#actoren)
    - [Functionele eisen en omschrijving](#functionele-eisen-en-omschrijving)
  - [Definition of Done](#definition-of-done)
  - [Userstories](#user-stories)

<div style="page-break-after: always;"></div>

## Inleiding

### Over dit document

In dit document worden de verschillende actoren, de user stories en de definition of done van te bouwen applicatie vastgelegd.

### Over het project en de opdrachtgever-

> In deze paragraaf wordt kort beschreven: de contactpersoon van het bedrijf of organisatie waarvoor de opdracht wordt uitgevoerd, een omschrijving van het bedrijf of organisatie waarvoor het project wordt uitgevoerd, het probleem en de manier waarop de opdrachtgever het probleem wil oplossen.

## Actoren

> In dit hoofdstuk wordt beschreven wie er in welke rol met de functionaliteit gaat werken.
> Voorbeelden zijn: admin, gast, administratief medewerker.

### Functionele eisen en omschrijving

> Hier vermeld je alle functionaliteiten die de applicatie gaat bieden, zoals is beschreven (en geprioriteerd) in het programma van eisen. De beschrijvingen dienen ook duidelijk te zijn voor niet vakgenoten. Maak de benodigde **user stories**.

| Titel              | <Naam/Titel van de functionaliteit> |
| ------------------ | ----------------------------------- |
| Als een...         | ==Type gebruiker==                  |
| Wil ik...          | ==Doelstelling/Actie==              |
| Zodat ik...        | ==Reden==                           |
| Prioriteit         | ==Hoog/Medium/Laag==                |
| Acceptatiecriteria | ==Voorwaarde(n)==                   |
| Scenario           | ==Stap 1, Stap 2, ...==             |
| Bijzonderheden     | ==Extra informatie==                |

## user stories
# Login pagina

| Titel                                                 | Login pagina                                           |
| ----------------------------------------------------- | ------------------------------------------------------ |
| **Als een...**                                        | Gebruiker                                              |
| **Wil ik...**                                         | Inloggen met mijn e-mailadres en wachtwoord            |
| **Zodat ik...**                                       | Toegang krijg tot mijn persoonlijke account            |
| **Prioriteit**                                        | Hoog                                                   |
| **Acceptatiecriteria**                                |
| - Inlogformulier met e-mail en wachtwoord             |
| - Foutmelding bij ongeldige inloggegevens             |
| - Wachtwoord vergeten optie                           |
| - Mogelijkheid om ingelogd te blijven                 |
| **Scenario**                                          |
| 1. Gebruiker opent de loginpagina                     |
| 2. Gebruiker vult e-mail en wachtwoord in             |
| 3. Gebruiker klikt op 'Inloggen'                      |
| 4. Bij correcte gegevens wordt de gebruiker ingelogd  |
| 5. Bij incorrecte gegevens verschijnt een foutmelding |
| **Bijzonderheden**                                    | Gebruik van versleutelde wachtwoorden voor beveiliging |

---

## Landing page

| Titel                                                         | Landing page                                              |
| ------------------------------------------------------------- | --------------------------------------------------------- |
| **Als een...**                                                | Bezoeker                                                  |
| **Wil ik...**                                                 | Een overzichtelijke en aantrekkelijke landingspagina zien |
| **Zodat ik...**                                               | Snel begrijp waar de website over gaat                    |
| **Prioriteit**                                                | Hoog                                                      |
| **Acceptatiecriteria**                                        |
| - Afteltijd zichtbaar op de voorpagina                        |
| - Route/Blogpost weergegeven op de voorpagina                 |
| - Overzicht van de belangrijkste zaken                        |
| **Scenario**                                                  |
| 1. Bezoeker opent de website                                  |
| 2. De landingspagina toont direct de belangrijkste informatie |
| 3. Bezoeker kan doorklikken naar verdere details              |
| **Bijzonderheden**                                            | Responsief design voor verschillende schermformaten       |

---

## GPS Tracker

| Titel                                         | GPS Tracker                                                                    |
| --------------------------------------------- | ------------------------------------------------------------------------------ |
| **Als een...**                                | Gebruiker                                                                      |
| **Wil ik...**                                 | Routes kunnen bekijken en de locatie kunnen volgen                             |
| **Zodat ik...**                               | Ten alle tijden kan zien waar de parade zich bevindt                           |
| **Prioriteit**                                | Hoog                                                                           |
| **Acceptatiecriteria**                        |
| - Kaartweergave met routes                    |
| - Live GPS-tracking                           |
| **Scenario**                                  |
| 1. Gebruiker opent de GPS-trackerpagina       |
| 2. Kaart met huidige routes wordt weergegeven |
| 3. Live locatie van de parade wordt getoond   |
| **Bijzonderheden**                            | Gebruik van een betrouwbare kaartenprovider zoals Google Maps of OpenStreetMap |

---

## Vrijwilliger aanmeldpagina

| Titel                                            | Vrijwilliger aanmeldpagina                   |
| ------------------------------------------------ | -------------------------------------------- |
| **Als een...**                                   | Geïnteresseerde vrijwilliger                 |
| **Wil ik...**                                    | Mij kunnen aanmelden via een formulier       |
| **Zodat ik...**                                  | Kan helpen bij activiteiten                  |
| **Prioriteit**                                   | Medium                                       |
| **Acceptatiecriteria**                           |
| - Aanmeldformulier met naam, e-mail en motivatie |
| - Bevestigingsmail na aanmelding                 |
| - Opslag van aanmeldingen in database            |
| **Scenario**                                     |
| 1. Bezoeker opent de aanmeldpagina               |
| 2. Bezoeker vult naam, e-mail en motivatie in    |
| 3. Bezoeker klikt op ‘Aanmelden’                 |
| 4. Bevestigingsmail wordt verstuurd              |
| 5. Gegevens worden opgeslagen in database        |
| **Bijzonderheden**                               | GDPR-conform verwerking van persoonsgegevens |

---


## Vrijwilliger aanmeldpagina

| Titel                                            | Vrijwilliger aanmeldpagina                   |
| ------------------------------------------------ | -------------------------------------------- |
| **Als een...**                                   | Geïnteresseerde vrijwilliger                 |
| **Wil ik...**                                    | Mij kunnen aanmelden via een formulier       |
| **Zodat ik...**                                  | Kan helpen bij activiteiten                  |
| **Prioriteit**                                   | Medium                                       |
| **Acceptatiecriteria**                           |
| - Aanmeldformulier met naam, e-mail en motivatie |
| - Bevestigingsmail na aanmelding                 |
| - Opslag van aanmeldingen in database            |
| **Scenario**                                     |
| 1. Bezoeker opent de aanmeldpagina               |
| 2. Bezoeker vult naam, e-mail en motivatie in    |
| 3. Bezoeker klikt op ‘Aanmelden’                 |
| 4. Bevestigingsmail wordt verstuurd              |
| 5. Gegevens worden opgeslagen in database        |
| **Bijzonderheden**                               | GDPR-conform verwerking van persoonsgegevens |


## Donatie pagina

| Titel           | Donatie pagina                              |
| --------------- | ------------------------------------------- |
| **Als een...**  | Bezoeker                                    |
| **Wil ik...**   | Eenvoudig een donatie kunnen doen           |
| **Zodat ik...** | Financieel kan bijdragen aan het initiatief |
| **Prioriteit**  | Hoog                                        |

---

## Foto's upload pagina

| Titel                                       | Foto's upload                                     |
| ------------------------------------------- | ------------------------------------------------- |
| **Als een...**                              | Gebruiker                                         |
| **Wil ik...**                               | Foto's kunnen uploaden                            |
| **Zodat ik...**                             | Andere mensen kan laten zien wat er gemaakt wordt |
| **Prioriteit**                              | Hoog                                              |
| **Acceptatiecriteria**                      |
| - Je kan foto's uploaden                    |
| **Scenario**                                |
| 1. Gebruiker opent de foto upload pagina    |
| 2. Gebruiker kiest een foto om te uploaden  |
| 3. Gebruiker typt beschrijving voor de foto |
| 4. Gebruiker klikt op upload                |

---

## Foto Feed pagina

| Titel                                                | Foto Feed                  |
| ---------------------------------------------------- | -------------------------- |
| **Als een...**                                       | Gebruiker                  |
| **Wil ik...**                                        | Foto's kunnen vergroten    |
| **Zodat ik...**                                      | De foto beter kan bekijken |
| **Prioriteit**                                       | Laag                       |
| **Acceptatiecriteria**                               |
| - Je kan foto's vergroten                            |
| **Scenario**                                         |
| 1. Je komt op de foto pagina                         |
| 2. Je ziet niet goed wat er op de foto staat         |
| 3. Je veegt met twee vingers om de foto te vergroten |

---

## Foto Feed pagina

| Titel                                               | Foto Feed                         |
| --------------------------------------------------- | --------------------------------- |
| **Als een...**                                      | Gebruiker                         |
| **Wil ik...**                                       | Foto's kunnen liken               |
| **Zodat ik...**                                     | Aan kan geven dat de foto leuk is |
| **Prioriteit**                                      | Hoog                              |
| **Acceptatiecriteria**                              |
| - Je kan een foto liken                             |
| **Scenario**                                        |
| 1. Je komt op de foto pagina                        |
| 2. Je klikt klikt op het hartje om de foto te liken |

---

## Foto Feed pagina

| Titel                        | Foto Feed                    |
| ---------------------------- | ---------------------------- |
| **Als een...**               | Gebruiker                    |
| **Wil ik...**                | op foto's kunnen reageren    |
| **Zodat ik...**              | iets kan zeggen over de foto |
| **Prioriteit**               | Hoog                         |
| **Acceptatiecriteria**       |
| - Je kan op foto's reageren  |
| **Scenario**                 |
| 1. Je komt op de foto pagina |
| 2. Je kan reacties lezen     |
| 3. Je kan reacties plaatsen  |

---
## Blog  pagina

| Titel                        | Blog                         |
| ---------------------------- | ---------------------------- |
| **Als een...**               | Gebruiker                    |
| **Wil ik...**                | informatie vergaren          |
| **Zodat ik...**              | op de informatie kan handelen|
| **Prioriteit**               | Hoog                         |
| **Acceptatiecriteria**       |
|De gebruiker kan een blog bekijken |
| **Scenario**                 |
| 1. de gebruiker komt op de blog pagina |
| 2. de gebruiker heeft ogen en kan dus de blog pagina lezen |

---


## admin  pagina

| Titel                        | Admin pagina                          |
| ---------------------------- | ---------------------------- |
| **Als een...**               | admin                        |
| **Wil ik...**                | De admin pagina knunen bezoeken          |
| **Zodat ik...**              | Andere funcites in het admin panel kan gebruiken|
| **Prioriteit**               | Hoog                         |
| **Acceptatiecriteria**       |
|De admin kan lp de admin pagina komen |
| **Scenario**                 |
| 1. de admin komt op de blog pagina |

---