<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
 */




/* Custom conditions */
function is_about(){
	global $post;
		
	if(is_page_branch(2))
		return true;
	
	if(is_post_type_archive('org'))
		return true;
	
	if(is_post_type_archive('org'))
		return true;
	
	return false;
}

function is_page_branch($pageID){
	global $post;
	
	if(empty($pageID))
		return false;
		
	if(!is_page() || is_front_page())
		return false;
	
	if(is_page($pageID))
		return true;
	
	if($post->post_parent == 0)
		return false;
	
	$parents = get_post_ancestors($post);
	
	if(is_string($pageID)){
		$test_id = get_page_by_path($pageID)->ID;
	}
	else {
		$test_id = (int)$pageID;
	}
	
	if(in_array($test_id, $parents))
		return true;
	
	return false;
}


function is_tax_branch($slug, $tax) {
	//global $post;
	
	$test = get_term_by('slug', $slug, $tax);
	if(empty($test))
		return false;
	
	if(is_tax($tax)){
		$qobj = get_queried_object();
		if($qobj->term_id == $test->term_id || $qobj->parent == $test->term_id)
			return true;
	}
	
	//if(is_singular() && is_object_in_term($post->ID, $tax, $test->term_id))
	//	return true;
	
	return false;
}


function is_posts() {
	
	if(is_home() || is_category())
		return true;	
		
	if(is_singular('post'))
		return true;
	
	return false;
}


/** Logo **/
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


/** Separator **/
function tst_get_sep($mark = '//') {
	
	return "<span class='sep'>".$mark."</span>";
}

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

 
 
