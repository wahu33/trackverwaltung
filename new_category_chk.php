<?php
  include ("config.php");



  $strBezeichung    = $_POST['bezeichnung'];
  $strPlural         = $_POST['plural'];
  $strKommentar     = $_POST['kommentar'];
  $numSort          = (int)$_POST['sort'];
  $numPrivat        = (int)$_POST['privat'];



  $strSQL = "insert into kategorie (bezeichnung, plural, kommentar, sort, privat)
            values ('$strBezeichung','$strPlural','$strKommentar',$numSort,$numPrivat)";

  $pdo->query($strSQL);

  //DEBUG:echo $strSQL;

  header ("Location: index.php");



?>
