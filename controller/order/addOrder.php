<?php include_once("order_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['clientEmail']) || !isset($_POST['clientName']) || !isset($_POST['clientPhone']) || !isset($_POST['resellerName']) || !isset($_POST['fromPlatform']) || !isset($_POST['trackingLink']) || !isset($_POST['billingFullAddress']) || !isset($_POST['shippingFullAddress']) || !isset($_POST['getOrderDetail']) || !isset($_POST['orderRemark']) || !isset($_POST['totalAmount']) || !isset($_POST['orderStatus'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

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
$seCode                 = escape($_POST["seCode"]);
$orderStatus            = escape($_POST["orderStatus"]);
$byCash                 = escape($_POST["byCash"]);



$paymentProofPath = null;
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

// Ensure the uploads directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check for and handle file upload

if(!$byCash){
    if (isset($_FILES['paymentProof']) && $_FILES['paymentProof']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath  = $_FILES['paymentProof']['tmp_name'];
        $fileName     = $_FILES['paymentProof']['name'];
        $fileExtension= strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName  = uniqid('payment_proof_') . '.' . $fileExtension;
        $destPath     = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $paymentProofPath = 'https://se.multihome.tech/uploads/' . $newFileName;
        } else {
            $outputArray = array('status' => 400, 'message' => "Failed to move uploaded file.");
            outputJson($outputArray);
            return;
        }
    } else if ($orderStatus === 'shipped' || $orderStatus === 'completed' || $orderStatus === 'order_confirmed') {
        $outputArray = array('status' => 400, 'message' => "Payment proof is required for the selected order status.");
        outputJson($outputArray);
        return;
    }

}

$method = new Order();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> addOrder($adminUsername, $clientEmail, $clientName, $clientPhone, $resellerName, $fromPlatform, $trackingLink, $billingFullAddress, $shippingFullAddress, $getOrderDetail, $totalAmount, $orderRemark, $seCode, $orderStatus, $paymentProofPath, $byCash);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>