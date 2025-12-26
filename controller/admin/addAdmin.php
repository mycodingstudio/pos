<?php include_once("admin_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['userName']) || !isset($_POST['userUsername']) || !isset($_POST['userPassword']) || !isset($_POST['modules'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}


$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$userName           = escape($_POST["userName"]);
$userUsername       = escape($_POST["userUsername"]);
$userPassword       = escape($_POST["userPassword"]);
$modules            = $_POST["modules"];


$method = new Admin();
$method -> addAdmin($adminUsername, $adminAccesstoken, $userName, $userUsername, $userPassword, $modules);

?>