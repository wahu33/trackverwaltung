<?php
  include ("config.php");



  $strBezeichung    = mysql_real_escape_string($_POST['bezeichnung']);
  $strPlural         = mysql_real_escape_string($_POST['plural']);
  $strKommentar     = mysql_real_escape_string($_POST['kommentar']);
  $numSort          = (int)$_POST['sort'];
  $numPrivat        = (int)$_POST['privat'];



  $strSQL = "insert into kategorie (bezeichnung, plural, kommentar, sort, privat)
            values ('$strBezeichung','$strPlural','$strKommentar',$numSort,$numPrivat)";

  $pdo->query($strSQL);

  //DEBUG:echo $strSQL;

  header ("Location: index.php");



?>
