<?php
	require_once ("config.php");
	require_once("vendor/autoload.php");
	include("header.php");
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- <script src="https://code.highcharts.com/modules/boost.js"></script> -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/annotations.js"></script>
<?php
  use phpGPX\phpGPX;
	use phpGPX\Models\GpxFile;
	use phpGPX\Models\Link;
	use phpGPX\Models\Metadata;
	use phpGPX\Models\Point;
	use phpGPX\Models\Segment;
	use phpGPX\Models\Track;

	$gpx = new phpGPX();
	$strBezeichung="";

	$id=(int)$_GET['id'];
	$query="SELECT id, bezeichnung,filename,kategorie  FROM datei d WHERE  d.id=$id";
	$result = $pdo->query($query);
	if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $strPath=$strUploaddir.$row['filename'];
	    $file = $gpx->load($strPath);
			$kategorie=$row['kategorie'];
			$strBezeichung=$row['bezeichnung'];
			echo "<h2>Trackstatistik</h2>";
			echo "<h3>$strBezeichung</h3>";
      $numTrack=1;
			//DEBUG:	echo "<pre>";print_r($file->metadata);echo"</pre><hr>";

			foreach ($file->tracks as $track)
			{
          echo "<h4>Track $numTrack</h4>";
					//DEBUG:	echo "<pre>";print_r($track->stats);echo"</pre><hr>";
					$trackinfo=$track->stats->toArray();
					echo "<table class='table  table-striped table-sm' style='max-width:25em;'>";
					echo "<tr><th>Entfernung</th>           <td>". round($trackinfo['distance']/1000,2)   ." km </td></tr>\n";
					echo "<tr><th>Mittl. Geschwindigkeit</th><td>".round($trackinfo['avgSpeed']*3.6,1)        ." km/h</td></tr>\n";
					echo "<tr><th>Geschwindigkeit</th>      <td>".round($trackinfo['avgPace']/60,2)      ." min/km</td></tr>\n";
					echo "<tr><th>Min. Höhe</th>            <td>".$trackinfo['minAltitude']  ." m</td></tr>\n";
					echo "<tr><th>Max. Höhe</th>            <td>".$trackinfo['maxAltitude']  ." m</td></tr>\n";
					echo "<tr><th>Anstieg gesamt</th>       <td>".$trackinfo['cumulativeElevationGain']." m</td></tr>\n";
					echo "<tr><th>Startzeit</th>            <td>".date("d.m.Y H:i",strtotime($trackinfo['startedAt']))    ." Uhr</td></tr>\n";
					echo "<tr><th>Endzeit</th>              <td>".date("d.m.Y H:i",strtotime($trackinfo['finishedAt']))   ." Uhr</td></tr>\n";
					echo "<tr><th>Dauer</th>                <td>". toStd($trackinfo['duration'])     ."</td></tr>\n";
					echo "</table>";

/*
					echo "<h3>Segemente</h3>";
          echo "<table class='table  table-striped table-sm'>";
					echo "<thead>";
					echo "<tr><th>Entfernung</th><th>Mittl. Geschw.</th><th>min. Höhe</th><th>max. Höhe</th><th>StartZeit</th><th>Endzeit</th><th>Dauer</th></tr>";
          echo "</thead>";
					echo "<tbody>";
			    foreach ($track->segments as $segment)
			    {
						  $segmentinfo=$segment->stats->toArray();
							echo "<tr>";
							echo "<td>".round($segmentinfo['distance']/1000,2) ." km</td>";
							echo "<td>".round($segmentinfo['avgSpeed']*3.6,2) ." km/h</td>";
							echo "<td>".$segmentinfo['minAltitude'] ."</td>";
							echo "<td>".$segmentinfo['maxAltitude'] ."</td>";
							echo "<td>".date("H:i",strtotime($segmentinfo['startedAt'])) ." Uhr</td>";
							echo "<td>".date("H:i",strtotime($segmentinfo['finishedAt'])) ." Uhr</td>";
							echo "<td>".toStd($segmentinfo['duration']) ."</td>";
							echo "</tr>\n";
			    }
					echo "</tbody>";
					echo "</table>\n";
*/
					$numTrack++;
			}

			$strData="[";
			$numCount=0;
			$numOffset=0;
			foreach ($file->tracks as $track) {
				foreach ($track->segments as $segment) {
					//DEBUG:echo "<pre>";print_r($segment->points);echo"</pre><hr>";
					foreach ($segment->points as $point) {
							//echo ($point->elevation);		echo "<br>";
							if ($numCount % 10 == 0) {
									$strElevation =  $point->elevation;
									$numDistance  =  round($point->distance/1000 ,3);
									$strDistance = $numDistance+$numOffset;
								  $strData .= "[". $strDistance .",".$strElevation."],";
							}
							$numCount++;
					}
				}
				$numOffset+=$numDistance;
			}
			$strData.="];";
	}
  echo "<div id='container' style='height: 500px; max-width: 1000px; margin: 0 auto'></div>";
	echo "<br><a class='btn btn-primary' href='map.php?id=$id'>Karte</a> ";
	echo " <a class='btn btn-primary' href='list.php?kategorie=$kategorie'>zurück</a>";
//	echo $strData;
	echo "<hr>";
?>
<script>
var elevationData = <?php echo $strData; ?>

// Now create the chart
Highcharts.chart('container', {

    chart: {
        type: 'area',
        zoomType: 'x',
        panning: true,
        panKey: 'shift'
    },

    title: {
        text: 'Höhendiagraumm'
    },

    subtitle: {
        text: '<?=$strBezeichung?>'
    },

    annotations: [{
        labelOptions: {
            backgroundColor: 'rgba(255,255,255,0.5)',
            verticalAlign: 'top',
            y: 15
        },
        labels: []
    }],

    xAxis: {
        labels: {
            format: '{value} km'
        },
        minRange: 5,
        title: {
            text: 'Entfernung'
        }
    },

    yAxis: {
        startOnTick: true,
        endOnTick: false,
        maxPadding: 0.35,
        title: {
            text: null
        },
        labels: {
            format: '{value} m'
        }
    },

    tooltip: {
        headerFormat: 'Entfernung: {point.x:.1f} km<br>',
        pointFormat: '{point.y} m a. s. l.',
        shared: true
    },

    legend: {
        enabled: false
    },

    series: [{
        data: elevationData,
        lineColor: Highcharts.getOptions().colors[1],
        color: Highcharts.getOptions().colors[2],
        fillOpacity: 0.5,
        name: 'Höhe',
        marker: {
            enabled: false
        },
        threshold: null
    }]

});

	</script>
<?php
	include("footer.php");
?>
