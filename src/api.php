<?php

defined('ABSPATH') || die('');

add_action('wp_ajax_validate_data', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }
    $mdl = new Rcurl(); 
	$data = $mdl->login(); 
	
	/*typeList
	languageList
	toneList
	*/
	 $temp = RytMeGetOptions();
     $temp['token'] = $data->data->token;
	 update_option('rytme_options', $temp); 
	 
	  $typeList =	$mdl->post('typeList')	;
	  $languageList =	$mdl->post('languageList')	;
	  $toneList =	$mdl->post('toneList')	;
	  $tmp = RytMeGetOptions();
	  $tmp['typeList']=$typeList;
	  $tmp['languageList']=$languageList;
	  $tmp['toneList']=$toneList;
		 update_option('rytme_options', $tmp); 
 
	 
	 
	$temp['SuccessfulMessage'] = $data->message; $temp['savingSuccessful'] = true;
	wp_send_json_success($temp); wp_die(); }
);


add_action('wp_ajax_get_rytme_data', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }
    $temp = RytMeGetOptions(); $temp['SuccessfulMessage'] = __('Data Load', 'rytme');
        $temp['savingSuccessful'] = false; wp_send_json_success($temp); wp_die(); }
);


add_action('wp_ajax_rytme_data_save', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }

    $login = sanitize_text_field($_POST['login']); $pwsd = sanitize_text_field($_POST['pwsd']);
        $fp = sanitize_text_field($_POST['fp']); $rytme_options = array(
        'login' => $login,
        'pwsd' => $pwsd,
        'fp' => $fp,
        ); update_option('rytme_options', $rytme_options); $temp = RytMeGetOptions(); $temp['SuccessfulMessage'] =
        __('Data Saved ...', 'rytme'); $temp['savingSuccessful'] = true;
        wp_send_json_success($temp); wp_die(); }
);


add_action('wp_ajax_rytm_data', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }

 //$mdl = new Rcurl(); 
 //$temp =	$mdl->post($_POST['operation'])	;
 
  $temp = RytMeGetOptions();
  if ($_POST['operation'] == 'languageList'){
	  wp_send_json_success($temp['languageList']); wp_die();  
  }
  
// print_r($temp );
// die;
  wp_send_json_success($temp); wp_die(); 
		}
);



add_action('wp_ajax_rytm_data_get_title', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }

	$mdl = new Rcurl(); 
	$params = [];
	$params["driveIdFolder"]= null;
	$params["driveIdFile"]= "";
	$params["typeId"]= "60583a058c0a4a000c69c96d";
	
	$rytme_options = get_option('rytme_options');
	$toneId= json_decode($rytme_options['toneList'])->data;

	$languageList = json_decode($rytme_options['languageList']);

	for ($i=0; $i<count($languageList->data);$i++){

	$languageList->data[$i]->isDefault = $languageList->data[$i]->_id == $_POST['languageId'];
	}

	$languageList = json_encode($languageList );
	$rytme_options['languageList'] = $languageList;
	update_option('rytme_options', $rytme_options);


	shuffle($toneId);
	$toneId = end($toneId)->_id;
	$params["toneId"]= $toneId;
	$params["languageId"]= $_POST['languageId'];
	$params["contextInputs"]= ['TARGET_KEYWORDS_LABEL'=>$_POST['starttext']];
	$params["variations"]= 1;
	$params["creativityLevel"]= "default";

	$data =$mdl->post('generateExecute',$params);
	
  wp_send_json_success($data); wp_die(); 
		}
);






add_action('wp_ajax_rytm_data_get_content', function ()
{
    if (!check_ajax_referer('api-nonce', 'security', false)) {
        wp_send_json_error(__('Invalid security token sent.', 'rytme')); wp_die(); }

	$mdl = new Rcurl(); 
	$params = [];
	$params["driveIdFolder"]= null;
	$params["driveIdFile"]= "";
	$params["typeId"]= "60584cf2c2cdaa000c2a7954";
	
	$rytme_options = get_option('rytme_options');
	$toneId= json_decode($rytme_options['toneList'])->data;

/*

{"operation":"generateExecute","params":{"driveIdFolder":null,"driveIdFile":"64959768a9c4dd6da0f2c5bc","typeId":"60584cf2c2cdaa000c2a7954","toneId":"60572a639bdd4272b8fe358b","languageId":"607adc2f6f8fe5000c1e637a","contextInputs":{"SECTION_TOPIC_LABEL":"Расслабьтесь на Побережье Черного моря: Отдых в Севастополе","SECTION_KEYWORDS_LABEL":"отдых в севсатополе"},"variations":1,"creativityLevel":"default"}}


{"success":true,"message":"Generated successfully.","code":"default","data":{"_id":"64959944c8f2e9cf0fd09b1d","driveIdFileNew":"","content":"<p>Для тех, кто ищет идеальное место для отдыха на побережье, Севастополь является одним из лучших вариантов. Этот город находится на полуострове Крым и предлагает своим посетителям широкий выбор развлечений и отдыха.</p><p>Отдых в Севастополе подходит как для семейного отпуска, так и для романтического уикенда. Город известен своими пляжами с прекрасными видами на Черное море, а также достопримечательностями, которые можно посетить в свободное время.</p><p>Севастополь предлагает различные виды развлечений: экскурсии по городу, дайвинг, парусный спорт или просто расслабление на пляже. В этом городе есть все необходимое для того чтобы провести незабываемый отпуск.</p>","contentSingle":"Для тех, кто ищет идеальное место для отдыха на побережье, Севастополь является одним из лучших вариантов. Этот город находится на полуострове Крым и предлагает своим посетителям широкий выбор развлечений и отдыха.Отдых в Севастополе подходит как для семейного отпуска, так и для романтического уикенда. Город известен своими пляжами с прекрасными видами на Черное море, а также достопримечательностями, которые можно посетить в свободное время.Севастополь предлагает различные виды развлечений: экскурсии по городу, дайвинг, парусный спорт или просто расслабление на пляже. В этом городе есть все необходимое для того чтобы провести незабываемый отпуск."}}


*/
	shuffle($toneId);
	$toneId = end($toneId)->_id;
	$params["toneId"]= $toneId;
	$params["languageId"]= $_POST['languageId'];
	$params["contextInputs"]= ['SECTION_TOPIC_LABEL'=>$_POST['title'], 'SECTION_KEYWORDS_LABEL'=>$_POST['starttext']];
	$params["variations"]= 1;
	$params["creativityLevel"]= "default";

	$data =$mdl->post('generateExecute',$params);

	$dataRes1= json_decode($data);
	$src = $dataRes1->data->content;
	$driveId = $dataRes1->data->driveIdFileNew;
	preg_match_all('%(?<=<p>)(.+?)(?=</p>)%sm', $src, $matches);

	$Res1=$matches[1];
	if (count($Res1)<3){
		sleep(1);
		$data =$mdl->post('generateExecute',$params);
		$dataRes2= json_decode($data);
		$src = $dataRes2->data->content;
		preg_match_all('%(?<=<p>)(.+?)(?=</p>)%sm', $src, $matches);
		$Res2=$matches[1];
		$driveId = $dataRes2->data->_id;
		$Res1 = array_merge($Res1, $Res2);
	}
	
	

	
	$params = [];
	$params["driveId"]= $driveId;
	$params["languageId"]= $_POST['languageId'];
	$params["toneId"]= $toneId;
	$params["content"]= end($Res1);

	$params["operation"]= 'text-continue-ryting';


//	print_r($data);
//	print_r($params);
	$data =$mdl->post('documentExecuteOperation',$params);
	$ResAdd= json_decode($data);
	
	//print_r($Res1);
	
	$Res1[count($Res1)-1] = $Res1[count($Res1)-1].' '.$ResAdd->data->contentSingle;
		//print_r($Res1);

	//die;
	
	
		for($i=0;$i<count($Res1);$i++){
		$Res1[$i] = '<p>'.$Res1[$i].'</p>';
	}
	$dataRes1->data->content = implode('',$Res1);
	$data = json_encode($dataRes1);
	
	//print_r($data);

	//die;


	
	
  wp_send_json_success($data); wp_die(); 
		}
);


/*

   
{"operation":"documentExecuteOperation","params":{"driveId":"64994c9e743611b9fa084a69","languageId":"607adc2f6f8fe5000c1e637a","toneId":"60572a639bdd4272b8fe358b","content":"Приобретение оборудования для дистилляции самогонного спирта - серьезное дело, поэтому следует тщательно изучить все возможные варианты и выбрать лучшее сочетание цены и качества.","operation":"text-continue-ryting"}}

{"success":true,"message":"Generated successfully.","code":"default","data":{"_id":"64994d2718d8198a02276265","driveIdFileNew":"","content":"<p>Здравствуйте!</p><p>Спасибо за обращение к нам за помощью в выборе оборудования для дистилляции самогонного спирта. Мы понимаем, что это серьезное дело и поэтому готовы помочь Вам сделать правильный выбор.</p><p>Важно учитывать все возможные варианты и тщательно изучить каждый из них. Наша компания предлагает широкий ассортимент качественного оборудования для дистилляции самогонного спирта по доступным ценам.</p><p>Мы готовы предоставить Вам профессиональную консультацию по подбору оборудования, которое будет соответствовать всем Вашим требованиям и пожеланиям. Наши специалисты ответят на все Ваши вопросы и помогут определиться с выбором.</p><p>Благодарим за доверие к нашей компании!</p>","contentSingle":"Здравствуйте!Спасибо за обращение к нам за помощью в выборе оборудования для дистилляции самогонного спирта. Мы понимаем, что это серьезное дело и поэтому готовы помочь Вам сделать правильный выбор.Важно учитывать все возможные варианты и тщательно изучить каждый из них. Наша компания предлагает широкий ассортимент качественного оборудования для дистилляции самогонного спирта по доступным ценам.Мы готовы предоставить Вам профессиональную консультацию по подбору оборудования, которое будет соответствовать всем Вашим требованиям и пожеланиям. Наши специалисты ответят на все Ваши вопросы и помогут определиться с выбором.Благодарим за доверие к нашей компании!"}}

*/


