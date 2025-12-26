<?php include_once("admin_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['code']) || !isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['userName']) || !isset($_POST['userUsername']) || !isset($_POST['userPassword']) || !isset($_POST['modules']) || !isset($_POST['accountStatus'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$code               = escape($_POST["code"]);
$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$userName           = escape($_POST["userName"]);
$userUsername       = escape($_POST["userUsername"]);
$userPassword       = escape($_POST["userPassword"]);
$modules            = $_POST["modules"];
$accountStatus      = escape($_POST["accountStatus"]);


$method = new Admin();
$method -> updateAdmin($code, $adminUsername, $adminAccesstoken, $userName, $userUsername, $userPassword, $modules, $accountStatus);

?>