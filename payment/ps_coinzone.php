<?php
require './auth.php';
require $xcart_dir.'/modules/Coinzone/CoinzoneLib.php';

$module_params = func_get_pm_params('ps_coinzone.php');
$clientCode = $module_params['param01'];
$apiKey = $module_params['param02'];

if (!isset($_POST['paymentid'])) { // COINZONE IPN

    $headers = getallheaders();
    $nHeaders = array();
    foreach ($headers as $key => $value) {
        $nHeaders[strtolower($key)] = $value;
    }

    $schema = isset($_SERVER['HTTPS']) ? "https://" : "http://";
    $currentUrl = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $content = file_get_contents("php://input");
    $input = json_decode($content, true);

    /** check signature */
    $stringToSign = $content . $currentUrl . $nHeaders['timestamp'];
    $signature = hash_hmac('sha256', $stringToSign, $apiKey);
    if ($signature !== $headers['signature']) {
        header("HTTP/1.0 400 Bad Request");
        exit("Invalid callback");
    }

    // fetch session
    $skey = $orderids = $input['merchantReference'];
    $bill_output['sessid'] = func_query_first_cell("SELECT sessid FROM $sql_tbl[cc_pp3_data] WHERE ref='".$orderids."'");

    // APC system responder
    foreach ($input as $k => $v) {
        $advinfo[] = "$k: $v";
    }

    // update order statusx
    $successArray = array('PAID', 'COMPLETE');
    if (in_array($input['status'], $successArray)) {

        $bill_output['sessid'] = func_query_first_cell("SELECT sessid FROM $sql_tbl[cc_pp3_data] WHERE ref='".$orderids."'");

        $bill_output['code'] = 1;
        $bill_output['billmsg'] = 'Order paid for';
        require($xcart_dir.'/payment/payment_ccend.php');

    } else {
        header("HTTP/1.0 400 Bad Request");
        exit("Invalid status");
    }

}
else { // POST from customer placing the order

    if (!defined('XCART_START')) { header("Location: ../"); die("Access denied"); }

    // associate order id with session
    $_orderids = join("-",$secure_oid);
    if (!$duplicate)
        db_query("REPLACE INTO $sql_tbl[cc_pp3_data] (ref,sessid,trstat) VALUES ('".$_orderids."','".$XCARTSESSID."','GO|".implode('|',$secure_oid)."')");

    $coinzone = new CoinzoneLib($clientCode, $apiKey);

    /* create payload array */
    $payload = array(
        'amount' => $cart['total_cost'],
        'currency' => $module_params['param03'],
        'merchantReference' => $_orderids,
        'email' => $userinfo['email'],
        'redirectUrl' => $current_location.'/order.php?orderid='.$_orderids,
        'notificationUrl' => $current_location.'/payment/ps_coinzone.php',
    );
    var_dump($cart); die();
    $response = $coinzone->callApi('transaction', $payload);

    if ($response->status->code === 201) {
        // headers already sent by xcart, so use JS
        print "<script> window.location = '{$response->response->url}'; </script>";
        exit;
    }
}
