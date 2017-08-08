<?php
/**
 * Funtions
 **/

define('TSTN_THEME_VERSION', '1.0');

if ( ! isset( $content_width ) ) {
	$content_width = 629; /* pixels */
}

if ( ! function_exists( 'tst_setup' ) ) :
function tst_setup() {

	// Inits
//	load_theme_textdomain('tst', get_template_directory() . '/lang');
	add_theme_support( 'automatic-feed-links' );	
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(600, 371, true ); // regular thumbnails 16:9	
	//add_image_size('square', 420, 420, true ); // fixed size for portfolio item 
	
	// Menus
	register_nav_menus(array(
		'primary' => 'Главное меню',				
		'social'  => 'Социальные кнопки',
		'footer'  => 'Меню в футере',
		'sitemap' => 'Карта сайта',
	));

	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
endif; // tst_setup
add_action( 'after_setup_theme', 'tst_setup' );

/** Custom image size for medialib **/
//add_filter('image_size_names_choose', 'tst_medialib_custom_image_sizes');
function tst_medialib_custom_image_sizes($sizes) {
	
	$addsizes = apply_filters('tst_medialib_custom_image_sizes', array(
		//"thumbnail-landscape" => __("Landscape mini", 'frl'),
		"embed" => __('Fixed', 'frl')
	));
		
	return array_merge($sizes, $addsizes);
}

/** Register widget area. **/
add_action( 'init', 'tst_widgets_init', 25 );
function tst_widgets_init() {
		
	$config = array(
		'right' => array(
						'name' => 'Правая колонка',
						'description' => 'Общая боковая колонка справа'
					)		
	);
		
	foreach($config as $id => $sb) {
		
		$before = '<div id="%1$s" class="widget %2$s">';
		
		if(false !== strpos($id, 'footer')){
			$before = '<div id="%1$s" class="widget-bottom %2$s">';
		}		
		
		register_sidebar(array(
			'name' => $sb['name'],
			'id' => $id.'-sidebar',
			'description' => $sb['description'],
			'before_widget' => $before,
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h3>',
			'after_title' => '</h3></div>',
		));
	}
}





require get_template_directory().'/inc/class-cssjs.php';
//require get_template_directory().'/inc/class-mediamnt.php';

require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/mailpoet.php';
require get_template_directory().'/inc/template-tags.php';

if(is_admin()){
	require get_template_directory().'/inc/admin.php';
}


	
/** Cron **/
function tst_cron_job() {
	
	if (!wp_next_scheduled( 'tst_daily_events')) {
		wp_schedule_event( strtotime('today midnight'), 'daily', 'tst_daily_events' );
	}
}
add_action( 'wp', 'tst_cron_job' );

/** Version in footer **/
add_action('wp_footer', function(){
	echo "<!-- Theme version ".TSTN_THEME_VERSION." -->";
}, 100);