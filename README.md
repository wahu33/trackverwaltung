Geotracking-Datenbank
==================


Datenbank zur Speicherung und Kategorisierung von GPX-Tracks

Version 0.2 - HTML5, PHP7
Version 0.3 - Bootstrap, phpgpx (composer), Highchart
Version 0.4 - Download von GPX-Dateien

Installation der GPX-Trackverwaltung
-------


Voraussetzung zur Installation ist ein Webserver mit PHP 7 und die Installation von Composer. 

### Composer installieren

Wie man Composer auf seinem System installiert, erfährt man unter https://getcomposer.org/download/ oder benutzt eine der vielen Anleitungen im Internet speziell für sein Betriebssystem, z. B.:

`````
curl -s https://getcomposer.org/installer | php
`````
Zusätzlich kann man auf einem Linux-Systems noch folgendes in die .bashrc schreiben:
`````
alias composer="/path/to/composer.phar"
`````

### Von Github clonen und Composer ausführen

Zunächst die Quellen in das Dokumentroot des Webservers installieren:

````
git clone https://github.com/wahu33/trackverwaltung.git
cd trackverwaltung
composer install
mkdir gpx-files
chomod 777 gpx-files
cat "protected" > gpx-files/.htaccess
cp config.default.php config.php
````









Achtung: In HTML5 hat sich die CSS-Eigenschaft für height:100% geändert.
Alle Vater-Container müssen auch mit height:100% ausgezeichnet werden:
html, body : {height:100%}



* https://github.com/Sibyx/phpGPX
