<?php
include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
#include 'security.php';
set_time_limit(3600);
$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$extension = (isset($_GET['extension']) ? $_GET['extension'] : null);
$token = (isset($_GET['token']) ? $_GET['token'] : null);

if ($domain_uuid !== null and $extension !== null and $token !== null) {
	
    $sql = "select count(*) as ExtCount from devices where extension = '$extension' and extendomain = '$domain_uuid' ";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    print_r($result)
    }
}

?>