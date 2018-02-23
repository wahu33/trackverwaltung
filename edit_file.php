<?php
  require_once ("config.php");
  $id=$_GET['id'];
  include("header.php");
  if (!$boolLogin) header("Location: index.php");

?>

 <h3>Datei bearbeiten</h3>
 <p>&nbsp;</p>

<form id="formular"  method="post" action="edit_file_chk.php">
<div class="form-group myform">

<?php
   $query="SELECT d.id as id, d.bezeichnung as bezeichnung,beschreibung,kategorie,quelle, filename,status,groesse,d.sort as sort,DATE_FORMAT(datum,\"%Y-%m-%d\") as datum
            FROM datei d WHERE  d.id=$id";
  //DEBUG: echo $query;
   $stmt=$pdo->query($query);
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
 <label>Bezeichung:</label>
  <input class="form-control" name="bezeichnung" type="text" size="60" value="<?=$row['bezeichnung']?>"><br>
  <label>Beschreibung:</label>
  <textarea class="form-control" name="beschreibung" cols="36" rows="6"><?=$row['beschreibung']?></textarea><br>
 <label>Kategorie:</label>
 <select name="kategorie" class="form-control">
   <option value="-1">Kategorie</option>
<?php
// -------------------------------------------------------------------------------------------------------
// Auswahl der Klasse in Select-Box

   $query_kauswahl="SELECT id,bezeichnung FROM kategorie ORDER BY sort";
   $stmt_kauswahl = $pdo->query($query_kauswahl);
   while ($row_kauswahl = $stmt_kauswahl->fetch(PDO::FETCH_ASSOC)) {
       $strSelect = ($row_kauswahl['id']==$row['kategorie']) ? " selected=\"selected\" " : "";
       echo "<option value=\"".$row_kauswahl['id']."\" ".$strSelect.">".$row_kauswahl['bezeichnung']."</option>\n";
   }
?>
</select>
<br />
 <label>Quelle:</label>
 <select name="quelle" class="form-control">
<option value="-1">Quelle ausw&auml;hlen/Auswahl l&ouml;schen</option>
<?php
// -------------------------------------------------------------------------------------------------------
// Auswahl der Klasse in Select-Box
   $query_quellauswahl="SELECT id,bezeichnung FROM quelle ORDER BY sort";
   $stmt_quellauswahl = $pdo->query($query_quellauswahl);
   while ($row_quellauswahl = $stmt_quellauswahl->fetch(PDO::FETCH_ASSOC)) {
     $strSelect = ($row_quellauswahl['id']==$row['quelle']) ? " selected=\"selected\" " : "";
     echo "<option value=\"".$row_quellauswahl['id']."\" ".$strSelect.">".$row_quellauswahl['bezeichnung']."</option>\n";
   }
?>
</select>
<br />

    <label>Status:</label>
    <select name="status" class="form-control">
    <option value="1" <?php echo ($row['status']==1) ? " selected='selected'": "" ?>>öffentlich</option>
    <option value="2" <?php echo ($row['status']==2) ? " selected='selected'": "" ?>>privat</option>
    </select>

    <label>Dateiname:</label> <input class="form-control" name="filename" type="text" size="60" disabled="1" value="<?=$row['filename']?>"><br>
    <label>Größe:</label>     <input class="form-control" name="groesse" type="text" size="60" disabled="1" value="<?=$row['groesse']?>"><br>
    <label>Datum:</label>     <input class="form-control" name="datum" type="text" size="60" value="<?=$row['datum']?>"><br>
    <label>Sortierung:</label><input class="form-control" name="sort" type="text" size="60" value="<?=$row['sort']?>"><br>
    <input type="hidden" name="fach" value="<?=$row['fach']?>" />
    <input type="hidden" name="datei"  value="<?=$row['filename']?>" />
    <input type="hidden" name="id"  value="<?=$id?>" />
    <input class="btn btn-primary" name="submit" type="submit" value="Ändern">&nbsp;
    <input class="btn btn-warning" name="delete" type="submit" value="Löschen">
  </div>
  </form>
<p>&nbsp;</p>
<?php
}

include("footer.php");

?>
