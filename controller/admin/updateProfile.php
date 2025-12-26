<?php include_once("admin_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['currentPassword']) || !isset($_POST['newPassword'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}


$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$currentPassword    = escape($_POST["currentPassword"]);
$newPassword        = escape($_POST["newPassword"]);

$name           = escape($_POST["name"]);
$phone          = escape($_POST["phone"]);
$email          = escape($_POST["email"]);

$method = new Admin();
$method -> updateProfile($adminUsername, $adminAccesstoken, $currentPassword, $newPassword, $name, $phone, $email);

?>