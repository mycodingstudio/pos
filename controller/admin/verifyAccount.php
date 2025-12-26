<?php include_once("admin_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['loginUsername']) || !isset($_POST['loginPassword'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$loginUsername    = escape($_POST["loginUsername"]);
$loginPassword    = escape($_POST["loginPassword"]);

$method = new Admin();
$method -> verifyAccount($loginUsername, $loginPassword);

?>