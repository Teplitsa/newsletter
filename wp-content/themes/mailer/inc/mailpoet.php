<?php
/**
 * MailPoet customization
 **/

 
/** shortcode for undo unsubscribe link */
add_shortcode('tst_undo_unsubscribe_link', 'tst_undo_unsubscribe_link_screen');

function tst_undo_unsubscribe_link_screen(){
	if(class_exists('WYSIJA') && !empty($_REQUEST['wysija-key'])){
	 
		$undo_paramsurl = array(
			'wysija-page'=>1,
			'controller'=>'confirm',
			'action'=>'undounsubscribe',
			'wysija-key'=>$_REQUEST['wysija-key']
		);
		 
		$model_config = WYSIJA::get('config','model');
		$link_undo_unsubscribe = WYSIJA::get_permalink($model_config->getValue('confirmation_page'),$undo_paramsurl);
		 
		$undo_unsubscribe = str_replace(
		array('[link]','[/link]'),
		array('<a href="'.$link_undo_unsubscribe.'">','</a>'),
		'[link]Подписаться снова[/link]');
		 
		return $undo_unsubscribe;
	}
	
	return '';
}

/** subscibtion title for pages - doesn't work properly ) **/
add_shortcode('tst_subscribe_title', 'tst_subscribe_title_screen');

function tst_subscribe_title_screen(){
	if(class_exists('WYSIJA') && !empty($_REQUEST['wysija-key'])){
	 
		$undo_paramsurl = array(
			'wysija-page'=>1,
			'controller'=>'confirm',
			'action'=>'subscribe',
			'wysija-key'=>$_REQUEST['wysija-key']
		);
		 
		$model_config = WYSIJA::get('config','model');
		$subs_title = sprintf($model_config->getValue('subscribed_title'), 'demo');
		 
			 
		return $subs_title;
	}
	
	return '';
}


/* fix for browser version paddign */
add_filter('wysija_preview', 'tst_wysija_preview');
function tst_wysija_preview($email){
	
	if(isset($_REQUEST['wysija-page']) && (int)$_REQUEST['wysija-page'] == 1){
		//var_dump($email);
		$add = "<style>#wysija_wrapper { margin-top: 50px; }</style>";
		$email = str_replace('<span style', $add.'<span style', $email);
	}
	
	return $email;
}


/* be sure that our teplitsa theme is always default */
add_action('init', 'tst_wysija_set_default_theme');
function tst_wysija_set_default_theme(){
	
	if(!class_exists('WYSIJA'))
		return;
	
	$model_config = WYSIJA::get('config', 'model');
	$default_theme = $model_config->getValue('newsletter_default_theme'); 
	
	if($default_theme != 'teplitsa')
		$model_config->save(array('newsletter_default_theme' => 'teplitsa'));
}


/* subscribtion form code customisation */
add_shortcode('tst_sbs_form', 'tst_sbs_form_screen');
function tst_sbs_form_screen($atts) {
	
	extract(shortcode_atts(array(				
		'id'  => 0
	), $atts));
	
	
	if($id <= 0)
		return '';
	
	$out = "<div class='tst-sbs-form'>";
	$out .= tst_wysija_subscribtion_form($id);
	$out .= "</div>";
	
	return $out;
}


function tst_wysija_subscribtion_form($form_id){
	
	if(!class_exists('WYSIJA_NL_Widget'))
		return '';
	
	$widgetNL = new WYSIJA_NL_Widget(true);
	$form_html = $widgetNL->widget(array('form' => $form_id, 'form_type' => 'php'));
	
	//markup filters
	preg_match_all('/<p class=\"wysija-checkbox-paragraph\">(.*?)<\/p>/s',$form_html, $m);
	if(isset($m[1]) && !empty($m[1])){
		foreach($m[1] as $c_box){
			
			$label = '';
			$label = strip_tags($c_box);
			if(!empty($label)){
				$c_box_new = str_replace($label, "<span>{$label}</span>", $c_box);
				$form_html = str_replace($c_box, $c_box_new, $form_html);
			}
		}
	}
	
	// filter PD agreement
	preg_match_all('/(<p class=\"wysija-paragraph\">.*?<\/p>)/s',$form_html, $m);
	if(isset($m[1]) && !empty($m[1])){
	    foreach($m[1] as $c_box){
	        if(preg_match('/.*?pd-agreement.*?/', $c_box)) {
	            $c_box_new = str_replace("wysija-paragraph", "wysija-paragraph tst-agree-pd-paragraph", $c_box);
	            $form_html = str_replace($c_box, $c_box_new, $form_html);
	        }
	    }
	}
	
	preg_match_all('/(<label class=\"wysija-checkbox-label\">.*?pd-agreement.*?<\/label>)/s',$form_html, $m);
	if(isset($m[1]) && !empty($m[1])){
	    foreach($m[1] as $c_box){
	        	
	        $label = '';
	        $label = strip_tags($c_box);
	        if(!empty($label)){
	            
	            $c_box_new = $c_box;
	            
	            $c_box_new = str_replace("wysija-checkbox-label", "wysija-checkbox-label tst-agree-pd", $c_box_new);
	            $c_box_new = str_replace($label, "<span>{$label}</span>", $c_box_new);
	            $c_box_new = htmlspecialchars_decode($c_box_new);
	            
	            #echo $c_box_new;
	             
	            $form_html = str_replace($c_box, $c_box_new, $form_html);
	        }
	    }
	}
	
	return $form_html;
}

function tst_mailpoet_shortcodes_custom_filter( $tag_value , $user_id ) {
	
    if( in_array($tag_value, array('tst_footer', 'tst_header') ) ) {
		ob_start();
		include(get_template_directory() . '/email_' . $tag_value . '.php');
        $replacement = ob_get_clean();
    }
	elseif(preg_match("/tst_person_/", $tag_value)) {
		$joined_data = str_replace("tst_person_", "", $tag_value);
		$data = explode("_", $joined_data);
		
		$name = $data[0];
		$position = $data[1];
		if(!empty($data[2])) {
			$pic = wp_get_attachment_image_src((int)$data[2]);
			$pic = $pic[0];
		}
		else {
			$pic = null;
		}
		
		if($name) {
			ob_start();
?>
		<p style="border-bottom: 1px solid #dfe3e6; padding-top: 15px; padding-bottom: 25px;">
		<?php if($pic):?>
			<img src="<?php echo $pic?>" style="max-width: 32px; max-height: 32px; margin-right: 10px; border-radius: 50%; float:left;" />
		<?php endif?>
			<span style="float: left; display:block; font-size: 14px; line-height: 16px; text-transform: uppercase; font-weight: bold;">
				<span style="color: #7c8284; padding-top: 2px; display:block;"><?php echo $name?></span>
				<span style="color: #bbc1c5; display:block; padding-top: 1px;"><?php echo $position?></span>
			</span>
			<br style="float:none; clear:both;"/>
		</p>
<?php
			$replacement = ob_get_clean();
		}
		else {
			$replacement = "";
		}
	}
	elseif(preg_match("/tst_button_/", $tag_value)) {
		$joined_data = str_replace("tst_button_", "", $tag_value);
		$data = explode("_", $joined_data);
		
		$caption = $data[0];
		$url = 'https://'.$data[1];
		
		if($caption && $url) {
			ob_start();
?>
		<p style="text-align: center; margin-top: 30px; margin-bottom: -15px; padding-bottom: 22px;">
			<a href="<?php echo $url?>" class="tps-button" style="font-size: 14px; line-height: 16px; text-transform: uppercase; color: #f2f2f2; background-color: #219665; display: inline-block; text-decoration: none; height: 32px; padding: 16px 28px 0px 28px;border-radius: 24px; letter-spacing: 0.4px;"><?php echo $caption?></a>
		</p>
<?php
			$replacement = ob_get_clean();
		}
		else {
			$replacement = "";
		}
	}	
	
    return $replacement;
}
add_filter('wysija_shortcodes', 'tst_mailpoet_shortcodes_custom_filter', 10, 2);


function newsletter_mailpoet_itv_custom_content($shortcode, $newsletter, $subscriber, $queue, $newsletter_body, $arguments)
{
    if ($shortcode !== '[custom:itv-custom-content]') return $shortcode; 
    
    $subscriptionUrlFactory = \MailPoet\Subscription\SubscriptionUrlFactory::getInstance();
    $subscriber_first_name = $subscriber->getFirstName();
    $link_subscription_manage_url = $subscriptionUrlFactory->getManageUrl($subscriber);
    
    $html = <<<EOT
    
<div class="body-inner" style="margin: 0; padding: 0; border: 0; font-size: 100%; text-align: center; background-color: #fbf8f4; padding-bottom: 59px; vertical-align: inherit;">
<div class="header" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; text-align: center; background-color: #fbf8f4;"><img class="itv-logo" src="https://itv.te-st.ru/wp-content/itv/tstsite/assets_email/img/pic-logo-itv.png" style="padding: 0; font-size: 100%; border: 0; -ms-interpolation-mode: bicubic; vertical-align: bottom; max-width: 100%; width: 132px; height: 38px; display: inline-block; margin: 59px auto 0px auto;" /></div>
<div class="content" style="margin: 0; font-size: 100%; vertical-align: baseline; border: 2px solid #E6DFD1; background-color: #fff; width: 600px; min-height: 300px; margin-left: auto; margin-right: auto; margin-top: 41px; box-sizing: border-box; border-radius: 8px; padding: 28px 80px 40px 80px; color: #575552; text-align: center;">
<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; margin-top: 16px; text-align: center;"><img src="https://itv.te-st.ru/wp-content/itv/tstsite/assets_email/img/mail-icon-newInterestingTasks.png" alt="" style="margin: 0; padding: 0; border: 0; -ms-interpolation-mode: bicubic; vertical-align: bottom; max-width: 100%; height: auto; font-size: 10px; line-height: inherit; color: #666; font-family: inherit;" /></p>

<!-- END OF HEADER -->



<h2 style="margin: 0; padding: 0; border: 0; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 18px; line-height: 24px; font-weight: normal; font-style: normal; margin-top: 37px; text-align: center;">{$subscriber_first_name}, привет!</h2>
<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif;  margin-top: 16px; text-align: center;">Это Лена Закирова, кураторка платформы <a href="https://itv.te-st.ru/">«IT-волонтёр»</a>. Пишу потому, что вы зарегистрированы у нас как заказчик.</p>
<p style="margin: 0; padding: 0; border: 0; font-size: 100%; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; vertical-align: baseline; margin-top: 16px; text-align: center;">
Авторы задач часто спрашивают у меня: как правильно поставить задачу? Как выбрать волонтёра? Какую награду указать, чтобы она мотивировала? Что делать, если на задачу нет откликов? И таких вопросов примерно полсотни :)
</p>
<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; margin-top: 16px; text-align: center;">
Чтобы рассказать вам обо всём, не хватит одного письма или статьи. Поэтому со следующей недели я запускаю рассылку для заказчиков. В ней буду отвечать на самые популярные вопросы и делиться лайфхаками. Расскажу, как составлять задачи, чтобы собирать максимум откликов. Как мотивировать волонтёра на лучший результат. И что делать, если специалист не нашёлся или общение зашло в тупик.
</p>

<h1 style="margin: 0; padding: 0; border: 0; vertical-align: baseline; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 24px; line-height: 32px; font-weight: normal; font-style: normal; letter-spacing: -0.01em; color: #322e28; margin-top: 28px; padding-bottom: 32px; border-bottom: 1px solid #E6DFD1; text-align: center;">Расскажите, что интересует вас больше всего.</h1>

<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; margin-top: 16px; text-align: center;">
Мне важно знать, о чём написать в первую очередь, чтобы это принесло максимум пользы. Поэтому я подготовила небольшой опрос. Он займёт не больше одной минуты, но подарит бесценные знания о том, что вам интересно: 
</p>

<h4 style="margin: 0; padding: 0; font-size: 100%; vertical-align: baseline; text-decoration: none; display: inline-block; border-radius: 8px; box-shadow: inset -1px -1px 1px rgba(0,89,56,0.53); width: 389px; background-color: #0ea36c; border: none; color: #fff; margin-top: 32px; text-align: center;"><a href="https://forms.gle/ZuLCTLxThvRT4tdg6" style="margin: 0; padding: 0; border: 0; vertical-align: baseline; text-decoration: none; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 24px; line-height: 28px; font-weight: normal; font-style: normal; color: #fff; display: block; width: 100%; padding-top: 16px; padding-bottom: 16px;">Пройти опрос</a></h4>
<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; margin-top: 16px; text-align: center;">Письма будут приходить раз в неделю со следующего понедельника. Если вы не хотите получать рассылку — нажмите <a href="{$link_subscription_manage_url}" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline;">отписаться</a>, и я вас не побеспокою.</p>






<!-- BEGIN OF SIGNATURE -->

<h1 style="margin: 0; padding: 0; border: 0; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 24px; line-height: 32px; font-weight: normal; font-style: normal; letter-spacing: -0.01em; color: #322e28; margin-top: 28px; padding-bottom: 32px; border-bottom: 1px solid #E6DFD1; text-align: center;">Отстались или есть вопросы?</h1>

<p style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; line-height:150%; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; margin-top: 16px; text-align: center;">
Пишите мне, Лене Закировой, на почту <a href="mailto:zakirova@te-st.ru">zakirova@te-st.ru</a></p>

<div style="height: 40px; width: 100%;"></div>

<div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0">
            <tbody>
              <tr>
                <td align="center" style="border-collapse:collapse;font-size:0"><!--[if mso]>
                  <table border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
      <td width="330" valign="top">
        <![endif]--><div style="display:inline-block; max-width:330px; vertical-align:top; width:50%;">
          <table width="330" class="mailpoet_cols-two" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse;width:100%;max-width:330px;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
            <tbody>
      <tr>
        <td class="mailpoet_image mailpoet_padded_vertical mailpoet_padded_side" align="center" valign="top" style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px">
          <img src="https://newsletter.te-st.ru/wp-content/uploads/2022/03/imgonline-com-ua-Shape-AoKc1iB10xnBw.png" width="145" alt="imgonline-com-ua-Shape-AoKc1iB10xnBw" style="height:auto;max-width:100%;-ms-interpolation-mode:bicubic;border:0;display:block;outline:none;text-align:center">
        </td>
      </tr>
            </tbody>
          </table>
        </div><!--[if mso]>
      </td>
      <td width="330" valign="top">
        <![endif]--><div style="display:inline-block; max-width:330px; vertical-align:top; width:50%;">
          <table width="330" class="mailpoet_cols-two" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse;width:100%;max-width:330px;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
            <tbody>
      <tr>
        <td class="mailpoet_text mailpoet_padded_vertical mailpoet_padded_side" valign="top" style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;word-break:break-word;word-wrap:break-word">
          <br>
<br>
<table style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0" width="100%" cellpadding="0">
        <tbody><tr>
          <td class="mailpoet_paragraph" style="border-collapse:collapse;mso-ansi-font-size:14px;color:#292929;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;font-size:14px;line-height:19.6px;mso-line-height-alt:20px;word-break:break-word;word-wrap:break-word;text-align:left">
            Лена
          </td>
        </tr></tbody></table>
<table style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0" width="100%" cellpadding="0">
        <tbody><tr>
          <td class="mailpoet_paragraph" style="border-collapse:collapse;mso-ansi-font-size:14px;color:#292929;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;font-size:14px;line-height:19.6px;mso-line-height-alt:20px;word-break:break-word;word-wrap:break-word;text-align:left">
            Берегите себя!<br><br>
          </td>
        </tr></tbody></table>

        </td>
      </tr>
            </tbody>
          </table>
        </div><!--[if mso]>
      </td>
                  </tr>
                </tbody>
              </table>
            <![endif]--></td>
            </tr>
          </tbody>
        </table>
</div>



<!-- BEGIN OF FOOTER -->

<h6 style="margin: 0; padding: 0; border: 0; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 16px; line-height: 24px; font-weight: normal; font-style: normal; margin-top: 32px; padding-top: 32px; border-top: 1px solid #E6DFD1; text-align: center;">Мы с вами всегда на связи и готовы помочь</h6>
</div>
<div class="footer" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; text-align: center; color: #81766f; background-color: #fbf8f4;">
<div class="join-community" style="margin: 0; padding: 0; border: 0; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 16px; line-height: 24px; font-weight: normal; font-style: normal; max-width: 502px; margin-top: 41px; margin-left: auto; margin-right: auto; text-align: center;">
<div style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline;">Пригласите коллег и помогайте своей командой!</div>
<div style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline;">Включитесь вместе в решение интересных кейсов, получайте больший эффект от помощи.</div>
</div>
<div class="build-team" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; margin-top: 32px; text-align: center;"><a href="https://itv.te-st.ru" class="btn danger btn-build-team" style="margin: 0; padding: 0; font-size: 100%; vertical-align: baseline; text-decoration: none; display: inline-block; border-radius: 8px; box-shadow: 1px 1px 4px rgba(129,118,111,0.08),inset -1px -1px 1px rgba(129,118,111,0.16); width: 222px; background-color: #fff; border: 1px solid #E6DFD1; color: #fa7355; padding-top: 8px; padding-bottom: 8px; text-align: center;">Собрать команду</a></div>
<a href="https://te-st.ru" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline;"> <img class="teplitsa-logo" src="https://itv.te-st.ru/wp-content/itv/tstsite/assets_email/img/pic-logo-teplitsa.png" style="padding: 0; font-size: 100%; -ms-interpolation-mode: bicubic; max-width: 100%; border: 0; vertical-align: middle; width: 67.83px; height: 37px; display: inline-block; margin: 41px auto 0px auto; text-align: center;" /></a>
<div class="owner-info" style="margin: 0; padding: 0; border: 0; vertical-align: baseline; font-family: Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 12px; line-height: 16px; font-weight: normal; font-style: normal; max-width: 287px; margin-top: 16px; margin-left: auto; margin-right: auto; text-align: center;"><a href="https://itv.te-st.ru" style="margin: 0; padding: 0; border: 0; font-size: 100%; vertical-align: baseline; text-decoration: none; color: #81766f;">Платформа IT-Волонтер — проект Теплицы социальных технологий</a></div>
</div>
</div>

EOT;

    return $html;
}
add_filter('mailpoet_newsletter_shortcode', 'newsletter_mailpoet_itv_custom_content', 10, 6);
