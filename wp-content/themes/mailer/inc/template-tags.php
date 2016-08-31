<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
 */


/** == Common template functons == **/

/* logos */
function tst_site_logo($size = 'regular') {

	switch($size) {
		case 'fixed':
			$file = 'pic-logo';
			break;			
		default:
			$file = 'icon-logo';
			break;	
	}
	
	$file = esc_attr($file);	
?>
<svg class="logo <?php echo $file;?>">
	<use xlink:href="#<?php echo $file;?>" />
</svg>
<?php
}

function tst_svg_icon($id, $echo = true) {
	
	ob_start();
?>
<svg class="svg-icon <?php echo $id;?>">
	<use xlink:href="#<?php echo $id;?>" />
</svg>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	if($echo)
		echo $out;
	return $out;
}


/* separator */
function tst_get_sep($mark = '//') {
	
	return "<span class='sep'>".$mark."</span>";
}



/** == MailPoet filters == **/

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


 
