<?php
  require_once ("config.php");
  $id=$_GET['id'];
  include("header.php");
  if (!$boolLogin) die("Fehler");
?>
<h2>Neue Kategorie einfügen</h2>
<form  method="post" action="new_category_chk.php">
  <div class="form-group myform">
<label>Bezeichnung</label>
<input class="form-control" type="text" size="30" name="bezeichnung" placeholder="Bezeichnung" /><br />
<label>Plural</label>
<input class="form-control" type="text" size="30" name="plural" placeholder="Bezeichnung plural"/><br />
<label>Beschreibung</label>
<input class="form-control" type="text" size="50" name="kommentar" placeholder="Kommentar" /><br />


<label>Sort</label>
<select name="sort" id="sort" class="form-control">
<?php
  for ($i=0;$i<130;$i=$i+10) {
      echo "<option value='$i'>$i</option>";
  }
?>
</select>
<br />

<label>Privat</label>
<select name="privat" id="privat" class="form-control">
  <option selected value="0">öffentlich</option>
  <option value="1">privat</option>
</select>
<br />
  <button type="submit" class="btn btn-primary">Abschicken</button>
  </div>
</form>

<?php
  include("footer.php");
?>
