<?php   
   require_once ("config.php");
   $id=$_GET['id'];
   if (!is_numeric($id)) die("Falscher Paramter!");
   $query="SELECT d.id as id, d.bezeichnung as bezeichnung, d.beschreibung, filename,k.bezeichnung as katbez, d.kategorie as kategorie, q.bezeichnung as quelle,
                  groesse,status,DATE_FORMAT(datum,\"%d.%m.%Y\") as datum,d.sort,d.trkpt_count,d.distance,
                  lon_max,lon_min,lat_max,lat_min
                  FROM datei d, kategorie k, quelle q
                 WHERE d.kategorie=k.id AND q.id=d.quelle and d.id=".$id;
   //DEBUG:    echo $query;
   $stmt=$pdo->prepare($query);
   $stmt->execute();
   if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //Karte kopieren
                                                 
  $strFilenameNew=md5($row['filename']).".gpx";
                                                                                                                        
  if (!file_exists($strFiledir.$strFilenameNew))
    exec("/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $row['filename'] ." -x simplify,count=1000 -o gpx -F ". $strFiledir . $strFilenameNew ,$out);
                                                                                                                                      
?>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <link href="default.css" type="text/css" rel="stylesheet" media="screen" /> 
    <title><?=$row['katbez']?></title>
    <style type="text/css">
        body { width:calc(100% - 50px);padding: 10px 10px 10px 30px }
        #map1 { width:49%;height:500px;height:100vh;display:inline-block;vertical-align:top }
        #map1_profiles { width:49%;height:500px;height:100vh;margin-left:1%;display:inline-block; }
        #map1_hp { height:30%;height:calc((100% + 64px)/3);margin-top:10px }
        #map1_sp { height:30%;height:calc((100% + 64px)/3);margin-top:-37px }
        #map1_vp { height:30%;height:calc((100% + 64px)/3);margin-top:-37px }
        @media screen and (max-width:500px) {
        #map1, #map1_profiles { width:100%;height:100vh; display:block; margin:0 }            }
    </style>
  </head>
  <body>
   <h1><?=$row['katbez']?></h1>
    <h2><?=$row['bezeichnung']?></h2>

    <div id="map1" class="gpxview:files/<?=$strFilenameNew?>:Karte"><noscript><p>Zum Anzeigen der Karte wird Javascript benötigt.</p></noscript></div>
     <div id="map1_profiles">
       <div id="map1_hp" class="no_x"><noscript><p>Zum Anzeigen des Profils wird Javascript benötigt.</p></noscript></div>
       <div id="map1_sp" class="no_x"><noscript><p>Zum Anzeigen des Profils wird Javascript benötigt.</p></noscript></div>
       <div id="map1_vp"><noscript><p>Zum Anzeigen des Profils wird Javascript benötigt.</p></noscript></div>
     </div>
 
    <script type="text/javascript">
//      var Largemapcontrol = true;
      var Overviewmapcontrol = true;
//      var Legende = false;
			var Fullscreenbutton = true;
    </script>
    <script src="GM_Utils/GPX2GM.js"></script>     
     
<?php echo "<br style=\"clear:left\"/><br /><a href=\"list.php?kategorie=". $row['kategorie']."\">zur&uuml;ck</a>"; ?>

<p style="font-size:.7em"><a href="http://www.j-berkemeier.de/GPXViewer/">GPX-Viewer</a> von <a href="http://www.j-berkemeier.de">J&uuml;rgen Berkemeier</a></p>
  </body>
</html>
<?php } ?>
