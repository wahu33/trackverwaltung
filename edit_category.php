<?php

require_once("config.php");
include("header.php");

$numId = (int)$_GET['id'];

$strSQL = "select * from kategorie where id=".$numId;

$result = $pdo->query($strSQL);

if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $strBezeichnung = $row['bezeichnung'];
   $strPlural = $row['plural'];
   $strKommentar = $row['kommentar'];
   $numSort = $row['sort'];
   $numPrivat = $row['privat'];
}
?>
<h2>Kategorie bearbeiten</h2>
<form id="cat_form" method="post" action="edit_category_chk.php">
  <div class="form-group myform">
    <label>Bezeichnung: </label>
    <input  class="form-control" type="text" size="30" name="bezeichnung" value="<?php echo $strBezeichnung; ?>" /><br />
    <label>Plural</label>
    <input  class="form-control" type="text" size="30" name="plural"  value="<?php echo $strPlural; ?>" /><br />
    <label>Beschreibung</label>
    <input  class="form-control" type="text" size="50" name="kommentar"  value="<?php echo $strKommentar; ?>" /><br />
    <label>Sort</label>
    <input  class="form-control" type="text" size="10" name="sort"  value="<?php echo $numSort; ?>" /><br />
    <label>Privat</label>
    <select name="privat" id="privat" class="form-control">
      <option <?php echo ($numPrivat==0) ? " selected='selected'" : "" ?> value="0">öffentlich</option>
      <option <?php echo ($numPrivat==1) ? " selected='selected'" : "" ?> value="1">privat</option>
    </select>
    <br>
    <input type="submit"  class="btn btn-primary" value="Abschicken" />
    <input type="hidden" name="id" value="<?php echo $numId; ?>" />
  </div>
</form>

<?php
   include("footer.php");
?>
