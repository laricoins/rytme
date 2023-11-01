<?php
defined('ABSPATH') || die('');
add_action('wp_ajax_validate_data', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $mdl = new RytmeCurl();
    $data = $mdl->rytme_login();
    /*typeList
    languageList
    toneList
    */
    $temp = RytmeGetOptions();
    $temp['token'] = $data->data->token;
    update_option('rytme_options', $temp);
    $typeList = $mdl->rytme_post('typeList');
    $languageList = $mdl->rytme_post('languageList');
    $toneList = $mdl->rytme_post('toneList');
    $tmp = RytmeGetOptions();
    $tmp['typeList'] = $typeList;
    $tmp['languageList'] = $languageList;
    $tmp['toneList'] = $toneList;
    update_option('rytme_options', $tmp);
    $temp['SuccessfulMessage'] = $data->message;
    $temp['savingSuccessful'] = true;
    wp_send_json_success($temp);
    wp_die();
});
add_action('wp_ajax_get_rytme_data', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $temp = RytmeGetOptions();
    $temp['SuccessfulMessage'] = __('Data Load', 'rytme');
    $temp['savingSuccessful'] = false;
    wp_send_json_success($temp);
    wp_die();
});
add_action('wp_ajax_rytme_data_save', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $login = sanitize_text_field($_POST['login']);
    $pwsd = sanitize_text_field($_POST['pwsd']);
    $fp = sanitize_text_field($_POST['fp']);
    $rytme_options = array('login' => $login, 'pwsd' => $pwsd, 'fp' => $fp,);
    update_option('rytme_options', $rytme_options);
    $temp = RytmeGetOptions();
    $temp['SuccessfulMessage'] = __('Data Saved ...', 'rytme');
    $temp['savingSuccessful'] = true;
    wp_send_json_success($temp);
    wp_die();
});
add_action('wp_ajax_rytm_data', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $temp = RytmeGetOptions();
    if (sanitize_text_field($_POST['operation']) == 'languageList') {
        wp_send_json_success($temp['languageList']);
        wp_die();
    }
    wp_send_json_success($temp);
    wp_die();
});
add_action('wp_ajax_rytm_data_get_title', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $mdl = new RytmeCurl();
    $params = [];
    $params["driveIdFolder"] = null;
    $params["driveIdFile"] = "";
    $params["typeId"] = "60583a058c0a4a000c69c96d";
    $rytme_options = get_option('rytme_options');
    $toneId = json_decode($rytme_options['toneList'])->data;
    $languageList = json_decode($rytme_options['languageList']);
    for ($i = 0;$i < count($languageList->data);$i++) {
        $languageList->data[$i]->isDefault = $languageList->data[$i]->_id == sanitize_text_field($_POST['languageId']);
    }
    $languageList = json_encode($languageList);
    $rytme_options['languageList'] = $languageList;
    update_option('rytme_options', $rytme_options);
    shuffle($toneId);
    $toneId = end($toneId)->_id;
    $params["toneId"] = $toneId;
    $params["languageId"] = sanitize_text_field($_POST['languageId']);
    $params["contextInputs"] = ['TARGET_KEYWORDS_LABEL' => sanitize_text_field($_POST['starttext']) ];
    $params["variations"] = 1;
    $params["creativityLevel"] = "default";
    $data = $mdl->rytme_post('generateExecute', $params);
    wp_send_json_success($data);
    wp_die();
});
add_action('wp_ajax_rytm_data_get_content', function () {
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme'));
        wp_die();
    }
    $mdl = new RytmeCurl();
    $params = [];
    $params["driveIdFolder"] = null;
    $params["driveIdFile"] = "";
    $params["typeId"] = "60584cf2c2cdaa000c2a7954";
    $rytme_options = get_option('rytme_options');
    $toneId = json_decode($rytme_options['toneList'])->data;
    shuffle($toneId);
    $toneId = end($toneId)->_id;
    $params["toneId"] = $toneId;
    $params["languageId"] = sanitize_text_field($_POST['languageId']);
    $params["contextInputs"] = ['SECTION_TOPIC_LABEL' => $_POST['title'], 'SECTION_KEYWORDS_LABEL' => sanitize_text_field($_POST['starttext']) ];
    $params["variations"] = 1;
    $params["creativityLevel"] = "default";
    $data = $mdl->rytme_post('generateExecute', $params);
    $dataRes1 = json_decode($data);
    if (!isset($dataRes1->data)) {
        $error = [];
        $error['success'] = true;
        $error['data']['content'] = __('Error generate, change title or Enter starttext text ...  try letter', 'rytme');
        wp_send_json_success(json_encode($error));
        wp_die();
    }
    $src = $dataRes1->data->content;
    $driveId = $dataRes1->data->driveIdFileNew;
    preg_match_all('%(?<=<p>)(.+?)(?=</p>)%sm', $src, $matches);
    $Res1 = $matches[1];
    if (count($Res1) < 3) {
        sleep(1);
        $data = $mdl->rytme_post('generateExecute', $params);
        $dataRes2 = json_decode($data);
        $src = $dataRes2->data->content;
        preg_match_all('%(?<=<p>)(.+?)(?=</p>)%sm', $src, $matches);
        $Res2 = $matches[1];
        $driveId = $dataRes2->data->_id;
        $Res1 = array_merge($Res1, $Res2);
    }
    $params = [];
    $params["driveId"] = $driveId;
    $params["languageId"] = sanitize_text_field($_POST['languageId']);
    $params["toneId"] = $toneId;
    $params["content"] = end($Res1);
    $params["operation"] = 'text-continue-ryting';
    $data = $mdl->rytme_post('documentExecuteOperation', $params);
    $ResAdd = json_decode($data);
    $Res1[count($Res1) - 1] = $Res1[count($Res1) - 1] . ' ' . $ResAdd->data->contentSingle;
    for ($i = 0;$i < count($Res1);$i++) {
        $Res1[$i] = '<p>' . $Res1[$i] . '</p>';
    }
    $dataRes1->data->content = implode('', $Res1);
    $data = json_encode($dataRes1);
    wp_send_json_success($data);
    wp_die();
});
