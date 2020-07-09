<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$message = array();


$sql = "select * from v_domains";
$prep_statement = $db->prepare(check_sql($sql));
$prep_statement->execute();
$domains = $prep_statement->fetchAll(PDO::FETCH_NAMED);
unset($sql, $prep_statement);
$message = ['domains' => $domains];
echo(json_encode($message));