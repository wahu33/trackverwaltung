<?php
	require_once ("config.php");
	require_once("vendor/autoload.php");
	include("header.php");

  use phpGPX\phpGPX;
	use phpGPX\Models\GpxFile;
//	use phpGPX\Models\Link;
//	use phpGPX\Models\Metadata;
	use phpGPX\Models\Point;
	use phpGPX\Models\Segment;
	use phpGPX\Models\Track;

	$gpx = new phpGPX();
	$strBezeichung="";

	$id=(int)$_GET['id'];
	$query="SELECT id, bezeichnung,filename,kategorie,trkpt_count,trk_date,distance  FROM datei d WHERE  d.id=$id";
	$result = $pdo->query($query);
	if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$kategorie=$row['kategorie'];
		$strBezeichung=$row['bezeichnung'];
		echo "<h2>Track-Details</h2>";
		echo "<h3>$strBezeichung</h3>";
		echo "<table class='table  table-striped table-sm' style='max-width:45em;'>";
		echo "<tr><th>Bezeichnung</th><td>" . $row['bezeichnung'] ."</td></tr>";
		echo "<tr><th>Dateiname</th><td>" . $row['filename'] ."</td></tr>";
		echo "<tr><th>Trackpoints</th><td>" . $row['trkpt_count'] ."</td></tr>";
		echo "<tr><th>Entfernung</th><td>" . round($row['distance'],3) ." km</td></tr>";
		echo "<tr><th>Datum</th><td>" . round($row['trk_date'],3) ." </td></tr>";
		echo "</table>\n";
		echo "<br>";
		$strPath=$strUploaddir.$row['filename'];
		if (file_exists($strPath)) {
			$file = $gpx->load($strPath);
			
			$numTrack=1;
				//DEBUG:	echo "<pre>";print_r($file->metadata);echo"</pre><hr>";
				$arrTracks = array();
				foreach ($file->tracks as $track)
				{
						//DEBUG:	echo "<pre>";print_r($track->stats);echo"</pre><hr>";
						$trackinfo=$track->stats->toArray();
						$arrTrack[$numTrack]['distance']=round($trackinfo['distance']/1000,2)." km";
						$arrTrack[$numTrack]['avgSpeed']=round($trackinfo['avgSpeed']*3.6,1) ." km/h";
						$arrTrack[$numTrack]['avgPace']= round($trackinfo['avgPace']/60,2)   ." min/km";
						$arrTrack[$numTrack]['minAltitude']= round($trackinfo['minAltitude'],0)  ." m";
						$arrTrack[$numTrack]['maxAltitude']=   round($trackinfo['maxAltitude'],0)  ." m";
						$arrTrack[$numTrack]['cumulativeElevationGain']=round($trackinfo['cumulativeElevationGain'],1)." m";
						$arrTrack[$numTrack]['startedAt']=date("d.m.Y H:i",strtotime($trackinfo['startedAt']))." Uhr";
						$arrTrack[$numTrack]['finishedAt']=date("d.m.Y H:i",strtotime($trackinfo['finishedAt']))." Uhr";
						$arrTrack[$numTrack]['duration']=toStd($trackinfo['duration']);
						$numTrack++;
				}
				$arrLegende = array ("distance"=>"Entfernung",
														"avgSpeed"=>"Mittl. Geschwindigkeit",
														"avgPace"=>"Geschwindigkeit",
														"minAltitude"=>"Min. Höhe",
														"maxAltitude"=>"Max. Höhe",
														"cumulativeElevationGain"=>"Anstieg gesamt",
														"startedAt"=>"Startzeit",
														"finishedAt"=>"Endzeit",
														"duration"=>"Dauer");

			echo "<br>";
			echo "<table class='table  table-striped table-sm' style='max-width:45em;'>";
			echo "<tr><th></th>";
			for ($i=1;$i<$numTrack;$i++) {echo "<th>Track $i</th>";}
			echo "</tr>\n";
			foreach ($arrTrack[1] as $key => $item) {
					echo "<tr><th>".$arrLegende[$key]."</th>";
					for ($i=1;$i<$numTrack;$i++) {
							echo "<td>".$arrTrack[$i][$key]."</td>";
					}
					echo "</tr>\n";
			}
			echo "</table>\n";
		}
	}
	echo "<br><a class='btn btn-primary' href='map.php?id=$id'>Karte</a> ";
	echo " <a class='btn btn-primary' href='list.php?kategorie=$kategorie'>zurück</a>";
//	echo $strData;
	echo "<hr>";
	include("footer.php");
?>
