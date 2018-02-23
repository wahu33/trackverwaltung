<?php
  require_once("config.php");
  $numQuelle = (isset($_GET['quelle'])) ? $_GET['quelle'] : 0;
  $numKategorie = (isset($_GET['kategorie'])) ? $_GET['kategorie'] : 1;
  if ($numKategorie<1) $numKategorie=1;
  include("header.php");
?>

<?php
// -------------------------------------------------------------------------------------------------------
// Auswahl der Klasse in Select-Box

   $query_kauswahl="SELECT id,bezeichnung,plural,kommentar FROM kategorie WHERE privat<1 ORDER BY sort";
   $result_kauswahl = mysql_query($query_kauswahl);
   while ($row_kauswahl = mysql_fetch_array($result_kauswahl, MYSQL_ASSOC)) {
     $strSelect = ($row_kauswahl['id']==$numKategorie) ? " selected=\"selected\" " : "";
         if ($row_kauswahl['id']==$numKategorie) {
            $strPlural = $row_kauswahl['plural'];
            $strKommentar = $row_kauswahl['kommentar'];
     }
   }
?>

<?php
   echo "<h2>".$strPlural."</h2>";
   if (!empty($strKommentar)) echo "<p>".$strKommentar."</p>";

   $strQuelle = ($numQuelle>0) ? " and quelle=".$numQuelle." " : "";
   $strKategorie = " and d.kategorie=".$numKategorie." ";

   $query="SELECT d.id as id, d.bezeichnung as bezeichnung, filename,k.bezeichnung as kategorie, q.bezeichnung as quelle,
                    groesse,status,DATE_FORMAT(datum,\"%d.%m.%Y\") as datum,d.sort,
			d.trkpt_count,DATE_FORMAT(trk_date,\"%d.%m.%Y\") as trk_date,d.distance
            FROM datei d, kategorie k, quelle q
			WHERE d.kategorie=k.id AND q.id=d.quelle $strKategorie ORDER BY d.kategorie,d.sort,d.trk_date";
   //DEBUG:    echo $query;
   $result = mysql_query($query) or die (mysql_error());
   echo "<table id='filelist'>";
   $zeile=1;
   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      echo "<tr>";
	  echo "<td>$zeile.</td>";
	  echo "<td><a href=\"map1.php?id=".$row['id']."\">".$row['bezeichnung']."</a></td>";
	  echo "<td>".$row['trk_date']."</td>";
	  echo "<td align='right'>".round($row['distance'],2)." km</td>";

	  echo "</tr>";
	  $zeile++;
   }
   echo "</table>\n";

include("footer.php");
?>
