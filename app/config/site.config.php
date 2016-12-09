<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("_SCHEMA","/home/mail/h01/wm50/");
define("_CPL", _SCHEMA."cpl/");
define("_CACHE", _SCHEMA."cache/");

$ADMIN_IP = array(
	gethostbyname('office.ip.wiro.kr'),
);
if(in_array($_SERVER['REMOTE_ADDR'],$ADMIN_IP)){
	define("_ADMINIP",$_SERVER['REMOTE_ADDR']);
}else{
	define("_ADMINIP",gethostbyname('office.ip.wiro.kr'));
}
?>
