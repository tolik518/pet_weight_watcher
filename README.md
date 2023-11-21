# Pet Weight Watcher

## Projektstruktur

Wir haben uns dafür entschieden, das Projekt in zwei Teile zu gliedern.
Einmal den Frontend-Teil, welcher die Webseite darstellt und den Backend-Teil, welcher die Daten verarbeitet.

Das Backend besteht, aus einer API die in PHP geschrieben wurde und wird mit einem Nginx-Server ausgeliefert.
Das Frontend hingegen ist mit HTML und JavaScript geschrieben und wird von keinem Server ausgeliefert, sondern wird direkt im Browser ausgeführt.

### Frontend
Das Frontend kann man öffnen, in dem man einfach die `frontend/index.html`-Datei im Browser öffnet.  
Ohne das Backend kann man im Frontend jedoch nichts machen, da die Daten aus dem Backend geladen werden.

### Backend
Das Backend ist dockerisiert und besteht aus drei Containern.
Einmal dem PHP-Container, welcher den PHP-Code ausführt und dem Nginx-Container, welcher den PHP-Code ausliefert.

Um das Backend zu starten, muss man, wenn man Docker installiert hat, einfach `docker compose up --build`ausführen.   
Beenden kann man es mit `docker compose down`

### Ordnerstruktur
```
├── backend
│   ├── code
│   │   ├── composer.json
│   │   ├── composer.lock
│   │   ├── public
│   │   │   ├── haustiere.csv
│   │   │   └── index.php
│   │   ├── src
│   │   │   ├── DataReader.php
│   │   │   ├── DataWriter.php
│   │   │   ├── Factory.php
│   │   │   ├── PetCallbackHandler.php
│   │   │   └── Router.php
│   │   └── vendor
│   │       ├── autoload.php
│   │       ├── ...
│   └── docker
│       ├── nginx
│       │   ├── default.conf
│       │   └── Dockerfile
│       └── php
│           └── Dockerfile
├── docker-compose.yml
├── frontend
│   ├── animals.csv
│   └── index.html
└── README.md
```