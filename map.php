<?php
  require_once ("config.php");

  $id   = (is_numeric($_GET['id']))?$_GET['id']:-1;
  $query="SELECT d.id as id, d.bezeichnung as bezeichnung, d.beschreibung,
             filename,k.bezeichnung as katbez, d.kategorie as kategorie,
             q.bezeichnung as quelle, status,
             groesse,status,DATE_FORMAT(trk_date,\"%d.%m.%Y\") as datum,
             d.sort,d.trkpt_count,d.distance,
    				 lon_max,lon_min,lat_max,lat_min
          FROM datei d, kategorie k, quelle q
			    WHERE d.kategorie=k.id AND q.id=d.quelle and d.id=".$id;
 //DEBUG:echo $query;

 $stmt=$pdo->query($query);
 if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   //$lon=($row['lon_max']+$row['lon_min'])/2;
   //$lat=($row['lat_max']+$row['lat_min'])/2;
   //$width = max ($row['lon_max']-$row['lon_min'],$row['lat_max']-$row['lat_min']);

   //Karte kopieren
   $strFilename = escapeshellcmd($row['filename']);
   //$strFilname = $row['filename'];
   $strFilenameNew=md5($strFilename).".gpx";
   $strKmlNew=md5($strFilename).".kml";
/*
   if (!file_exists($strFiledir.$strFilenameNew))
       exec("/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $strFilename ." -x simplify,count=1000 -o gpx -F ". $strFiledir . $strFilenameNew ,$out);

   if (!file_exists($strFiledir.$strKmlNew))
       exec("/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $strFilename ." -x simplify,count=1000  -o kml,points=0,lines=1,line_color=AF801FE0 -F ". $strFiledir . $strKmlNew ,$out);
*/
   $strFilenameNew="gpx-files/".$strFilename;
   include("header-map.php");
   //echo "exec(/usr/bin/gpsbabel -t -i gpx -f $strUploaddir$strFilename -x simplify,count=1000 -o gpx -F $strFiledir$strFilenameNew ,null)";
?>

<div id="wrapper"><div id="map"></div></div>
<div class="info card">
  <div class="card-header">
    <h3><a href="list.php?kategorie=<?=$row['kategorie']?>"><?=$row['katbez']?></a></h3>
    <h4><?=$row['bezeichnung']?></h4>
  </div>
  <div class='card-body'>
    <p><?=$row['beschreibung']?></p>
    <strong>Entfernung:</strong> <?php echo round($row['distance'],2) ?> km<br>
    <strong>Trackpunkte:</strong> <?php echo $row['trkpt_count'] ?><br />
    <strong>Datum:</strong> <?=$row['datum']?><br />
    <br />
    <a href="info.php?id=<?=$id?>"><i class="fa fa-info-circle" aria-hidden="true"></i> Trackdetails</a><br>
    <a href="profile.php?id=<?=$id?>"><i class="fa fa-area-chart" aria-hidden="true"></i> Höhenprofil</a>
    <br><br>
    <a  class="btn btn-primary" href="list.php?kategorie=<?=$row['kategorie']?>">zurück</a><br />
    </div>
  </div>
    <?php
        //echo "/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $row['filename'] ." -x simplify,count=1000 -o kml -F ". $strFiledir . $strKmlNew;
        if ($boolLogin) {
    ?>
          <br>
      <div class="card">
      <div class="card-header"><strong>Info</strong></div>
      <div class="card-body">
        	Sort: <?=$row['sort']?><br>
        	Status: <?php echo ($row['status']==0) ? "öffentlich" : "privat"; ?>
          <br>
          <a class="btn btn-primary" href="edit_file.php?id=<?=$id?>">Edit</a>
          <a class="btn btn-primary" href="parser1.php?id=<?=$id?>">Parser</a><br>
      </div>
   </div>
<?php
    }
  }
  $strSQL = "select lon,lat from tracks where track_id=$id";
  $stmt=$pdo->query($strSQL);
  $strData="[";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $strData .= "[".$row['lat'].",".$row['lon']."],";
  }
  $strData .= "]";
?>
<script type="text/javascript">
      var latlngs = <?= $strData ?>;
      var map = L.map('map');
      var mbUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
      L.tileLayer(mbUrl, {attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);
      var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);
      // zoom the map to the polyline
      map.fitBounds(polyline.getBounds());

      //var gpx = '<?php echo $strFilenameNew ?>'; // URL to your GPX file or the GPX itself
      //new L.GPX(gpx, {async: true}).on('loaded', function(e) { map.fitBounds(e.target.getBounds()); }).addTo(map);

</script>
<?php
include("footer.php");
?>
