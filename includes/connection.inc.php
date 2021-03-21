<?php
if(file_exists('/dev.txt')) {
	$_HOST="http://ec2-3-96-204-132.ca-central-1.compute.amazonaws.com/";
} else {
	$_HOST="https://thesystem.gg/";
}
$_CACHEBASE="/cache/";
$_WEBROOT='/var/www/html/';
$_PATHBASE="/";



require_once($_WEBROOT."includes/session.inc.php");
require_once($_WEBROOT."includes/system.lib.php");
if(!isset($_SESSION['system_access_token']) and !isset($_OVERRIDE)) {

 $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
 $url = $base_url . $_SERVER["REQUEST_URI"];
if(stristr($url,"process-")) {
	$url="";
}
	header("Location: ".$_HOST."/login.php?url=".urlencode($url));
	die();

$z=system_post("/users/refresh",array("email" => $_SESSION['system_email'],"refreshToken" => $_SESSION['system_userdata']['AuthenticationResult']['RefreshToken']));
if(isset($z['access_token']) and isset($z['refresh_token'])) {
	$_SESSION['system_access_token']=$z['access_token'];
	$_SESSION['system_userdata']['AuthenticationResult']['RefreshToken']=$z['refresh_token'];

if(!isset($_delaysessionwrite)) {
session_write_close();
}
} else {
	header("Location: ".$_HOST."/login.php?url=".urlencode($url));
	die();
}


}



function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. "<sup>".$ends[$number % 10]."</sup>";
}

function sortBySubValue($array, $value, $asc = true, $preserveKeys = false)
{
    if (is_object(reset($array))) {
        $preserveKeys ? uasort($array, function ($a, $b) use ($value, $asc) {
            return $a->{$value} == $b->{$value} ? 0 : ($a->{$value} <=> $b->{$value}) * ($asc ? 1 : -1);
        }) : usort($array, function ($a, $b) use ($value, $asc) {
            return $a->{$value} == $b->{$value} ? 0 : ($a->{$value} <=> $b->{$value}) * ($asc ? 1 : -1);
        });
    } else {
        $preserveKeys ? uasort($array, function ($a, $b) use ($value, $asc) {
            return $a[$value] == $b[$value] ? 0 : ($a[$value] <=> $b[$value]) * ($asc ? 1 : -1);
        }) : usort($array, function ($a, $b) use ($value, $asc) {
            return $a[$value] == $b[$value] ? 0 : ($a[$value] <=> $b[$value]) * ($asc ? 1 : -1);
        });
    }
    return $array;
}

function alnum($str) {
return preg_replace("/[^A-Za-z0-9]/", "", $str);
}
function jsonapiformat($type,$attributes,$id="") {

	$base=array();
	$base['data']=array();
	$base['data']['type']=$type;
	if($id != "") $base['data']['id']=$id;
	$base['data']['attributes']=array();
	$base['data']['attributes']=$attributes;

	return $base;
}

function jsonapistrip($data) {
	$data=$data['data'];
	$newdata=array();
	foreach($data as $k => $v) {

		$obj=array();
		$obj['type']=$v['type'];
		$obj['id']=$v['id'];
		foreach($v['attributes'] as $kk => $vv) {
			$obj[$kk]=$vv;
		}
		$newdata[]=$obj;
	}
	return $newdata;
}

function lowerall($arr) {
$new=array();
foreach($arr as $k => $v) {
$new[]=strtolower($v);
}
return $new;
}

function hasPermission($name) { 
$name=strtolower($name);
global $_SESSION;
$permissions=lowerall($_SESSION['scoreboardRolePermissions']);
if(in_array($name,$permissions) === false) {
return false;
} else {
return true;
}
}

function requirePermission($name,$redir) {
global $_SESSION;
$name=strtolower($name);
$permissions=lowerall($_SESSION['rolePermissions']);
if(in_array($name,$permissions) === false) {
//	die("Dont have '".$name."' in ".implode("','",$permissions));
	header("Location: ".$redir);
	die();
}
}


 define('AWS_S3_KEY', '{your-s3-key}');
 define('AWS_S3_SECRET', '{your-s3-secret}');
 define('AWS_S3_REGION', 'ca-central-1');
 define('AWS_DEFAULT_REGION', 'ca-central-1');
 define('AWS_S3_BUCKET', '{s3-bucket}');
 define('AWS_S3_URL', 'http://s3.'.AWS_S3_REGION.'.amazonaws.com/'.AWS_S3_BUCKET.'/');



