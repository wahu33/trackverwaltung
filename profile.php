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
	$strBezeichung="";
	$id=(int)$_GET['id'];
	$query="SELECT id, bezeichnung,kategorie,trkpt_count  FROM datei d WHERE  d.id=$id";
	$result = $pdo->query($query);
	if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$kategorie=$row['kategorie'];
		$strBezeichung=$row['bezeichnung'];
		$numTrackCount = $row['trkpt_count'];
    $numMod = $numTrackCount / 600;  // 600 Datenpunkte in der Darstellung
		 $strSQL = "select dist,ele from tracks where track_id=$id";
	   $stmt=$pdo->query($strSQL);
	   $strData="[";
		 $numCount=1;
	   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	     if ($numCount % $numMod == 0) {
				 $strData .= "[".$row['dist'].",".$row['ele']."],";
			 }
			 $numCount++;
	   }
		 $strData .= ']';
	 }
	echo "<h2>Höhenprofil</h2>";
	echo "<h3>$strBezeichung</h3>";
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
