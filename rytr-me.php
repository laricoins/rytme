<?php
/**
 * Plugin Name: Ryt ME
 * Plugin URI: https://github.com/laricoins/rytme
 * Description: Ryt ME plugin
 * Version: 0.22
 * Text Domain: rytme
 * Domain Path: /languages
 * Author: ddnitecry
 * License: GPL2
 */

defined('ABSPATH') || die('');



define('RYTME_VERSION', '1.9.23');
define('RYTME_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RYTME_PLUGIN_PATH', plugin_dir_path(__FILE__));
include_once(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'configuration.php');
include_once(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'base-class.php');
include_once(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'api.php');


//$mdl = new Rcurl();
//$mdl->login();
//$ddd  = $mdl->post('languageList');


//$mdl1 = new Rcurl();
//$ddd =  $mdl->get('https://app.rytr.me/account');
//$ddd =  $mdl->cookie_path;
//print_r($ddd);
//die;
function RytMeGetOptions()
{
    $defaults = array(
        'login' => '',
        'pwsd' => '',
        
    );
    $options = get_option('rytme_options');
    if (!empty($options) && is_array($options)) {
        $options = array_merge($defaults, $options);
    } else {
        $options = $defaults;
    }
    return $options;
}



