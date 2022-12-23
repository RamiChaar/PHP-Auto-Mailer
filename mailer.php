
<?php
require_once 'vendor/autoload.php';

//create mail transport with swift

//change this variable to the email (preferably gmail) that you would like to send from
$emailToUse = "customEmail@email.com";
//change this password to a one-time-password set up with your email
$oneTimeEmailPasswordToUse = "oneTimePassword";

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))->setUsername($emailToUse)->setPassword($oneTimeEmailPasswordToUse);
$mailer = new Swift_Mailer($transport);

//connect to database

//connect to your mailerDB database that you set up with the mailer.mysql file
$serverName = "localhost";
$userName = "databaseUsername";
$password = "databasePassword";

$dbName = "mailerDB";
$connection = mysqli_connect($serverName, $userName, $password, $dbName);
if (mysqli_connect_errno()) {
  echo "Failed to connect";
  exit();
}

//get current time and do query to select all emails that should be sent at this time
date_default_timezone_set('America/Los_Angeles');
$currTime = date('Y/m/d H:i', time());
$sql = "SELECT * FROM messages WHERE sent = 'false' and timestamp < '$currTime'";

$messageRows = $connection->query($sql);

$sentIds = array();

//send message for each query row
while($row = $messageRows->fetch_assoc()){
  $toEmail = $row['email'];
  $message = $row['message'];
  $id = $row['idmessages'];

  array_push($sentIds, $id);

  //check if email is valid before sending
  if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
    $message = (new Swift_Message($currTime))->setFrom(['automailer@mail.com' => 'Auto Mailer'])
    ->setTo([$toEmail => 'Recipient'])
    ->setBody($message);

    $result = $mailer->send($message);
  }

}

//update all sent messages in database
foreach($sentIds as $id){
  $sql = "UPDATE messages SET sent = 'true' WHERE idmessages = $id";
  $connection->query($sql);
}

?>