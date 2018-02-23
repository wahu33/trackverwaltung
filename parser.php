<?php
	require_once ("config.php");
	include("header.php");
	require_once("vendor/autoload.php");

	use phpGPX\phpGPX;
	use phpGPX\Models\GpxFile;
	//	use phpGPX\Models\Link;
	//	use phpGPX\Models\Metadata;
	use phpGPX\Models\Point;
	use phpGPX\Models\Segment;
	use phpGPX\Models\Track;



  $id=(int)$_GET['id'];
  $query="SELECT id, bezeichnung,filename,kategorie  FROM datei d WHERE  d.id=$id";

  $result = $pdo->query($query);

  if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$numKategorie=$row['kategorie'];
    $gpx = new phpGPX();
		$strFile = $strUploaddir.$row['filename'];
    $file = $gpx->load($strFile);
		//DEBUG: echo "<pre>";print_r($file->metadata);echo"</pre><hr>";
		$maxlon =-100; $minlon=100; $maxlat=-100; $minlat=100; $maxele=0;$minele=10000;
		$trkcount=0;
    $trackdate = array();
    $distance=0;
		$numTrack=1;
		$pdo->query("delete from tracks where track_id=$id");
		foreach ($file->tracks as $track) {
			//DEBUG: echo "<pre>";print_r($track->stats);echo"</pre><hr>";
			$trackinfo=$track->stats->toArray();
			$trackdate[$numTrack]['start']=strtotime($trackinfo['startedAt']);
			$trackdate[$numTrack]['finish']=strtotime($trackinfo['finishedAt']);

			foreach ($track->segments as $segment) {
				//DEBUG:echo "<pre>";print_r($segment->points);echo"</pre><hr>";
				foreach ($segment->points as $point) {
						$lon = $point->longitude;
						$lat = $point->latitude;
						$ele = $point->elevation;
						$distance += $point->difference/1000;   //in km
						$datetime = $point->time;
						$time = $datetime->getTimestamp();

						if ($lon>$maxlon) $maxlon=$lon;
						if ($lon<$minlon) $minlon=$lon;
						if ($lat>$maxlat) $maxlat=$lat;
						if ($lat<$minlat) $minlat=$lat;
						if ($ele>$maxele) $maxele=$ele;
						if ($ele<$minele) $minele=$ele;



						$strSQL = "insert into tracks (track_id,lon,lat,ele,dist,date) values ($id,$lon,$lat,$ele,$distance,$time)";
						try {
							$pdo->query($strSQL);
						} catch (PDOException $e) {echo $e->getMessage(); die ("Fehler in Statement.");}
						$trkcount++;
				}
			}
			$numTrack++;
		}


    $startDate = $trackdate[1]['start'];
		$endDate =   $trackdate[$numTrack-1]['finish'];

		echo "<h2>GPS-Trackanalyse ".$row['bezeichnung']."</h2>";
		echo "Min. Lon: ".$minlon."<br />";
		echo "Max. Lon: ".$maxlon."<br />";
		echo "Min. Lat: ".$minlat."<br />";
		echo "Max. Lon: ".$maxlat."<br />";
		echo "Min. Höhe: ".$minele."<br />";
		echo "Max. Höhe: ".$maxele."<br />";
		echo "Start: ".date("d.m.Y H:m:s",$startDate)."<br />";
		echo "Ende: ".date("d.m.Y H:m:s",$endDate)."<br />";

		echo "Steckenpunkte: " . $trkcount . "<br />";
		echo "Steckenlänge: ". round($distance,3) .  "km<br />";

		$sql ="UPDATE datei SET
						lat_min=$minlat,lat_max=$maxlat,lon_min=$minlon,lon_max=$maxlon,
						trkpt_count=$trkcount,distance=$distance,
						trk_date='" . date("Y-m-d",$startDate) . "'
						WHERE id=".$row['id'];
		// echo $sql."<br />";
		try {
		    $pdo->query($sql);
	  } catch (PDOException $e) {echo $e->getMessage();  die();}

    $file->save($strUploaddir.'Track.json', \phpGPX\phpGPX::JSON_FORMAT);
		echo "<a href='gpx-files/Track.json" . "'>Json-File</a><br>";

		echo "<br><a class='btn btn-primary' href='map.php?id=$id'>Karte</a> ";
		echo " <a class='btn btn-primary' href='profile.php?id=$id'>Profil</a> ";
		echo " <a class='btn btn-primary' href='list.php?kategorie=$numKategorie'>zurück</a>";

} // if ($row..
include("footer.php");
?>
