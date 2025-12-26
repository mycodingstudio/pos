<?php include_once("material_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['materialType'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$materialType       = escape($_POST["materialType"]);

$method = new Material();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> getAllMaterial($materialType);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>