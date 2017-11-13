<?php

$override = 1;

$F = array();
//$F['message'] = "Data Package!";

$name = $_body->name;
$datapackage_version = $_body->datapackage_version;
$title = $_body->title;
$description = $_body->description;
$version = $_body->version;
$name = $_body->name;
$keywords = $_body->keywords;
$licenses = $_body->licenses;
$sources = $_body->sources;
$contributors = $_body->contributors;
$maintainers = $_body->maintainers;
$publishers = $_body->publishers;
$resources = $_body->resources;

// Going to ignore most of the Datapackage.json fields until I get more feedback.
// Just going to process resources.

// Insert the Package
$query = "INSERT INTO datapackage(id,name,title,description) VALUES('" . $local_id . "','" . $name . "','" . $title . "','" . $description . "')";
//echo "\n" . $query . "\n";

// Execute Query
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$response = $conn->exec($query);

foreach($resources as $resource)
	{
	
	$datapackage_id = getGUID();
	
	$path = $resource->path;
	//echo $url . "\n";
	$url = $resource->url;
	
	// Insert the Package
	$query = "INSERT INTO resource(id,datapackage_id,path,url,status) VALUES('" . $datapackage_id . "','" . $local_id . "','" . $path . "','" . $url . "','new')";
	//echo "\n" . $query . "\n";
	
	// Execute Query
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$response = $conn->exec($query);
	
	}
	
$_body->id = $local_id;
$F = $_body;
?>