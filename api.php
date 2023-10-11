<?php
//$data = json_decode($_POST);
//print_r($_POST);
//die;
if (!defined('ABSPATH')) {
    define('ABSPATH', __dir__ . '/');
}

define('TIME_INTERVAL_API', 0);
// https://extendsclass.com/rest-client-online.html
global $db;
require_once __dir__ . '/safemysql/safemysql.class.php';
$db = new SafeMySQL(['user' => 'apitapi', 'pass' => 'AL3JNhMacWVLaOw', 'db' =>
    'apitapi', ]);
require_once __dir__ . '/safemysql/suadmin.php';

if (!empty($_GET['action'])) {
    $action = $_GET['action'];


    switch ($action) {


        case 'send':
            require_once __dir__ . '/safemysql/send.php';
            //  проверка на частоту запроса


            if (Chek_Interval()) {

                /*
                
                "sku",[[ARTICULE]],"apikey",[[APIKEY]],"payment_key",[[PAYMENT_KEY]]
                */
                $sku = $_POST['sku'];
                $apikey = $_POST['apikey'];
                $payment_key = $_POST['payment_key'];

                $user = Chek_User($apikey); // проверяем валидность юзера
                if ($user) { // проверяем валидность юзера

                    $product = Chek_Product_Sku($sku);
                    if ($product) { // проверяем sku

                        $payment = Chek_Product_PaymentKey($product, $payment_key);

                        // print_r($user);
                        if ($payment) { // проверяем payment_key
                            /*
                            $payment =  Array ( [key] => pay1 [paysumm] => 0.01 ) 
                            $product   =  Array ( [product_id] => 388 [user_id] => 90 [sku] => sku-388 [apikey_commission] => 0.3 [payment_type] => 0 [payment] => a:1:{i:0;a:2:{s:3:"key";s:4:"pay1";s:7:"paysumm";s:4:"0.01";}} ) 



                            $user   =  Array ( [user_id] => 89 [apikey] => OLR32W3b9mls7EGivJ [fs_disable_users_status] => 0 [last_login] => ) 
                            */

                            // проверяем баданс пользователя

                            $get_user_balance = get_user_balance($user['user_id']);

                            if ($get_user_balance >= $payment['paysumm']) { // если баланс положительный


                                if ($payment['payment_type'] == 0) { // смотрим тип продукта если обычный
								set_user_transaction_simple($product['product_id'], $user['user_id'],$product['user_id'],$payment['paysumm'],$product['apikey_commission']);
                                    response(200, "ok", null);
                                } else { // смотрим тип продукта если раз в месяц
									response(401, "api nit work", null);
                                }

                            } else {
								
								// проверяем было ли платеж в течении 30 дней  60*60*24*30 == 2592000
								
								$Payment30 = Chek_Product_Payment30($product['product_id'],$product['user_id'],2592000);
								if ($Payment30 == 0)  {  // платежей не было
									set_user_transaction_simple($product['product_id'], $user['user_id'],$product['user_id'],$payment['paysumm'],$product['apikey_commission']);	
								}
								   response(200, "ok", null);
								
							}


                        } else {
                            response(401, "payment_key not valid", null);
                        }
                    } else {

                        response(401, "sku not valid", null);
                    }


                } else {
                    response(401, "apikey not valid", null);
                }
                response(200, "Good", $user);


            } else {
                response(401, "too frequent requests wait - " . TIME_INTERVAL_API . " sec", null);
            }


            break;
        case 'suadmin':
            $data = $_POST['data'];
            $data = json_decode($data);
            $job = $data->job;
            $api_key = $data->api_key;
            $sync_type = $data->sync_type;
            $dataset = $data->dataset;


            if ($api_key == 'Khdlsi03jdjsl') {


                switch ($job) {
                    case 'users':


                        sync_users($dataset, $sync_type);
                        response(200, "users sinc", null);
                        break;

                    case 'product':
                        sync_product($dataset, $sync_type);
                        response(200, "product sinc", null);
                        break;


                    case 'transactions_wp_api':
                        sync_transactions_wp_api($dataset, $sync_type);
                        response(200, "product sinc", null);
                        break;

                    case 'transactions_api_wp':
                        sync_transactions_api_wp($dataset, $sync_type);
                       
                        break;

                    default:
                        response(400, "Invalid job", null);
                        break;
                }

            } else {
                response(400, "Invalid api_key", null);
            }
            //	$job === 'users'

            break;
        default:
            response(400, "Invalid Request", null);
            break;
    }
} else {
    response(400, "Invalid Request", null);
}

function response($status, $status_message, $data)
{
    header("HTTP/1.1 " . $status);
    header("Content-Type:application/json");
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
    die;
}
