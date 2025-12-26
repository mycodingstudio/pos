<?php include_once("order_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['code']) || !isset($_POST['orderId']) || !isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['clientEmail']) || !isset($_POST['clientName']) || !isset($_POST['clientPhone']) || !isset($_POST['resellerName']) || !isset($_POST['fromPlatform']) || !isset($_POST['trackingLink']) || !isset($_POST['billingFullAddress']) || !isset($_POST['shippingFullAddress']) || !isset($_POST['getOrderDetail']) || !isset($_POST['orderRemark']) || !isset($_POST['totalAmount']) || !isset($_POST['orderStatus']) ) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$code                   = escape($_POST["code"]);
$orderId                = escape($_POST["orderId"]);
$adminUsername          = escape($_POST["adminUsername"]);
$adminAccesstoken       = escape($_POST["adminAccesstoken"]);
$clientEmail            = escape($_POST["clientEmail"]);
$clientName             = escape($_POST["clientName"]);
$clientPhone            = escape($_POST["clientPhone"]);
$resellerName           = escape($_POST["resellerName"]);
$fromPlatform           = escape($_POST["fromPlatform"]);
$trackingLink           = escape($_POST["trackingLink"]);
$billingFullAddress     = escape($_POST["billingFullAddress"]);
$shippingFullAddress    = escape($_POST["shippingFullAddress"]);
$getOrderDetail         = $_POST["getOrderDetail"];
$orderRemark            = escape($_POST["orderRemark"]);
$totalAmount            = escape($_POST["totalAmount"]);
$orderStatus            = escape($_POST["orderStatus"]);
$hasNewFile             = escape($_POST["hasNewFile"]);



$paymentProofPath = null;
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if($hasNewFile == "true"){
    if (isset($_FILES['paymentProof']) && $_FILES['paymentProof']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['paymentProof']['tmp_name'];
        $fileName      = $_FILES['paymentProof']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName   = uniqid('payment_proof_') . '.' . $fileExtension;
        $destPath      = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $paymentProofPath = 'https://se.multihome.tech/uploads/' . $newFileName;
        } else {
            $outputArray = array('status' => 400, 'message' => "Failed to move uploaded file.");
            outputJson($outputArray);
            return;
        }
    }
}

$method = new Order();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> updateOrder($code, $orderId, $adminUsername, $clientEmail, $clientName, $clientPhone, $resellerName, $fromPlatform, $trackingLink, $billingFullAddress, $shippingFullAddress, $getOrderDetail, $totalAmount, $orderRemark, $orderStatus, $paymentProofPath, $hasNewFile);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>