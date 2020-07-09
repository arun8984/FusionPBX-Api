<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$message = array();

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);

if ($domain_uuid == null) {
    $sql = "select * from v_users";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $users = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    $message = ['users' => $users];
    echo(json_encode($message));
}

if ($domain_uuid != null) {
    $sql = "select * from v_users where domain_uuid = '$domain_uuid'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $users = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    $message = ['users' => $users];
    echo(json_encode($message));
}