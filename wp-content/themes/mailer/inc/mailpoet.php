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
			<span style="float: left; display:block; font-size: 12px; line-height: 14px; text-transform: uppercase;">
				<span style="color: #7c8284; font-weight: 400; padding-top: 2px; display:block;"><?php echo $name?></span>
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
		<p style="text-align: center; margin-top: 30px; margin-bottom: 0px; padding-bottom: 22px;">
			<a href="<?php echo $url?>" class="tps-button" style="font-size: 12px; text-transform: uppercase; color: #f2f2f2; background-color: #219665; display: inline-block; text-decoration: none; height: 34px; padding: 6px 28px 0px 28px;border-radius: 20px; letter-spacing: 0.4px;"><?php echo $caption?></a>
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
