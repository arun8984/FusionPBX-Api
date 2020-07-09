<?php
error_reporting(0);
header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

include 'security.php';
define('KEY_SECURE','h5r@mg7$#ueqdstj');
$source = Security::encrypt($_GET['source'], KEY_SECURE);
echo json_encode(array("result"=>"success" , 'value'=>$source ));
die;

?>