<?php

require 'api_cache/API_cache.php';
require 'functions.php';

$params_array = array();
foreach($_REQUEST as $key=>$value){
	//$$key = $value;
	if($key=="pageq")continue;
	$params_array[$key]=$value;
}
$pageq = $_REQUEST['pageq'];



if(isset($_SERVER['HTTPS'])){
	$protocol = "https";
}else{
	$protocol = "http";
}

$host = $protocol."://localhost/";
$endpoint = "JsonAPI/";
$this_path = dirname(__FILE__);


function outputdata($pageq,$cache_for_seconds,$params_array){
	
	global $this_path,$host,$endpoint;
	
	$api_call = $host.$endpoint.$pageq;
	
	$page_base_name = explode(".", $pageq);
	$page_base_name = $page_base_name[0];
	
	$temp_str = "";
	foreach ($params_array as $k=>$v){
		//$v = makeUnixFilename($v); 
		$temp_str .= $v; 
	}
	$temp_str = md5($page_base_name."_".$temp_str);
	$cache_file = $this_path."/caches/".$temp_str.".json";
	
	$api_cache = new API_cache ($api_call, $cache_for_seconds, $cache_file,$params_array);
  	if (!$res = $api_cache->get_api_cache())
		$res = "{error: 'Could not load cache'}";
	ob_start();
	echo $res;
	$json_body = ob_get_clean();
	header ('Content-Type: application/json');
	header ('Content-length: ' . strlen($json_body));
	header ("Expires: " . $api_cache->get_expires_datetime());
	return $json_body;
}


$secs = 1;
$mins = 60*$secs;
$hours = 60*$mins;

$pages_to_cache_array = array(
				"getArtistsAPI.php"=>12*$hours,
				"categoryAPI.php"=>24*$hours,
				"getAlbumsAPI.php"=>24*$hours,
				"featurePlaylistAPI.php"=>24*$hours,
				"newArrivalAlbumAPI.php"=>24*$hours,
				"contentAPI.php"=>30*$mins,
				"subcategoryAPI.php"=>24*$mins*$secs,
				"downloadAPI.php"=>1*$hours,
				"downloadlistAPI.php"=>1*$hours
				);
							
if(isset($pages_to_cache_array[$pageq])){
	$cache_for_seconds = $pages_to_cache_array[$pageq];
	$data = outputdata($pageq,$cache_for_seconds,$params_array);
  	echo $data;
}else{
	echo getfromurl($host.$endpoint.$pageq."?nocache=true",$params_array);
}
