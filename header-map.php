<?php
  session_start();
  $strName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
  $numUserId=(isset($_SESSION['userid'])) ? $_SESSION['userid'] : "";
  $boolLogin = (!empty($numUserId));
  error_reporting(E_ALL);
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GPS-Datenbank</title>
	<link href="default.css" type="text/css" rel="stylesheet" media="screen" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<script src="leaflet/leaflet.js"></script>
	<script src="leaflet/gpx.js"></script>
	<link rel="stylesheet" href="leaflet/leaflet.css" />
  <style>

    .info {margin-top:4em;}
    a:link, a:visited {color:darkred;}
    a.btn:link, a.btn:visited {color:white;}
  </style>
</head>
<body>
<!--  Navbar -->
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="index.php">GPX-Trackverwaltung</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
          </li>
<?php
      if ($boolLogin) {
?>
          <li class="nav-item">
            <a class="nav-link" href="new_category.php">Neue Kategorie</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="upload_file.php">Upload</a>
          </li>
<?php
        }
?>
      <li class="nav-item">
       <?php  echo ($boolLogin) ?  "<a class=\"nav-link\" href=\"logout.php\">Logout</a>" :   "<a class=\"nav-link\" href=\"login.php\">Login</a>"; ?>
          </li>
<?php
      if ($boolLogin) echo "<li class='nav-item'><a class='nav-link'>(".$strName.")</a></li>";
?>
        </ul>

        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>
  </header>
