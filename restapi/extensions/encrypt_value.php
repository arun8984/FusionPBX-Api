<?php
include 'security.php';
define('KEY_SECURE','h5r@mg7$#ueqdstj');
$source = Security::encrypt($_GET['source']), KEY_SECURE);
echo json_encode(array("result"=>"success" , 'value'=>$source ));
die;

?>