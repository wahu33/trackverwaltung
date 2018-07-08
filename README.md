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

Zunächst die Quellen in das Dokumentroot des Webservers installieren und Composer ausführen. Anschließend wird noch ein Verzeichnis für die GPX-Dateien eingerichtet und der direkte Zugriff auf das Verzeichnis gesperrt, falls mod_rewrite installiert ist.

````
git clone https://github.com/wahu33/trackverwaltung.git
cd trackverwaltung
composer install
mkdir gpx-files
chmod 777 gpx-files
echo "protected" > gpx-files/.htaccess
cp config.default.php config.php
````

### Datenbank einrichten

````
mysql -uroot -p
mysql> CREATE DATABASE IF NOT EXISTS trackverwaltung;
mysql> GRANT ALL ON trackverwaltung.* TO trackverwaltung@localhost IDENTIFIED BY "gpx-geheim";
mysql> flush privileges;
mysql> quit
mysql -uroot -p trackverwaltung < SQL/trackverwaltung.sql
````

Damit ist die Installation abgeschlossen. Unter http://example.com/trackvewaltung sollte die Applikation aufgerufen werden können.

Das Login geschieht mit User ````admin```` und Passwort ````admin````.

### Passwort ändern

Es gibt noch keine Nutzervewaltung Passwörter können daher nur in der Datenbank direkt geändert werden, entweder über ````phpmyadmin```` oder direkt in der Datenbank.

``````
mysql> use trackverwaltung;
mysql> update user set password=password('NeuesPasswort') where user='admin';
``````

### Nacharbeiten
Sollte die Applikation im Internet betrieben werden, sollte man auch das Datenbankpasswort ändern.


### Anmerkungen 

Achtung: In HTML5 hat sich die CSS-Eigenschaft für height:100% geändert.
Alle Vater-Container müssen auch mit height:100% ausgezeichnet werden:
html, body : {height:100%}

#### Verwendete Bibliothek

* https://github.com/Sibyx/phpGPX
