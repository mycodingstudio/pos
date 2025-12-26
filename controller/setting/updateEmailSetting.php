<?php include_once("setting_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['email'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$email              = escape($_POST["email"]);

$method = new Setting();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> updateEmailSetting($adminUsername, $email);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>