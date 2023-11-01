<?php
defined('ABSPATH') || die('');

class RytmeCurl
{

    public $aux_url = 'https://api.rytr.me/';


    public function rytme_login()
    {
        $rytme_options = get_option('rytme_options');
        $email = $rytme_options['login'];
        $password = $rytme_options['pwsd'];
        $fp = $rytme_options['fp'];
		
        //	{"operation":"userAuthLogin","params":{"email":"yuval17@012.net.il","otp":"","password":"er45tz39a4","name":"","fp":"3900f85a25ae91deb85c66c052ff259f"}}

        $aux = ['operation' => 'userAuthLogin', 'params' => ['email' => $email, 'otp' =>
            "", 'password' => $password, 'name' => "", 'fp' => $fp, ]];
        $body = json_encode($aux);

        $headers = ["sslverify" => false, 'Content-Type' =>
            'application/json; charset=utf-8'];

        $args = array('headers' => $headers, 'body' => $body);
        $response = wp_remote_post($this->aux_url, $args);
        if (is_wp_error($response)) {
            $request = $response->get_error_message();

        } else {
            $request = json_decode(wp_remote_retrieve_body($response));
            if ($request->success == 1) {
                $token = $request->data->token;
                $rytme_options = get_option('rytme_options');
                $rytme_options['token'] = $token;
                update_option('rytme_options', $rytme_options);
            }
        }
        return $request;
    }
/*
languageList  -- список языков

*/
    public function rytme_post($operation,$params='')
    {
        $rytme_options = get_option('rytme_options');
        $token = $rytme_options['token'];
        $headers = ["sslverify" => false, 'Content-Type' =>
            'application/json; charset=utf-8', 'Authorization' => 'Bearer ' . $token, 'user-agent'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/114.0','Authentication' => 'Bearer ' . $token, ];
        $body = ['operation' => $operation];
		if ($params){
		$body['params']=$params;	
		}
		
// {"operation":"generateExecute","params":{"driveIdFolder":null,"driveIdFile":"","typeId":"60583a058c0a4a000c69c96d","toneId":"6064c6679bde74000cea994c","languageId":"607adc2f6f8fe5000c1e637a","contextInputs":{"TARGET_KEYWORDS_LABEL":"ахмат сила"},"variations":3,"creativityLevel":"default"}}	

	
        $body = json_encode($body);
//print_r( $body); die;
        $args = array('headers' => $headers, 'body' => $body);
        $response = wp_remote_post($this->aux_url, $args);
//print_r( $response);

        return wp_remote_retrieve_body($response);
    }
}
