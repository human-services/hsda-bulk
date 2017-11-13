<?php
function processCsv($absolutePath)
{
    $csv = array_map('str_getcsv', file($absolutePath));
    $headers = $csv[0];
    unset($csv[0]);
    $rowsWithKeys = [];
    foreach ($csv as $row) {
        $newRow = [];
        foreach ($headers as $k => $key) {
            $newRow[$key] = $row[$k];
        }
        $rowsWithKeys[] = $newRow;
    }
    return $rowsWithKeys;
}

$processed = 0;
$override = 1;
$query = "SELECT * from resource WHERE id = '" . $id2 . "' AND status = 'new'";
//echo $query;
$results = $conn->query($query);
//echo $results;

$p = array();
$p['resource_id'] = $id2;
$p['url'] = '';
$p['records_processed'] = $processed;	

if(count($results) > 0)
	{
	foreach ($results as $row)
		{
		$id = $row['id'];
		$datapackage_id= $row['datapackage_id'];
		$path = $row['path'];
		$path = str_replace(".csv","",$path) . "/";
		$url = $row['url'];
		$status = $row['status'];
		$verb = "POST";
		
		//echo $url;
		$resource_raw = file_get_contents($url);
		$file_id = getGUID();	
		$local_temp = "/var/www/html/hsda-bulk/temp/" . $file_id;
		$myfile = fopen($local_temp, "w") or die("Unable to open file!");
		fwrite($myfile, $resource_raw);
		fclose($myfile);
		$data = processCsv($local_temp);
		unlink($local_temp);
		$resource_json = json_encode($data);
		//echo $resource_json;
		foreach($data as $row)
			{
				
			$local_id = getGUID();	
			$data_json = json_encode($row);
			
			//echo $data_json . "\n";
			
			// Insert the Package
			$query = "INSERT INTO job(id,path,verb,body,status) VALUES(";
			$query .= "'" . $local_id . "',";
			$query .= "'" . $path . "',";
			$query .= "'" . $verb . "',";
			$query .= "'" . $data_json . "',";
			$query .= "'new')";
			//echo "\n" . $query . "\n";
			
			// Execute Query
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$response = $conn->exec($query);	
			
			$processed++;
			
			}	
					
		$query = "UPDATE resource SET status = 'processed' WHERE id = '" . $id2 . "';";
		// Execute Query
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$response = $conn->exec($query);			
				
		$p = array();
		$p['resource_id'] = $id2;
		$p['url'] = $url;
		$p['records_processed'] = $processed;				
				
		}
	}
	
$ReturnObject = $p;
?>