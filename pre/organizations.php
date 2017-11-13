<?php

$override = 1;
$local_id = getGUID();

$p = array();
$p['job_id'] = $local_id;

$data_json = json_encode($_body);

// Insert the Package
$query = "INSERT INTO job(id,path,verb,body,status) VALUES(";
$query .= "'" . $local_id . "',";
$query .= "'" . $route . "full/',";
$query .= "'" . $verb . "',";
$query .= "'" . $data_json . "',";
$query .= "'new')";
//echo "\n" . $query . "\n";

// Execute Query
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$response = $conn->exec($query);	

$F = $p;
?>