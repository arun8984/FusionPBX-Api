<?php
include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
#include 'security.php';
set_time_limit(3600);
$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$extension = (isset($_GET['extension']) ? $_GET['extension'] : null);
$token = (isset($_GET['token']) ? $_GET['token'] : null);
$os = (isset($_GET['os']) ? $_GET['os'] : null);
if ($domain_uuid !== null and $extension !== null and $token !== null and $os !== null) {
	
    $sql = "select * from devices where extension = '$extension' and domain_uuid = '$domain_uuid' ";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    if (sizeof($result) == 0){
	$sql = "INSERT INTO devices (extension, domain_uuid, os, pushtoken)VALUES(:extension, :domain_uuid, :os, :token)";
    }else{
	$sql="UPDATE devices set pushtoken = :token, os = :os where extension = :extension and domain_uuid = :domain_uuid";
    }
    $prep_statement = $db->prepare($sql);
    $prep_statement->execute(['extension'=>$extension,'domain_uuid'=>$domain_uuid,'os'=>$os,'token'=>$token]);
    
    
}

?>
