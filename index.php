<?php
  require_once("config.php");
  include("header.php");
?>
<div class="mylist">
<h2>Kategorien</h2>
<ul class="list-group">
<?php
   if ($boolLogin)
      $query_kauswahl="SELECT id,bezeichnung,plural,kommentar,privat,sort FROM kategorie WHERE 1 ORDER BY sort";
   else
      $query_kauswahl="SELECT id,bezeichnung,plural,kommentar,privat FROM kategorie WHERE privat=0 ORDER BY sort";

   $stmt = $pdo->query($query_kauswahl);
   while ($row_kauswahl = $stmt->fetch(PDO::FETCH_ASSOC)) {
     echo "<li class=\"list-group-item\"><strong><a href=\"list.php?kategorie=".$row_kauswahl['id']."\" >".$row_kauswahl['plural']."</a></strong>";
     if ($boolLogin and $row_kauswahl['privat']==1) echo " <i class=\"fa fa-user-secret\" aria-hidden=\"true\"></i><strong></strong>";
     if ($boolLogin) {
       echo " <a href='edit_category.php?id=".$row_kauswahl['id']."'> <i class='fa fa-pencil pull-right' aria-hidden='true'></i></a>";
       echo " <span class=\"badge badge-secondary pull-right\">".$row_kauswahl['sort']."</span>";
     }
     echo "<br />".$row_kauswahl['kommentar']."</li>\n";
   }
?>
</ul>
</div>
<?php include("footer.php"); ?>
