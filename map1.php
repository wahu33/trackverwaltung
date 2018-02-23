<?php
 require_once ("config.php");
  if (is_numeric($_GET['id'])) $id=$_GET['id'];
  $zoom =  (isset($_GET['zoom']) && is_numeric($_GET['zoom'])) ? $_GET['zoom'] : -1;
  
   $query="SELECT d.id as id, d.bezeichnung as bezeichnung, d.beschreibung, filename,k.bezeichnung as katbez, d.kategorie, q.bezeichnung as quelle,
                    groesse,status,DATE_FORMAT(datum,\"%d.%m.%Y\") as datum,d.sort,d.trkpt_count,d.distance,
					lon_max,lon_min,lat_max,lat_min
            FROM datei d, kategorie k, quelle q 
			WHERE d.kategorie=k.id AND q.id=d.quelle and d.id=".$id;
 //DEBUG:    echo $query;
 $result = mysql_query($query) or die(mysql_error());
 if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $lon=($row['lon_max']+$row['lon_min'])/2;
   $lat=($row['lat_max']+$row['lat_min'])/2;
   $width = max ($row['lon_max']-$row['lon_min'],$row['lat_max']-$row['lat_min']);
  
 if ($zoom<0) { 
   if ($width<0.002) $zoom=17;
   else if ($width<0.02) $zoom=15;
   else if ($width<0.1) $zoom=14;
   else if ($width<0.25) $zoom=12;
   else if ($width<0.5) $zoom=11;
   else if ($width<1.5) $zoom=10;
   else if ($width<3.0) $zoom=9;
   else if ($width<4.0) $zoom=8;
   else $zoom=7;
}
   
   //Karte kopieren 
   
   $strFiledir = "/var/www/vhosts/hupfeld-hamm.de/httpdocs/gps/files/";
   $strFilenameNew=md5($row['filename']).".gpx";
   $strKmlNew=md5($row['filename']).".kml";
   
   if (!file_exists($strFiledir.$strFilenameNew))
       exec("/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $row['filename'] ." -x simplify,count=1000 -o gpx -F ". $strFiledir . $strFilenameNew ,$out);
   
   if (!file_exists($strFiledir.$strKmlNew))
       exec("/usr/bin/gpsbabel -t -i gpx -f " . $strUploaddir . $row['filename'] ." -x simplify,count=1000  -o kml,points=0,lines=1,line_color=AF801FE0 -F ". $strFiledir . $strKmlNew ,$out);

   
   include("header-map.php");
?>
<!-- body.onload is called once the page is loaded (call the 'init' function) -->
<body onLoad="init();">
	<!-- define a DIV into which the map will appear. Make it take up the whole window -->
	<div id="map"></div>
<?php
  }
include("footer.php");  
?>
