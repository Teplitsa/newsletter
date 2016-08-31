<?php
/**
 * Admin customization
 **/

add_filter('manage_posts_columns', 'tst_common_columns_names', 50, 2);
function tst_common_columns_names($columns, $post_type) {
		
	if(in_array($post_type, array('post', 'programm', 'org', 'person',))){
		
		//if(in_array($post_type, array('programm')))
		//	$columns['menu_order'] = 'Порядок';
				
		if(!in_array($post_type, array('attachment')))
			$columns['thumbnail'] = 'Миниат.';
		
		if(isset($columns['author'])){
			$columns['author'] = 'Создал';
		}
		
		$columns['id'] = 'ID';
	}
	
	return $columns;
}

add_action('manage_pages_custom_column', 'tst_common_columns_content', 2, 2);
add_action('manage_posts_custom_column', 'tst_common_columns_content', 2, 2);
function tst_common_columns_content($column_name, $post_id) {
	
	$cpost = get_post($post_id);
	if($column_name == 'id'){
		echo intval($cpost->ID);
		
	}
	elseif($column_name == 'thumbnail') {
		$img = get_the_post_thumbnail($post_id, 'thumbnail');
		if(empty($img))
			echo "&ndash;";
		else
			echo "<div class='admin-tmb'>{$img}</div>";			
		
	}
	elseif($column_name == 'event_start') {
		$event = new TST_Event($post_id);
		echo $event->get_date_mark('formal');
	}
	elseif($column_name == 'menu_order') {
			
		echo intval($cpost->menu_order);
	}
}


add_filter('manage_pages_columns', 'tst_pages_columns_names', 50);
function tst_pages_columns_names($columns) {		
		
	if(isset($columns['author'])){
		$columns['author'] = 'Создал';
	}
	
	//$columns['menu_order'] = 'Порядок';	
	$columns['id'] = 'ID';
		
	return $columns;
}




//manage_edit-topics_columns
add_filter( "manage_edit-category_columns", 'tst_common_tax_columns_names', 10);
add_filter( "manage_edit-post_tag_columns", 'tst_common_tax_columns_names', 10);
function tst_common_tax_columns_names($columns){
	
	$columns['id'] = 'ID';
	
	return $columns;	
}

add_filter( "manage_category_custom_column", 'tst_common_tax_columns_content', 10, 3);
add_filter( "manage_post_tag_custom_column", 'tst_common_tax_columns_content', 10, 3);
function tst_common_tax_columns_content($content, $column_name, $term_id){
	
	if($column_name == 'id')
		return intval($term_id);
}


/* admin tax columns */
/*add_filter('manage_taxonomies_for_material_columns', function($taxonomies){
	$taxonomies[] = 'pr_type';
	$taxonomies[] = 'audience';
	
    return $taxonomies;
});*/



/**
* SEO UI cleaning
**/
add_action('admin_init', function(){
	foreach(get_post_types(array('public' => true), 'names') as $pt) {
		add_filter('manage_' . $pt . '_posts_columns', 'tst_clear_seo_columns', 100);
	}	
}, 100);

function tst_clear_seo_columns($columns){

	if(isset($columns['wpseo-score']))
		unset($columns['wpseo-score']);
	
	if(isset($columns['wpseo-title']))
		unset($columns['wpseo-title']);
	
	if(isset($columns['wpseo-metadesc']))
		unset($columns['wpseo-metadesc']);
	
	if(isset($columns['wpseo-focuskw']))
		unset($columns['wpseo-focuskw']);
	
	return $columns;
}

add_filter('wpseo_use_page_analysis', '__return_false');


/* Excerpt metabox */
add_action('add_meta_boxes', 'tst_correct_metaboxes', 2, 2);
function tst_correct_metaboxes($post_type, $post ){
	
	if(post_type_supports($post_type, 'excerpt')){
		remove_meta_box('postexcerpt', null, 'normal');
		
		$label = ($post_type == 'org') ? __('Website', 'tst') : __('Excerpt', 'tst');
		add_meta_box('tst_postexcerpt', $label, 'tst_excerpt_meta_box', null, 'normal', 'core');
	}
	
}

function tst_excerpt_meta_box($post){
	if($post->post_type == 'org'){
?>
<label class="screen-reader-text" for="excerpt"><?php _e('Website', 'tst'); ?></label>
<input type="text" name="excerpt" id="url-excerpt" value="<?php echo $post->post_excerpt; // textarea_escaped ?>" class="widefat">

<?php }	else { ?>

<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt', 'tst'); ?></label>
<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
<p><?php _e('Annotation for items lists (will be printed at the beginning of the single page)', 'tst'); ?></p>

<?php	
}
	
}


/**  Home page settings in admin menu */
add_action('admin_menu', 'tst_add_menu_items', 25);
function tst_add_menu_items(){
    
	$id = (int)get_option('page_on_front', 0);
	
    add_submenu_page('index.php',
                    'Настройки главной',
                    'Настройки главной',
                    'edit_pages',
                    'post.php?post='.$id.'&action=edit'                    
    );
	
	add_submenu_page('leyka',
                    'Категории',
                    'Категории',
                    'leyka_manage_donations',
                    'edit-tags.php?taxonomy=campaign_cat&post_type=leyka_campaign'       
    );   
}



/** Visual editor **/
add_filter('tiny_mce_before_init', 'tst_format_TinyMCE');
function tst_format_TinyMCE($in){

    $in['block_formats'] = "Абзац=p; Выделенный=pre; Заголовок 3=h3; Заголовок 4=h4; Заголовок 5=h5; Заголовок 6=h6";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';

	return $in;
}

/* Menu Labels */
//add_action('admin_menu', 'tst_admin_menu_labels');
function tst_admin_menu_labels(){ /* change adming menu labels */
    global $menu, $submenu;
	
    //lightbox   
    foreach($submenu['options-general.php'] as $order => $item){
		
        if(isset($item[2]) && $item[2] == 'responsive-lightbox'){
			$submenu['options-general.php'][$order][0] = 'Lightbox';			
		}        
    }
	
	//forms
	foreach($menu as $order => $item){
        
        if($item[2] == 'ninja-forms'){ 
            $menu[$order][0] = __('Forms', 'tst');            
            break;
        }
    }   
}



/** Dashboards widgets **/
add_action('wp_dashboard_setup', 'tst_remove_dashboard_widgets' );
function tst_remove_dashboard_widgets() {
	
	//remove defaults 	
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );	
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		
	
	//add ours
	$locale = get_locale();
    
    if($locale == 'ru_RU') {
        add_meta_box('custom_links', 'Полезные ссылки', 'tst_custom_links_dashboard_screen', 'dashboard', 'side', 'core');
    }	
} 



function tst_custom_links_dashboard_screen(){
	
	//tst_itv_info_widget();
	//tst_support_widget();


}

function tst_itv_info_widget(){
	    
    $src = get_template_directory_uri().'/assets/img/logo-itv.png';
    $domain = parse_url(home_url()); 
    $itv_url = "https://itv.te-st.ru/?giger=".$domain['host'];
?>
<div id="itv-dashboard-card" class="tst-dashboard">
	<div class="cols">
		<div class="col-logo"><div class="itv-logo col-logo">
			<a href="<?php echo esc_url($itv_url);?>" target="_blank"><img src="<?php echo esc_url($src);?>"></a>
		</div></div>
		<div class="col-btn"><a href="<?php echo esc_url($itv_url);?>" target="_blank" class="button">Опубликовать задачу</a></div>
	</div>
	
	
	<p>Вам нужна помощь в настройке или доработке сайта?<br>Опубликуйте задачу на платформе <a href="<?php echo esc_url($itv_url);?>" target="_blank">it-волонтер</a></p>                
	
</div>
<?php
}

function tst_support_widget(){
	
	$src = get_template_directory_uri().'/assets/img/tst-logo';
	
	$doc = (defined('TST_DOC_URL') && !empty(TST_DOC_URL)) ? TST_DOC_URL : '';
	if(!empty($doc))
		$doc = str_replace('<a', '<a target="_blank" ', make_clickable($doc));
	
?>
<div id="tst-support-card" class="tst-dashboard">
	<div class="cols">
		
	<div class="col-logo"><div class="tree-logo">
		<img src="<?php echo $src;?>.svg" onerror="this.onerror=null;this.src=<?php echo $src;?>.png">
	</div></div>
	<div class="col-btn"><a href="mailto:support@te-st.ru" target="_blank" class="button">Написать в поддержку</a></div>
	</div>
	
	<p>Возникли проблемы с использованием сайта, нашли ошибку?<br>Обратитесь в поддержку Теплицы социальных технологий <a href="mailto:support@te-st.ru" target="_blank">support@te-st.ru</a></p>
	<?php if(!empty($doc)) { ?>
		<p>Справочная информация по работе с сайтом находится по ссылке <?php echo $doc; ?></p>
	<?php } ?>
</div>
<?php
}

/** Notification about wrong thumbnail size **/
add_filter('admin_post_thumbnail_html', 'tst_thumbnail_dimensions_check', 10, 2);
function tst_thumbnail_dimensions_check($thumbnail_html, $post_id) {
	global $_wp_additional_image_sizes;
	
	if('org' == get_post_type($post_id))
		return $thumbnail_html;
	
    $meta = wp_get_attachment_metadata(get_post_thumbnail_id($post_id));
    $needed_sizes = (isset($_wp_additional_image_sizes['post-thumbnail'])) ? $_wp_additional_image_sizes['post-thumbnail'] : false;
	
    if(
        $meta && $needed_sizes &&
        ($meta['width'] < $needed_sizes['width'] || $meta['height'] < $needed_sizes['height'])
    ) {
	
	$size = "<b>".$needed_sizes['width'].'x'.$needed_sizes['height']."</b>";
	$txt = sprintf(__('ATTENTION! You thumbnail image is too small. It should be at least %s px', 'tst'), $size);
	
    echo "<p class='tst-error'>{$txt}<p>";
    }

    return $thumbnail_html;
}


/** == Revome unused metabox == **/
//add_action( 'add_meta_boxes' , 'tst_remove_wrong_metaboxes', 20 );
function tst_remove_wrong_metaboxes() {
	
	//hide section default metabox
	remove_meta_box('tagsdiv-auctor', 'post', 'side');
	
}



/** == MailPoet UI == **/
add_filter( 'user_has_cap', 'tst_mailpoet_permissions', 100, 4 );
function tst_mailpoet_permissions( $allcaps, $cap, $args, $user ) {
	
	//no access for style/theme tab
	if(isset($cap[0]) && $cap[0] == 'wysija_style_tab' && isset($allcaps['wysija_style_tab'])){
		$allcaps['wysija_style_tab'] = false;		
	}
	if(isset($cap[0]) && $cap[0] == 'wysija_theme_tab' && isset($allcaps['wysija_theme_tab'])){
		$allcaps['wysija_theme_tab'] = false;		
	}
	
	
	return $allcaps;
}


/** add body class for admin **/
add_filter('admin_body_class', 'tst_admin_body_class');
function tst_admin_body_class($class) {
	
	if(current_user_can('manage_options'))
		return $class;
	
	$class .= ' non-admin-user';
	
	return $class;
}