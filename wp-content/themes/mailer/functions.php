<?php
/**
 * Funtions
 **/

define('TST_THEME_VERSION', '1.4.1');

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
	add_image_size('profile-avatar', 320, 320, true ); // fixed size for author profile
	add_image_size('portfolio', 420, 420, true ); // fixed size for portfolio item 
	add_image_size('post-thumbnail-medium', 1024, 633, true ); // large thumbnail for single
	add_image_size('post-thumbnail-intro', 1255, 776, true ); // large thumbnail for single
	//add_image_size('avatar', 40, 40, true ); // fixed size for embedding
	//add_image_size('thumbnail-landscape', 190, 142, true ); // fixed size for embedding
	//

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
					),
		'single_right' => array(
						'name' => 'Правая колонка - Записи',
						'description' => 'Боковая колонка справа на страницах записей'
					),
		'page_right' => array(
						'name' => 'Правая колонка - Страницы',
						'description' => 'Боковая колонка справа на страницах'
					),
		'floating_banner' => array(
						'name' => 'Плавающий баннер',
						'description' => 'Баннер в сетке секций и материалов по теме'
					),
		'floating_list' => array(
						'name' => 'Плавающий список',
						'description' => 'Список рекомендаций в сетке секций'
					),
		'home_list' => array(
						'name' => 'Плавающий список - Главная',
						'description' => 'Список рекомендаций в сетке нижней части главной'
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
require get_template_directory().'/inc/class-event.php';
//require get_template_directory().'/inc/class-mediamnt.php';
require get_template_directory().'/inc/class-section.php';
require get_template_directory().'/inc/class-stats.php';
require get_template_directory().'/inc/class-subscribe.php';

require get_template_directory().'/inc/authors.php';
require get_template_directory().'/inc/cards.php';
require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/events.php';
require get_template_directory().'/inc/forms.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/request.php';
require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/template-tags.php';
require get_template_directory().'/inc/teplobot.php';
require get_template_directory().'/inc/widgets.php';

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
	echo "<!-- Theme version ".TST_THEME_VERSION." -->";
}, 100);