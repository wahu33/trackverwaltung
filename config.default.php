<?php
/*
  config.php
*/
$strTitle  = "GPX-Trackverwaltung";
$db_server = "localhost";
$db_user   = "trackverwaltung";
$db_passwd = "gpx-geheim";
$db = "trackverwaltung";
$strUploaddir = "/var/www/html/trackverwaltung/gpx-files/";
$strFiledir   = "/var/www/html/trackverwaltung/files/";


try {
   $pdo = new PDO('mysql:host=localhost;dbname='.$db.';charset=utf8mb4', $db_user, $db_passwd);
} catch (PDOException $e) {echo $e->getMessage();  die();}

// ----- Umrechnungsfunktion Sekunden zu Stunden  --------

function toStd($sekunden)
{
    $stunden = floor($sekunden / 3600);
    $minuten = floor(($sekunden - ($stunden * 3600)) / 60);
    $sekunden = round($sekunden - ($stunden * 3600) - ($minuten * 60), 0);

    if ($stunden <= 9) {
        $strStunden = "0" . $stunden;
    } else {
        $strStunden = $stunden;
    }

    if ($minuten <= 9) {
        $strMinuten = "0" . $minuten;
    } else {
        $strMinuten = $minuten;
    }

    if ($sekunden <= 9) {
        $strSekunden = "0" . $sekunden;
    } else {
        $strSekunden = $sekunden;
    }
    return "$strStunden:$strMinuten:$strSekunden";
}
