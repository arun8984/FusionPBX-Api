<?php
class FCM
{
    function __construct()
    {
    }
    /**
     ** Sending Push Notification
     **/
    public function send_notification($registatoin_ids, $notification, $device_type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if ($device_type == "android") {
            $fields = array(
                'to' => $registatoin_ids,
                'data' => $notification
            );
        } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
        }
        
        $headers = array(
            'Authorization:key=AAAAZV7Fq3Y:APA91bHjIMkbBo12jtGvbNsuBkEccb9E3RhWBWmXJAbyT4opmc-2cSxsDx0HK42cyRgOCIN6yY7GrDDSYnL2spI4jfjwu23ANKsekHzv_GFIApn7vOepIH63AszvXvHZDRl453nH2W5K',
            'Content-Type:application/json'
        );
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
?>