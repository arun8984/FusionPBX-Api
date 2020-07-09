<?php
include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
#include 'security.php';

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid']:null);

if($domain_uuid !== null){
	$sql = "select d.domain_name, e.extension, e.password ,e.effective_caller_id_name from v_extensions e, v_domains d  where e.domain_uuid = d.domain_uuid and d.domain_uuid= '$domain_uuid' ";
	$prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $result;
	
	echo(json_encode($message));
}

?>