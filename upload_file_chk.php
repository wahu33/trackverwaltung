<?php
require_once("config.php");
$debug = false;

//if ( !((strcmp($_FILES['userfile']['type'],"application/pdf")==0)) )
if ($debug) {
  echo $_FILES['userfile']['tmp_name']."<br>";
  echo $uploaddir . $_FILES['userfile']['name']."<br>";
  echo $_FILES['userfile']['type']."<br>";
  //die ("<h2 style='color:red'>Format nicht erlaubt oder Datei zu gro�!</h2><p><a href=\"upload_file.php\">zur�ck</a></p>");
}

$strFilename =  $_FILES['userfile']['name'];
$strFilename =  str_replace(" ","_",$strFilename);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $strUploaddir . $strFilename)) {
   //print "File is valid, and was successfully uploaded.  Here's some more debugging info:\n";
   //print_r($_FILES);
   $bezeichnung=$_POST['bezeichnung'];
   $datum     = $_POST['datum'];
   $kategorie = $_POST['kategorie'];
   $quelle    = $_POST['quelle'];
   $status    = $_POST['status'];
   $filename  = $strFilename;
   $groesse   = $_FILES['userfile']['size'];
   if (empty($bezeichnung)) $bezeichnung=$filename;
   if (empty($datum)) $datum=date("Y-m-d");
   $query="INSERT INTO datei (bezeichnung,beschreibung, filename, quelle, kategorie, status, groesse, datum,sort)
          VALUES ('$bezeichnung','', '$filename', $quelle, $kategorie, $status, $groesse, '$datum',0)";
	 //DEBUG:  echo $query."<hr>";
    try {
      $pdo->query($query);
    } catch (PDOException $e) { echo "Fehler: " . $e->getMessage();}
    $id = $pdo->lastInsertId();
    //DEBUG: echo "ID:".$id;
	  header("Location: parser.php?id=".$id);
} else {
   include("header.php");
   print "Fehler bei Dateiupload:\n";
   echo "<pre>";
   print_r($_FILES);
   echo "</pre>";
   incluce("footer.php");
}

?>
