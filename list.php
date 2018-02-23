<?php
    require_once("config.php");
    $numQuelle = (isset($_GET['quelle'])) ? (int)$_GET['quelle'] : 0;
    $numKategorie = (isset($_GET['kategorie'])) ? (int)$_GET['kategorie'] : 1;
    if ($numKategorie<1) $numKategorie=1;
    include("header.php");

    $strSQL="SELECT bezeichnung, plural, kommentar FROM kategorie WHERE id=$numKategorie";
    $stmt_k = $pdo->query($strSQL);
    if ($row = $stmt_k->fetch(PDO::FETCH_ASSOC)){
      $strPlural = $row['plural'];
      $strKommentar = $row['kommentar'];
    }
    echo "<h2>".$strPlural."</h2>";
    if (!empty($strKommentar)) echo "<p>".$strKommentar."</p>";

    //Nach Jahren anordnen
    $strSQL="select DATE_FORMAT(trk_date,'%Y') as year from datei
             where kategorie=$numKategorie and trk_date>'1970-01-01 00:00:00'
             group by DATE_FORMAT(trk_date,'%Y')
             order by year DESC";
    $stmt_y=$pdo->query($strSQL);
    $arrYears=$stmt_y->fetchAll(PDO::FETCH_NUM);



    $strQuelle = ($numQuelle>0) ? " and quelle=".$numQuelle." " : "";
    $strKategorie = " and d.kategorie=".$numKategorie." ";
    $strStatus = ($boolLogin) ? "" : " and d.status=1";

    if ($numKategorie >= 1) {
       $aktYear=date("Y");
  	   foreach ($arrYears as $arrYear) {
           $year=$arrYear[0];
      		 echo "<h4>$year</h4>";
      		 $query="SELECT d.id as id, d.bezeichnung as bezeichnung, filename,k.bezeichnung as kategorie, q.bezeichnung as quelle,
        					groesse,status,DATE_FORMAT(datum,\"%d.%m.%Y\") as datum,d.sort,
          				d.trkpt_count,DATE_FORMAT(trk_date,\"%d.%m.%Y\") as trk_date,d.distance
          				FROM datei d, kategorie k, quelle q
          				WHERE d.kategorie=k.id AND q.id=d.quelle $strKategorie  and trk_date>'$year-01-01' and trk_date<'$year-12-31' $strStatus
          				ORDER BY d.kategorie,d.sort,d.trk_date";
      	    //DEBUG:    echo $query;
      	    $stmt2 = $pdo->prepare($query);
      	    $stmt2->execute();
            echo "<div class='table-responsive'>";
      	    echo "<table class='table table-striped table-sm mytable'>";
            echo "<thead><tr>";
            echo "<th></th><th>Track</th><th>Datum</th><th>Entfernung</th>";
            if ($boolLogin) {echo "<th>Status</th><th>Sort</th><th></th>";}
            echo "<th></th><th></th><th></th><th></th></tr></thead>";
            echo "<tbody>";
            echo "<tbody>";
      	    $zeile=1;
      	    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
          		  echo "<tr>";
          		  echo "<td>$zeile.</td>";
          		  echo "<td><a href=\"map.php?id=".$row['id']."\">".$row['bezeichnung']."</a></td>";
          		  echo "<td>".$row['trk_date']."</td>";
          		  echo "<td align='right'>".round($row['distance'],2)." km</td>";
                if ($boolLogin) {
                   echo "<td align='middle'>".$row['status']."</td>";
                   echo "<td align='middle'>".$row['sort']."</td>";
                   echo "<td><a href='edit_file.php?id=".$row['id']."'><i class='fa fa-pencil pull-right' aria-hidden='true'></a></td>";
                }
                echo "<td><a href='map.php?id=".$row['id']."'><i class='fa fa-map' aria-hidden='true'></i></a></td>";
                echo "<td><a href='profile.php?id=".$row['id']."'><i class='fa fa-area-chart' aria-hidden='true'></i></a></td>";
                echo "<td><a href='info.php?id=".$row['id']."'><i class='fa fa-info-circle' aria-hidden='true'></i></a></td>";
                echo "<td><a href='parser.php?id=".$row['id']."'><i class='fa fa-filter' aria-hidden='true'></i></a><br>";

          		  echo "</tr>\n";
          		  $zeile++;
  	        }
            echo "</tbody>\n";
      	    echo "</table>\n";
            echo "</div>\n";
  	   } //for $year
   }

echo "<br />";
echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\">zurück</a>";
include("footer.php");
?>
