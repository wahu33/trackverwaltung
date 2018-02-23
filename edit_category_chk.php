<?php
  include ("config.php");

  $strBezeichung    = $_POST['bezeichnung'];
  $strPlural        = $_POST['plural'];
  $strKommentar     = $_POST['kommentar'];
  $numSort          = (int)$_POST['sort'];
  $numPrivat        = (int)$_POST['privat'];
  $numId            = (int)$_POST['id'];


  $strSQL = "update kategorie set bezeichnung='$strBezeichung', plural='$strPlural', kommentar='$strKommentar',
            sort=$numSort, privat=$numPrivat where id=$numId";

  $pdo->query($strSQL);

  //DEBUG:echo $strSQL;

  header ("Location: index.php");
