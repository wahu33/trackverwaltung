<?php
  include("config.php");
  include("header.php");
?>

<h3>Dateiupload</h3>

<form  id="formular" enctype="multipart/form-data"  method="post" action="upload_file_chk.php">
  <div class="form-group myform">

  <label>Bezeichnung: </label>
  <input name="bezeichnung" class="form-control" type="text" size="40">

  <label>Datum:</label>
  <input name="datum" type="text" class="form-control" size="20">
  <small id="emailHelp" class="form-text text-muted">(leer = akt. Datum)</small>

  <label>Kategorie:</label>
  <select class="form-control" name="kategorie">
<?php
	   $query_katauswahl="SELECT id,bezeichnung FROM kategorie ORDER BY sort";
	   $result_katauswahl = $pdo->query($query_katauswahl);
	   while ($row_katauswahl = $result_katauswahl->fetch(PDO::FETCH_ASSOC)) {
		 echo "<option value=\"".$row_katauswahl['id']." \">".$row_katauswahl['bezeichnung']."</option>\n";
	   }
?>
 </select>
 <br />


<label>Quelle:</label>
  <select class="form-control" name="quelle">
<?php
	   $query_quellauswahl="SELECT id,bezeichnung FROM quelle ORDER BY sort";
	   $result_quellauswahl = $pdo->query($query_quellauswahl);
	   while ($row_quellauswahl = $result_quellauswahl->fetch(PDO::FETCH_ASSOC)) {
		 echo "<option value=\"".$row_quellauswahl['id']." \">".$row_quellauswahl['bezeichnung']."</option>\n";
	   }
?>
 </select>

<br>

<label>Status:</label>
  <select class="form-control" name="status">
  <option value="1">&ouml;ffentlich</option>
	<option value="2">privat</option>
  </select>

<br>

  <input type="hidden" name="MAX_FILE_SIZE" value="8500000">
  <label>Datei:</label><input class="form-control" name="userfile" type="file">
  <br>
  <button type="submit" class="btn btn-primary">Abschicken</button>
  </div>
  </form>
  <p>&nbsp;</p>

 <?php include("footer.php");
