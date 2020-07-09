<?php
error_reporting(0);
header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
include 'security.php';

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid']:null);
$extension = (isset($_GET['extension']) ? $_GET['extension']:null);
$password = (isset($_GET['password']) ? $_GET['password']:null);
$message = array(['message' => 'Missing info!']);
define('KEY_SECURE','h5r@mg7$#ueqdstj');
/*Show All Extensions across all domains*/
if ($domain_uuid == null and $extension == null) {
    $sql = "select domain_uuid from v_extensions ";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $extensions = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);

    $domains = array();
    foreach ($extensions as $extension) {
        if (!in_array($extension['domain_uuid'], $domains)) {

            $sql = "select extension_uuid,domain_uuid,extension,effective_caller_id_name,effective_caller_id_number,outbound_caller_id_name,outbound_caller_id_number from v_extensions where domain_uuid = '$extension[domain_uuid]'";
            $prep_statement = $db->prepare(check_sql($sql));
            $prep_statement->execute();
            $domainExtensions = $prep_statement->fetchAll(PDO::FETCH_NAMED);
            unset($sql);

            array_push($domains, [
                'domain_uuid' => $extension['domain_uuid'],
                'extensions' => $domainExtensions,
            ]);

        }
    }

    $message = ['domains' => $domains];
}

/*Show All extensions for a domain*/
if ($extension == null and $domain_uuid !== null) {
    $sql = "select extension_uuid,domain_uuid,extension,effective_caller_id_name,effective_caller_id_number,outbound_caller_id_name,outbound_caller_id_number from v_extensions where domain_uuid = '$domain_uuid' ";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $extensions = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $extensions;
}

/*Show an single extension under a specific domain*/
if ($extension !== null and $domain_uuid !== null) {
    $sql = "select extension_uuid,domain_uuid,extension,effective_caller_id_name,effective_caller_id_number,outbound_caller_id_name,outbound_caller_id_number from v_extensions ";
    $sql .= "where domain_uuid = '$domain_uuid' and extension = '$extension'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $extension = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $extension;
}

if ($extension !== null and $domain_uuid !== null and $password !== null) {
	$password = Security::decrypt($password, KEY_SECURE);
    $sql = "select extension_uuid,domain_uuid,extension,effective_caller_id_name,effective_caller_id_number,outbound_caller_id_name,outbound_caller_id_number from v_extensions ";
    $sql .= "where domain_uuid = '$domain_uuid' and extension = '$extension' and password = 'password'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $extension = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $extension;
}

echo(json_encode($message));

