<?php

use phpGPX\Models\GpxFile;
use phpGPX\Models\Link;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;

require_once 'vendor/autoload.php';
require_once ("config.php");

$strFilename="gpsdb.gpx";
$strBezeichnung="Track 1";

$id   = (is_numeric($_GET['id']))?$_GET['id']:-1;
$query="SELECT id,  bezeichnung, 
             DATE_FORMAT(trk_date,\"%d.%m.%Y\") as datum
          FROM datei 
		  WHERE  id=".$id;
 //DEBUG:echo $query;

 $stmt=$pdo->query($query);
 if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   $strFilename= $row['datum']."-".$row['bezeichnung'].".gpx";
   $strBezeichnung=$row['bezeichnung'];
 }

 $strSQL = "select lon,lat,ele,date from tracks where track_id=$id";
 $stmt=$pdo->query($strSQL);

 $data = array();
 $i=0;
 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     $data[$i]['longitude']=$row['lon'];
     $data[$i]['latitude']=$row['lat'];
     $data[$i]['elevation']=$row['ele'];
     $data[$i]['time']=new \DateTime("+ ".$i." MINUTE"); 
     $i++;  
 }

// Creating sample link object for metadata
$link 							= new Link();
$link->href 					= "https://bankerheide.de/gps/";
$link->text 					= 'GPS-DB by Walter Hupfeld';

// GpxFile contains data and handles serialization of objects
$gpx_file 						= new GpxFile();

// Creating sample Metadata object
$gpx_file->metadata 			= new Metadata();

// Time attribute is always \DateTime object!
$gpx_file->metadata->time 		= new \DateTime();

// Description of GPX file
$gpx_file->metadata->description = "generated from GPX-DB by Walter Hupfeld";

// Adding link created before to links array of metadata
// Metadata of GPX file can contain more than one link
$gpx_file->metadata->links[] 	= $link;

// Creating track
$track 							= new Track();

// Name of track
$track->name 					= sprintf($strBezeichnung);

// Type of data stored in track
$track->type 					= 'RUN';

// Source of GPS coordinates
$track->source 					= sprintf("GPS-DB");

// Creating Track segment
$segment 						= new Segment();


foreach ($data as $s_point)
{
	// Creating trackpoint
	$point 						= new Point(Point::TRACKPOINT);
	$point->latitude 			= $s_point['latitude'];
	$point->longitude 			= $s_point['longitude'];
	$point->elevation 			= $s_point['elevation'];
	$point->time 				   = $s_point['time'];

	$segment->points[] 			= $point;
}

// Add segment to segment array of track
$track->segments[] 				= $segment;

// Recalculate stats based on received data
$track->recalculateStats();

// Add track to file
$gpx_file->tracks[] 			= $track;

// Direct GPX output to browser

header("Content-Type: application/gpx+xml");
header("Content-Disposition: attachment; filename=".$strFilename);

echo $gpx_file->toXML()->saveXML();
exit();
