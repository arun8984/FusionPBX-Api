<?php
include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';
#include 'security.php';
set_time_limit(3600);
$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$baseUrl = 'http://67.227.23.249/';
if ($domain_uuid !== null) {
    $sql = "select d.domain_name, e.extension, e.password ,e.effective_caller_id_name from v_extensions e, v_domains d  where e.domain_uuid = d.domain_uuid and d.domain_uuid= '$domain_uuid' ";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    foreach ($result as $row) {

        $url = $baseUrl . $row['domain_name'] . "/_matrix/client/r0/register?kind=user";
        $data_reg = '{"username":"' . $row['extension'] . '", "password":"' . $row['password'] .
            '", "auth": {"type":"m.login.dummy"}}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_reg);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        curl_close($ch);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $regresult = json_decode($body, true);
        if ($httpcode == 200) {
            echo 'User Created - ' . $row['extension'];
            $data = array('displayname' => $row['effective_caller_id_name']);
            $data_json = json_encode($data);

            $url = $baseUrl . $row['domain_name'] . '/_matrix/client/r0/profile/' .
                urldecode('@' . $row['extension'] . ':' . $row['domain_name']) .
                '/displayname?access_token=' . urldecode($regresult['access_token']);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                    'Content-Length: ' . strlen($data_json)));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

        } elseif ($httpcode == 400) {
            if ($regresult['errcode'] == 'M_USER_IN_USE' or $regresult['errcode'] ==
                'M_EXCLUSIVE') {
                echo "Username Already Exists, Try again. User - " . $row['extension'];
            }
            if ($regresult['errcode'] == 'M_INVALID_USERNAME') {
                echo "Not a valid Username. User - " . $row['extension'];
            }
        } else {
            echo "Some error occured, Try again. User - " . $row['extension'].' Status code '.$regresult['errcode'];
        }

        echo '</br>';
        sleep(1);
    }
}

?>