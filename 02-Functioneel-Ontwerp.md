# Functioneel Ontwerp

Versienummer :
Auteur :
Studentnummer :
Klas :
Datum :

<div style="page-break-after: always;"></div>

## Versiebeheer

| Datum      | Versie | Wie | Wijzigingen |
| ---------- | ------ | --- | ----------- |
| yyyy-mm-dd | x.x    |     |             |
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

| Titel              | <Naam/Titel van de functionaliteit> |
| ------------------ | ----------------------------------- |
| Als een...         | ==Type gebruiker==                  |
| Wil ik...          | ==Doelstelling/Actie==              |
| Zodat ik...        | ==Reden==                           |
| Prioriteit         | ==Hoog/Medium/Laag==                |
| Acceptatiecriteria | ==Voorwaarde(n)==                   |
| Scenario           | ==Stap 1, Stap 2, ...==             |
| Bijzonderheden     | ==Extra informatie==                |

Voorbeeld user story:

| Titel              | Inloggen |
| ------------------ | -------- |
| Als een...         | Gebruiker |
| Wil ik...          | Inloggen |
| Zodat ik...        | Toegang krijg tot mijn account |
| Prioriteit         | Hoog     |
| Acceptatiecriteria | Gebruikersnaam en wachtwoord zijn correct |
| Scenario           | 1. Gebruiker vult gebruikersnaam en wachtwoord in en klikt op de knop "Inloggen" |
| Bijzonderheden     | nvt |

| Titel              | Registreren voor exclusieve content |
| ------------------ | ----------------------------------- |
| Als een...         | Website bezoeker |
| Wil ik ...         | registreren met mijn e-mailadres |
| Zodat ik ...       | toegang krijg tot exclusieve content |
| Prioriteit         | Hoog |
| Acceptatiecriteria | 1. Het registratieformulier moet e-mailvalidatie bevatten. <br/> 2. Na registratie moet er een bevestigingsmail worden verstuurd. <br/> 3. ... |
| Scenario           | 1. Open de registratiepagina. <br/> 2. Vul het formulier in met geldige gegevens. <br/> 3. Ontvang en bevestig de registratie-e-mail. <br/> 4. ... <br/> 5. ... |
| Bijzonderheden     | nvt |

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
