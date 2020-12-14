<?php
ini_set('display_errors', 'Off');
header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$sip_port = 5060;

$message = array();

$message = ['SipPort' => $sip_port];
echo(json_encode($message));