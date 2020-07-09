<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$message = array();

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);

/*
 * Types include
 * inbound
 * outbound
 * */
$type = (isset($_GET['type']) ? $_GET['type'] : '');

if ($domain_uuid = null) {

    $sql = "select * from v_destinations where destination_type = '$type'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $destinations = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    $message = $destinations;

}else{

    $sql = "select * from v_destinations where domain_uuid = '$domain_uuid' and destination_type = '$type'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $destinations = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    $message = $destinations;

}

echo(json_encode(['destinatons' => $message]));
