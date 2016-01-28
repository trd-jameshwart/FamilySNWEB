<?php
//Silence must be heard!
$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';

define("BASE_URL",$http.$_SERVER['HTTP_HOST']);
define('SERVICE_URL',BASE_URL.'/service/');
define('UPLOADS_URL',$http.$_SERVER['HTTP_HOST'].'/uploads/');

$fileInfo = pathinfo(__FILE__);
define('UPLOADS_DIR',$fileInfo['dirname'].'/uploads/');

