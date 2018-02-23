<?php
/*
  config.php
*/
$strTitle  = "GPS-Trackverwaltung";
$db_server = "localhost";
$strServername=$_SERVER['SERVER_NAME'];

switch ($strServername) {
  case 'localhost':
        $db_user   = "root";
        $db_passwd = "";
        $db =  "gps";
        $strUploaddir = "/var/www/html/gps/gpx-files/";
        $strFiledir   = "/var/www/html/gps/files/";
        break;
  case 'www.example.com':
        $db_user   = "track";
        $db_passwd = "track";
        $db = "track";
        $strUploaddir = "/var/www/html/trackverwaltung/gpx-files/";
        $strFiledir   = "/var/www/html/trackverwaltung/files/";
        break;
}

if ($_SERVER['CONTEXT_DOCUMENT_ROOT']=="/Users/john/Sites") {
  $strUploaddir = "/Users/john/Sites/trackverwaltung/gpx-files/";
  $strFiledir   = "/Users/john/Sites/trackverwaltung/files/";
}

try {
   $pdo = new PDO('mysql:host=localhost;dbname='.$db.';charset=utf8mb4', $db_user, $db_passwd);
} catch (PDOException $e) {echo $e->getMessage();  die();}


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
