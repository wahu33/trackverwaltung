<?php
  include ("config.php");



  $strBezeichung    = $_POST['bezeichnung'];
  $strPlural         = $_POST['plural'];
  $strKommentar     = $_POST['kommentar'];
  $numSort          = (int)$_POST['sort'];
  $numPrivat        = (int)$_POST['privat'];
  
  $strSQL = "insert into kategorie (bezeichnung, plural, kommentar, sort, privat)
            values (:bezeichnung,:plural,:kommentar,$numSort,$numPrivat)";

  $sth = $pdo->prepare($strSQL);
  $sth->execute(array(':bezeichnung'=>$strBezeichnung, ':plural'=>$strPlural, ';kommentar' => $strKommentar));

  //DEBUG:echo $strSQL;

  header ("Location: index.php");



?>
