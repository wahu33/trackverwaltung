<?php
  require("config.php");

  $strLogin    = $_POST['login'];
  $strPassword = $_POST['password'];

  $strSql = "select * from user where login='$strLogin' and password=password('$strPassword')";

  $stmt=$pdo->query($strSql);
  if ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
    session_start();
  	$_SESSION['user']=$row['name'];
  	$_SESSION['userid']=$row['id'];
    header("Location: index.php");
  }
  else {
    include("header.php");
?>
    <div class="alert alert-danger" role="alert">
    Passwort falsch!
    </div>
    <a class="btn btn-primary" href="login.php" role="button">zurück</a>
<?php
    include("footer.php");
  }

?>
