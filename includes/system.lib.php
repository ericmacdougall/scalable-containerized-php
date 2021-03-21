<?php

$_systemAPI="https://{your-api}";

function get_raw_noauth($url) {

		$cURLConnection = curl_init();

		curl_setopt($cURLConnection, CURLOPT_URL, $url);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($cURLConnection);
		curl_close($cURLConnection);
	return $data;
}

function raw_get($url) {

		$cURLConnection = curl_init();

		curl_setopt($cURLConnection, CURLOPT_URL, $url);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($cURLConnection);
		curl_close($cURLConnection);
	return $data;
}

function system_get_raw_noauth($url) {
	global $_systemAPI;
	$url=ltrim($url,"/");
	return get_raw_noauth($_systemAPI.$url);
}

function system_get_noauth($url) {
	return json_decode(system_get_raw_noath($url),true);
}

function get_raw($url) {

global $_SESSION;
if(isset($_SESSION['system_access_token'])) {
$at=$_SESSION['system_access_token'];
} else { $at=""; }

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "accesstoken: ".$at,
    "Content-Type: application/vnd.api+json",
    "Accept: application/vnd.api+json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

}

function system_get_raw($url) {
global $_systemAPI;
$url=ltrim($url,"/");
return get_raw($_systemAPI.$url);
}

function system_get($url) {
return json_decode(system_get_raw($url),true);
}


function system_post_raw($url,$params) {
	global $_systemAPI;
	global $_SESSION;
if(isset($_SESSION['system_access_token'])) {
$at=$_SESSION['system_access_token'];
} else { $at=""; }

$url=ltrim($url,"/");
$url=$_systemAPI.$url;
	
//echo $url;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($params),
  CURLOPT_HTTPHEADER => array(
    "accesstoken: ".$at,
    "Content-Type: application/vnd.api+json",
    "Accept: application/vnd.api+json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}


function system_post_raw_noauth($url,$params) {
	global $_systemAPI;
	global $_SESSION;
if(isset($_SESSION['system_access_token'])) {
$at=$_SESSION['system_access_token'];
} else { $at=""; }
	
$url=ltrim($url,"/");
$url=$_systemAPI.$url;
//echo " -'".$url."'- ";

$curl = curl_init();

//echo $url;
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($params),
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/vnd.api+json",
    "Accept: application/vnd.api+json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}

function system_post($url,$params) {
return json_decode(system_post_raw($url,$params),true);
}

function system_post_noauth($url,$params) {
return json_decode(system_post_raw_noauth($url,$params),true);
}






































function system_patch_raw($url,$params) {
	global $_systemAPI;
	global $_SESSION;
if(isset($_SESSION['system_access_token'])) {
$at=$_SESSION['system_access_token'];
} else { $at=""; }

$url=ltrim($url,"/");
$url=$_systemAPI.$url;
	
//echo $url;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PATCH",
  CURLOPT_POSTFIELDS => json_encode($params),
  CURLOPT_HTTPHEADER => array(
    "accesstoken: ".$at,
    "Content-Type: application/vnd.api+json",
    "Accept: application/vnd.api+json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}



function system_patch($url,$params) {
return json_decode(system_patch_raw($url,$params),true);
}


















function system_delete_raw($url,$params) {
	global $_systemAPI;
	global $_SESSION;
	$at=$_SESSION['system_access_token'];

$url=ltrim($url,"/");
$url=$_systemAPI.$url;
	
//echo $url;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "DELETE",
  CURLOPT_POSTFIELDS => json_encode($params),
  CURLOPT_HTTPHEADER => array(
    "accesstoken: ".$at,
    "Content-Type: application/vnd.api+json",
    "Accept: application/vnd.api+json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}



function system_delete($url,$params) {
return json_decode(system_delete_raw($url,$params),true);
}