<?php
  session_start();
  $db=$_SESSION['db'];
  require_once("config.php");

  $id = $_POST['id'];
  $bezeichnung = $_POST['bezeichnung'];
  $quelle = $_POST['quelle'];
  $beschreibung = $_POST['beschreibung'];
  $kategorie = $_POST['kategorie'];

  $datum = $_POST['datum'];
  $sort = $_POST['sort'];
  $filename = $_POST['datei'];
  $submit = (isset($_POST['submit'])) ? isset($_POST['submit']) : "";
  $delete = (isset($_POST['delete'])) ? isset($_POST['delete']) : "";
  $status = $_POST['status'];
  $numFach = $_POST['fach'];

  if (!empty($submit)) {
        $query="UPDATE datei SET bezeichnung='$bezeichnung', datum='$datum', sort=$sort, status=$status, kategorie=$kategorie, quelle=$quelle, beschreibung='$beschreibung'  WHERE id=$id";
      	//echo $query."<hr>";
        $pdo->query($query);
  }
  else if (!empty($delete)) {
        //echo "Löschen";
        $query="DELETE FROM datei  WHERE id=$id";
    	  //echo $query."<hr>";
        $pdo->query($query);
    	  $filename = $uploaddir.$filename;
            if (file_exists($filename)) {
            //echo "Lösche ".$filename;
        		unlink($filename);
  	   }
  }
  header("Location: list.php?kategorie=".$kategorie);
