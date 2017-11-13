<?php

$override = 1;

$p = array();
$p['job_id'] = $id;
$p['status'] = 'not processed';	

$query = "SELECT * from job WHERE id = '" . $id . "' AND status = 'new'";
//echo $query;
$results = $conn->query($query);
//echo $results;

if(count($results) > 0)
	{
	foreach ($results as $row)
		{
		$path  = $row['path'];
		if(substr($path,0,1) == "/"){ $path = substr($path,1,strlen($path)); }
		$verb  = $row['verb'];
		$body  = $row['body'];
		$body_array = json_decode($body);
		$body_count = count($body_array);
		
		//echo $verb . "<br />";
		if(strtolower($verb) == 'post')
			{
				
			$hsda_url = $openapi['hsda']['schemes'][0] . '://' . $openapi['hsda']['host'] . $openapi['hsda']['basePath'];
			$hsda_url = $hsda_url . $path;
			//echo "url: " . $hsda_url . "<br />";
			
			// Send Auth Headers
			$headers = array('x-appid: ' . $admin_login,'x-appkey: ' . $admin_code);
	
			$http = curl_init();
			
			curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($http, CURLOPT_VERBOSE, 1);
			//curl_setopt($http, CURLOPT_HEADER, 1);
			
		//	echo $body . "<br />";
			
			curl_setopt($http,CURLOPT_URL, $hsda_url);
			curl_setopt($http,CURLOPT_POST, $body_count);
			curl_setopt($http,CURLOPT_POSTFIELDS, $body);
			curl_setopt($http, CURLOPT_HTTPHEADER, $headers); 
			
			$output = curl_exec($http);
			$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
			$info = curl_getinfo($http);
			
			//var_dump($output);
			
			$filesJson = json_decode($output,true);
			
			//var_dump($filesJson);	
				
			$complete = date("Y-m-d | h:i:sa");
			$query = "UPDATE job SET status = 'processed',complete = '" . $complete . "' WHERE id = '" . $id . "'";
			// Execute Query
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$response = $conn->exec($query);
			
			$p['status'] = 'processed';	
			$p['complete'] = $complete;
					
			}
		
		}
	}
$ReturnObject = $p;	
?>