De pagina die je wilt maken komt in de folder Views met deze naam: (naam).view.php voorbeeld: login.view.placeholder-purple-50
Vervolgens open je een intergrated terminal in /public en voer je het volgende in: php -S localhost:8000
Hierna kan je in de browser naar het volgende toegaan: localhost:8000/public?page=(naam) voorbeeld: localhost:8000/public?page=login
dit is geen live server dus om veranderingen te zien moet je refreshen



Voor de docker nerds draai dit: 
docker run -it --rm -p 8000:8000 -v ${PWD}:/app sha256:6c19bcc7b8302731ca99b8d5b2dd0233ebce1cca4276aa6c0f3d4cc4dc484bdf
docker run -it --rm -p 8000:8000 -v ${PWD}:/app sunnemeerten