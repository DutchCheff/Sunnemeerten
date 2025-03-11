# Functioneel Ontwerp

Versienummer : 1.0
Auteur : Laurens Kuipers 
Studentnummer : 0266526
Klas : 24SDB
Datum : 11-3-2025

<div style="page-break-after: always;"></div>

## Versiebeheer

| Datum      | Versie | Wie | Wijzigingen |
| ---------- | ------ | --- | ----------- |
| 11-3-2025  | 1.0    | Laurens| Alles             |
|            |        |     |             |
|            |        |     |             |

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

<div style="page-break-after: always;"></div>

## Inleiding

### Over dit document

In dit document worden de verschillende actoren, de user stories en de definition of done  van te bouwen applicatie vastgelegd.

### Over het project en de opdrachtgever

> In deze paragraaf wordt kort beschreven: de contactpersoon van het bedrijf of organisatie waarvoor de opdracht wordt uitgevoerd, een omschrijving van het bedrijf of organisatie waarvoor het project wordt uitgevoerd, het probleem en de manier waarop de opdrachtgever het probleem wil oplossen.

## Actoren

> In dit hoofdstuk wordt beschreven wie er in welke rol met de functionaliteit gaat werken.
> Voorbeelden zijn: admin, gast, administratief medewerker.

### Functionele eisen en omschrijving

> Hier vermeld je alle functionaliteiten die de applicatie gaat bieden, zoals is beschreven (en geprioriteerd) in het programma van eisen. De beschrijvingen dienen ook duidelijk te zijn voor niet vakgenoten. Maak de benodigde **user stories**.

# Login pagina  
| Titel              | Login pagina                              |
| ------------------ | ---------------------------------------- |
| **Als een...**     | Gebruiker                                |
| **Wil ik...**      | Inloggen met mijn e-mailadres en wachtwoord |
| **Zodat ik...**    | Toegang krijg tot mijn persoonlijke account |
| **Prioriteit**     | Hoog                                     |
| **Acceptatiecriteria** |  
| - Inlogformulier met e-mail en wachtwoord  |  
| - Foutmelding bij ongeldige inloggegevens  |  
| - Wachtwoord vergeten optie  |  
| - Mogelijkheid om ingelogd te blijven  |  
| **Scenario**        |  
| 1. Gebruiker opent de loginpagina  |  
| 2. Gebruiker vult e-mail en wachtwoord in  |  
| 3. Gebruiker klikt op 'Inloggen'  |  
| 4. Bij correcte gegevens wordt de gebruiker ingelogd  |  
| 5. Bij incorrecte gegevens verschijnt een foutmelding  |  
| **Bijzonderheden**  | Gebruik van versleutelde wachtwoorden voor beveiliging |

---

## Landing page  
| Titel              | Landing page                              |
| ------------------ | ---------------------------------------- |
| **Als een...**     | Bezoeker                                 |
| **Wil ik...**      | Een overzichtelijke en aantrekkelijke landingspagina zien |
| **Zodat ik...**    | Snel begrijp waar de website over gaat  |
| **Prioriteit**     | Hoog                                     |
| **Acceptatiecriteria** |  
| - Afteltijd zichtbaar op de voorpagina  |  
| - Route/Blogpost weergegeven op de voorpagina  |  
| - Overzicht van de belangrijkste zaken  |  
| **Scenario**        |  
| 1. Bezoeker opent de website  |  
| 2. De landingspagina toont direct de belangrijkste informatie  |  
| 3. Bezoeker kan doorklikken naar verdere details  |  
| **Bijzonderheden**  | Responsief design voor verschillende schermformaten |

---

## GPS Tracker  
| Titel              | GPS Tracker                              |
| ------------------ | ---------------------------------------- |
| **Als een...**     | Gebruiker                                |
| **Wil ik...**      | Routes kunnen bekijken en de locatie kunnen volgen |
| **Zodat ik...**    | Ten alle tijden kan zien waar de parade zich bevindt |
| **Prioriteit**     | Hoog                                     |
| **Acceptatiecriteria** |  
| - Kaartweergave met routes  |  
| - Live GPS-tracking  |  
| **Scenario**        |  
| 1. Gebruiker opent de GPS-trackerpagina  |  
| 2. Kaart met huidige routes wordt weergegeven  |  
| 3. Live locatie van de parade wordt getoond  |  
| **Bijzonderheden**  | Gebruik van een betrouwbare kaartenprovider zoals Google Maps of OpenStreetMap |

---

## Vrijwilliger aanmeldpagina  
| Titel              | Vrijwilliger aanmeldpagina              |
| ------------------ | ---------------------------------------- |
| **Als een...**     | Geïnteresseerde vrijwilliger            |
| **Wil ik...**      | Mij kunnen aanmelden via een formulier  |
| **Zodat ik...**    | Kan helpen bij activiteiten            |
| **Prioriteit**     | Medium                                  |
| **Acceptatiecriteria** |  
| - Aanmeldformulier met naam, e-mail en motivatie  |  
| - Bevestigingsmail na aanmelding  |  
| - Opslag van aanmeldingen in database  |  
| **Scenario**        |  
| 1. Bezoeker opent de aanmeldpagina  |  
| 2. Bezoeker vult naam, e-mail en motivatie in  |  
| 3. Bezoeker klikt op ‘Aanmelden’  |  
| 4. Bevestigingsmail wordt verstuurd  |  
| 5. Gegevens worden opgeslagen in database  |  
| **Bijzonderheden**  | GDPR-conform verwerking van persoonsgegevens |

---

## Donatie pagina  
| Titel              | Donatie pagina                           |
| ------------------ | ---------------------------------------- |
| **Als een...**     | Bezoeker                                 |
| **Wil ik...**      | Eenvoudig een donatie kunnen doen       |
| **Zodat ik...**    | Financieel kan bijdragen aan het initiatief |
| **Prioriteit**     | Hoog                                     |

## Definition of Done

>Maak een definition of done.
>Zie voorbeeld hieronder. Aanpassen aan de wensen van je team of project.

1. **Functionele Vereisten**:
    - Alle functionaliteiten zoals beschreven in de user story zijn geïmplementeerd.
    - De functionaliteiten voldoen aan de specificaties en acceptatiecriteria.
2. **Code Kwaliteit**:
    - De code is geschreven volgens de vastgestelde coding standards.
    - Er zijn geen onbehandelde compilerwaarschuwingen of -fouten.
    - De code is goed leesbaar en gedocumenteerd.
3. **Unit Testing**:
    - Unit tests zijn geschreven voor alle nieuwe functionaliteiten.
    - Bestaande unit tests zijn bijgewerkt om wijzigingen in de code weer te geven.
    - Alle tests slagen zonder fouten.
4. **Integratietesting**:
    - De nieuwe code is geïntegreerd in de bestaande codebase zonder conflicten.
    - Integratietests zijn uitgevoerd en alle tests slagen zonder fouten.
5. **Gebruikersacceptatietesting (UAT)**:
    - De functionaliteiten zijn getest in de productieachtige omgeving.
    - De gebruikersacceptatietests zijn succesvol afgerond.
6. **Documentatie**:
    - Alle benodigde documentatie is bijgewerkt, inclusief gebruikershandleidingen, API-documentatie, enz.
7. **Deployment**:
    - De code is klaar voor implementatie naar de productieomgeving.
    - Eventuele configuratie- of deploymentscripts zijn bijgewerkt.
8. **Code Review**:
    - Code reviews zijn uitgevoerd en eventuele feedback is geïmplementeerd.
9. **Product Owner Goedkeuring**:
    - De leidinggevende/opdrachtgever heeft de functionaliteit gecontroleerd en goedgekeurd.
10. **Geen Blokkerende Problemen**:
    - Er zijn geen bekende blokkerende problemen die de implementatie van de functionaliteit zouden verhinderen.
