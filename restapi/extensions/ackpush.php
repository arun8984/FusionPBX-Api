<?php
include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
#include 'security.php';
set_time_limit(3600);
$msgid = (isset($_GET['msgid']) ? $_GET['msgid'] : null);
if ($msgid !== null) {

    $sql = "UPDATE pushmsg set ack = :ack where msgid = :msgid";

    $prep_statement = $db->prepare($sql);
    $prep_statement->execute(['msgid' => $msgid]);
}

?>
