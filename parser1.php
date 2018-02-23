<?php
#############################################################
# GPX Parser um GPX Files zu Parsen
# GPX File erstellt mit gpsbable |
# <a href="http://www.gpsbabel.org" target="_blank">http://www.gpsbabel.org</a>
#############################################################

#<trkpt lat="50.284741667" lon="10.098406667">
#<ele>237.200000</ele>
#<time>2007-08-12T09:15:07Z</time>
#<course>90.900002</course>
#<speed>6.194445</speed>
#<fix>3d</fix>
#<sat>4</sat>
#<hdop>5.700000</hdop>
#<vdop>4.400000</vdop>
#<pdop>7.200000</pdop>
#</trkpt>




function startElement($parser, $element_name, $element_attribute) {
	global $ausgabe;
	global $res_lat,$res_lon,$res_ele,$res_time;

	$attr_array[] = array();

	$attr_lat   = $element_attribute['LAT']; // Latitude
	$attr_lon   = $element_attribute['LON']; // Longtidude
	$attr_ele   = $element_attribute['ELE']; // H�HE
	$attr_time  = $element_attribute['TIME']; // Datum und Zeit
	$attr_course = $element_attribute['COURSE']; //
	$attr_speed  = $element_attribute['SPEED']; //
	$attr_fix   = $element_attribute['FIX']; //
	$attr_sat   = $element_attribute['SAT']; //
	$attr_hdop  = $element_attribute['HDOP']; //
	$attr_vdop  = $element_attribute['VDOP']; //
	$attr_pdop  = $element_attribute['PDOP']; //


	//Umwandeln in Kleinbuchstaben
	$element_name = strtolower($element_name);

	//�berpr�fung des Elementnames <trkpt lat="50.284741667" lon="10.098406667">
	if ($element_name=="trkpt") {
	  //echo "LON: {$attr_lon}, LAT: {$attr_lat}, ";
	  //$result_array[]= array($attr_lat, $attr_lon);
	  $res_lat=$attr_lat;
	  $res_lon=$attr_lon;
	}

	// H�henmeter <ele>237.200000</ele>
	if($element_name=="ele"){
	  //echo "H&ouml;he:";// {$attr_ele}";
	}

	// GPS Time <time>2007-08-12T09:15:07Z</time>
	if($element_name=="time"){
	 //echo  "GPS Time: {$attr_time}";
	}

	// GPS course <course>90.900002</course>
	if($element_name=="course"){
	#$ausgabe .= "GPS Course: {$attr_course}";
	}

	// GPS Geschwindeigkeit <speed>6.194445</speed>
	if($element_name=="speed"){
	#$ausgabe .= "GPS Speed: {$attr_speed}";
	}

	// GPS Geschwindeigkeit <fix>3d</fix>
	if($element_name=="fix"){
	#$ausgabe .= "GPS Fix: {$attr_fix}";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="sat"){
	#$ausgabe .= "GPS Sat (Anzahl): {$attr_sat}";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="hdop"){
	#$ausgabe .= "GPS hdop: {$attr_hdop}";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="vdop"){
	#$ausgabe .= "GPS vdop: {$attr_vdop}";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="pdop"){
	#$ausgabe .= "GPS pdop: {$attr_pdop}";
	}
}

function endElement($parser, $element_name) {
	global $ausgabe,$res_ele,$res_time,$res_lon,$res_lat,$result_array;
	// in Kleinbuchstaben umwandeln
	$element_name = strtolower($element_name);
	// �berpr�fung des Names eines Elementes
	if ($element_name=="trkpt") {
	 $result_array[] = array($res_lat,$res_lon,$res_ele,$res_time);
	  //echo "<br />";
	}

	if($element_name=="ele"){
	  $res_ele=$ausgabe;
	}

	// H�henmeter
	if($element_name=="time"){
	  $res_time=$ausgabe;//echo "<br> ";
	}

	// Kurs
	if($element_name=="course"){
	#$ausgabe .= " | ";
	}

	// GPS Geschwindeigkeit
	if($element_name=="speed"){
	#$ausgabe .= " | ";
	}

	// GPS Geschwindeigkeit
	if($element_name=="fix"){
	#$ausgabe .= " | ";
	}

	// GPS Geschwindeigkeit
	if($element_name=="sat"){
	#$ausgabe .= " <br /> ";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="hdop"){
	#$ausgabe .=  " | ";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="vdop"){
	#$ausgabe .=  " | ";
	}

	// GPS Satelitenempfang <sat>4</sat>
	if($element_name=="pdop"){
	#$ausgabe .= "";
	}


}

function cdata($parser, $element_inhalt) {
  global $ausgabe;
  $ausgabe =  $element_inhalt;
}

// -------------------------------------------------------------------
 include("header.php");

 require_once ("config.php");
  $id=(int)$_GET['id'];
  $query="SELECT id, bezeichnung,filename  FROM datei d WHERE  d.id=$id";
 //DEBUG:  echo $query;
 $result = $pdo->query($query);
 if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$xmlFile = file($strUploaddir.$row['filename']);
	$parser = xml_parser_create();
	xml_set_element_handler($parser, "startElement", "endElement");
	xml_set_character_data_handler($parser, "cdata");

	foreach($xmlFile as $elem)  {
	   xml_parse($parser, $elem);
	   //  $result_array[] = array($res_lon,$res_lat,$res_ele,$res_time);
	}
	xml_parser_free($parser);

	echo "<h2>GPS-Trackanalyse ".$row['bezeichnung']."</h2>";

	$maxlon =-100; $minlon=100; $maxlat=-100; $minlat=100; $maxele=0;$minele=10000;
	$trkcount=0;
	foreach ($result_array as $res) {
		  //print_r($res);echo "<br />";
		$lon = $res[1];
		$lat = $res[0];
		$ele = $res[2];
		$trackdate = $res[4];

		if ($lon>$maxlon) $maxlon=$lon;
		if ($lon<$minlon) $minlon=$lon;
		if ($lat>$maxlat) $maxlat=$lat;
		if ($lat<$minlat) $minlat=$lat;
		if ($ele>$maxele) $maxele=$ele;
		if ($ele<$minele) $minele=$ele;
		$trkcount++;
	}


	$total_distance=0.0;
	for ($i=0; $i<count($result_array); $i++) {
	  if ($i>0) {
		$e= acos( sin($result_array[$i-1][0]/180*pi()) * sin($result_array[$i][0]/180*pi())  +
					cos($result_array[$i-1][0]/180*pi()) * cos($result_array[$i][0]/180*pi())  *
					cos($result_array[$i][1]/180*pi() - $result_array[$i-1][1]/180*pi()) ) * 6378.137;

		//echo $total_distance." - " .$e."<br />";
		if (!is_nan($e)) $total_distance += $e;
	  }
	}

	$strStartDate = $result_array[0][3];
	$strEndDate = $result_array[count($result_array)-1][3];
	$arrStart = explode("T",$strStartDate);
	$arrStart1['date']= explode("-",$arrStart[0]);
	$arrStart1['time']= explode(":",$arrStart[1]);

	$startDate = mktime($arrStart1['time'][0]+2,$arrStart1['time'][1],$arrStart1['time'][2],$arrStart1['date'][1],$arrStart1['date'][2],$arrStart1['date'][0]);


	$arrEnd = explode("T",$strEndDate);
	$arrEnd1['date']= explode("-",$arrEnd[0]);
	$arrEnd1['time']= explode(":",$arrEnd[1]);

	$endDate = mktime($arrEnd1['time'][0]+2,$arrEnd1['time'][1],$arrEnd1['time'][2],$arrEnd1['date'][1],$arrEnd1['date'][2],$arrEnd1['date'][0]);


	echo "Min. Lon: ".$minlon."<br />";
	echo "Max. Lon: ".$maxlon."<br />";
	echo "Min. Lat: ".$minlat."<br />";
	echo "Max. Lon: ".$maxlat."<br />";
	echo "Min. Höhe: ".$minele."<br />";
	echo "Max. Höhe: ".$maxele."<br />";
	echo "Start:".date("d.m.Y H:m:s",$startDate)."<br />";
	echo "Ende: ".date("d.m.Y H:m:s",$endDate)."<br />";
	echo "Steckenpunkte: " . $trkcount . "<br />";
	echo "Steckenlänge: ". round($total_distance,3) .  "km<br />";

	$sql ="update datei set
		lat_min=$minlat,lat_max=$maxlat,lon_min=$minlon,lon_max=$maxlon,
		trkpt_count=$trkcount,distance=$total_distance,trk_date='" . date("Y-m-d",$startDate) . "' where id=".$row['id'];
	// echo $sql."<br />";
	$pdo->query($sql);

	echo "<a href=\"map.php?id=$id\">Karte</a>";

} // if ($row..
include("footer.php");
?>
