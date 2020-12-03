<?php
set_time_limit(300);
include '/etc/fusionpbx/config.php';
include_once 'fcm.php';

$extension = $argv[1];
$domain = $argv[2];
//$extension = '216';
//$domain = 'voice.jpnetsols.com';
if ($extension != '' && $domain != '') {
    try {
        if (isset($db_secure)) {
            $dbissecure = $db_secure;
        } else {
            $dbissecure = false;
        }
        if (strlen($db_host) > 0) {
            if (strlen($db_port) == 0) {
                $db_port = "5432";
            }
            if ($dbissecure == true) {
                $db = new PDO("pgsql:host=$db_host port=$db_port dbname=$db_name user=$db_username password=$db_password sslmode=verify-ca sslrootcert=$db_cert_authority");
            } else {
                $db = new PDO("pgsql:host=$db_host port=$db_port dbname=$db_name user=$db_username password=$db_password");
            }
        } else {
            $db = new PDO("pgsql:dbname=$db_name user=$db_username password=$db_password");
        }
    }
    catch (PDOException $error) {
        print "error: " . $error->getMessage() . "<br/>";
        die();
    }

    $sql = "select * from devices where extension = '$extension' and domain_uuid = '$domain' ";
	    $prep_statement = $db->prepare($sql);
    $prep_statement->execute();
    $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    if (sizeof($result) > 0) {
        $regId = $result[0]['pushtoken'];
        $os = $result[0]['os'];

        $notification = array();
        $arrNotification = array();
        $arrData = array();
        $arrNotification["MsgType"] = "INCOMINALERT";
        $arrNotification["type"] = 1;

        $fcm = new FCM();
        $result = $fcm->send_notification($regId, $arrNotification, $os);
        $json = json_decode($result, true);
        if ($json['success'] == 1) {
            $sql = "INSERT INTO pushmsg (msgid, ack)VALUES(:msgid, :ack)";
            $msgid = urldecode($json['results'][0]['message_id']);
            $prep_statement = $db->prepare($sql);
            $prep_statement->execute(['msgid' => $msgid, 'ack' => 0]);
            $starttime = strtotime("now");
            CheckAck : $sql = "select * from pushmsg where msgid = '$msgid'";
print "CheckACK: ".strtotime("now") . "<br/>";
            $prep_statement = $db->prepare($sql);
            $prep_statement->execute();
            $result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
            if ($result[0]['ack'] == 1) {
                sleep(1);
                file_put_contents('php://stdout', print_r('ACK RECEIVED', TRUE));
                die('Ack Received');
            } else {
                if ((strtotime("now") - $starttime) < 60) {
				                    sleep(1);
                    unset($result);
                    unset($prep_statement);
                    file_put_contents('php://stdout', print_r('ACK NOT RECEIVED', TRUE));
                    goto CheckAck;
                } else {
                    die('Time Out');
                }
            }
        } else {
            die('Push Failed');
        }
    }
}


?>