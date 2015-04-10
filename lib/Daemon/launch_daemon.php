<?php

set_time_limit(0);
ignore_user_abort(1);

$activate = false;
$running = false;
$time_loop =  1 * 60 * 60; /* second */
$time_loop_min = 60;
$id_loop = 1;

date_default_timezone_set('Europe/Paris');

$state_file = "state.json";

if (file_exists($state_file)) {
  $f = fopen($state_file,"r");
  $json_activation = json_decode(fread($f, filesize($state_file)), true);
  $activate = $json_activation['activate'];
  $running = $json_activation['running'];
  fclose($f);

  if(!$activate || $running)
    exit(1);

  $log_file = "log.txt";
  $f = fopen($log_file, "w");
  $json['count'] = 0;
  fwrite($f,json_encode($json, JSON_NUMERIC_CHECK));
  fclose($f);

  $running = true;
  saveServerState();

  while($activate && $running) {
    isActivate();

    if((!isNight() && isTime()) || $id_loop==1) {
      sendNotif('Message automatique #'.$id_loop.'. '.date("Y-m-d H:i:s").'.  '.getSNCF().'.'.((!$activate)?' Server is stopped.':''));
      log_notif();
      $id_loop++;
    }

    if($time_loop < $time_loop_min)
      $time_loop = $time_loop_min;
    if($time_loop >= 60*60)
      sleep($time_loop-(60*date('i')));
    else
      sleep($time_loop);

    isActivate();
  }

  $running = false;
  saveServerState();
  die("Boolean activate is false.");
}

function isNight() {
  $hour = date("H");
  return (23<=$hour || $hour<=6);
}

function isTime() {
  $hour = date("H");
  return ($hour==7 || $hour==18);
}

function log_notif() {
  global $running, $activate, $log_file;
  $f = fopen($log_file,"r");
  $json = json_decode(fread($f, filesize($log_file)), true);
  fclose($f);
  $json['count'] = $json['count'] + 1;
  $json[$json['count']]['date'] = date("Y-m-d H:i:s");
  $f = fopen($log_file, "w");
  fwrite($f, json_encode($json));
  fclose($f);
}

function isActivate() {
  global $running, $activate, $state_file;
  if (file_exists($state_file)) {
    $f = fopen($state_file,"r");
    $json_activation = json_decode(fread($f, filesize($state_file)), true);
    $activate = $json_activation['activate'];
    fclose($f);
  }
  else {
    $running = false;
    saveServerState();
    die("File 'activate.json' doesn't exist.");
  }
}

function saveServerState() {
  global $running, $activate, $state_file;
  $array['running'] = $running;
  $array['activate'] = $activate;
  $f = fopen($state_file, "w");
  fwrite($f, json_encode($array));
  fclose($f);
}

//generic php function to send GCM push notification
function sendPushNotificationToGCM($registatoin_ids, $message) {
//Google cloud messaging GCM-API url
    $url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
    ); 
    $headers = array(
        'Authorization: key=' . "AIzaSyALmR120lJH_ZN4NZO4_JyU7K_08OJwG2Q",
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);      
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

function sendNotif($pushMessage) {
  try {
    $bdd = new PDO('mysql:host=localhost;dbname=jarvis', 'root', '');
  }
  catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
  }

  //this block is to post message to GCM on-click
  $pushStatus = "";

  $req = $bdd->prepare('SELECT * FROM `user` WHERE `admin` = 1');
  $req->execute();

  while($donnees = $req->fetch()) {
    $gcmRegID  = $donnees['android_id'];

    if (isset($gcmRegID) && isset($pushMessage)) {   
      $gcmRegIds = array($gcmRegID);
      $message = array("m" => $pushMessage);
      $pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
    } 
  }
}


function getSNCF() {
  $url = "http://www.transilien.com/itineraire/ligne/init?codeLigne=D";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  // Set so curl_exec returns the result instead of outputting it.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Get the response and close the channel.
  $response = curl_exec($ch);
  curl_close($ch);

  return 'RER D = ' . explode('</td>', explode('<td class="trafic">', $response)[1])[0];
}

    
?>