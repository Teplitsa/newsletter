<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bb
 */



/** Default filters **/
add_filter( 'tst_the_content', 'wptexturize'        );
add_filter( 'tst_the_content', 'convert_smilies'    );
add_filter( 'tst_the_content', 'convert_chars'      );
add_filter( 'tst_the_content', 'wpautop'            );
add_filter( 'tst_the_content', 'shortcode_unautop'  );
add_filter( 'tst_the_content', 'do_shortcode' );

add_filter( 'tst_the_title', 'wptexturize'   );
add_filter( 'tst_the_title', 'convert_chars' );
add_filter( 'tst_the_title', 'trim'          );

global $wp_embed;
add_filter( 'tst_entry_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'tst_entry_the_content', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'tst_entry_the_content', 'wptexturize'                       );
add_filter( 'tst_entry_the_content', 'convert_smilies'                   );
add_filter( 'tst_entry_the_content', 'convert_chars'                     );
add_filter( 'tst_entry_the_content', 'tst_entry_wpautop'                 );
add_filter( 'tst_entry_the_content', 'shortcode_unautop'                 );
add_filter( 'tst_entry_the_content', 'prepend_attachment'                );
add_filter( 'tst_entry_the_content', 'tst_force_https'                   );
add_filter( 'tst_entry_the_content', 'wp_make_content_images_responsive' );
add_filter( 'tst_entry_the_content', 'do_shortcode', 11                  ); 


/* jpeg compression */
add_filter( 'jpeg_quality', create_function( '', 'return 95;' ) );


/* temp fix for wpautop in posts */
function tst_entry_wpautop($content){
	
	if(false === strpos($content, '[page_section')){
		$content = wpautop($content);
	}
	
	return $content;
}

 
/** Current URL  **/
if(!function_exists('tst_current_url')){
function tst_current_url() {
   
    $pageURL = 'http';
   
    if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {$pageURL .= "s";}
    $pageURL .= "://";
   
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
   
    return $pageURL;
}
}


/** Extract posts IDs from query **/
function tst_get_posts_ids_from_query($query){
	
	$ids = array();
	if(!$query->have_posts())
		return $ids;
	
	foreach($query->posts as $qp){
		$ids[] = $qp->ID;
	}
	
	return $ids;
}

function tst_get_post_id_from_posts($posts){
		
	$ids = array();
	if(!empty($posts)){ foreach($posts as $p) {
		$ids[] = $p->ID;
	}}
	
	return $ids;
}

function tst_get_term_id_from_terms($terms){
		
	$ids = array();
	if(!empty($terms)){ foreach($terms as $t) {
		$ids[] = $t->term_id;
	}}
	
	return $ids;
}


/** Favicon **/
function tst_favicon(){
	
	$favicon_test = WP_CONTENT_DIR. '/favicon.ico'; //in the root not working don't know why
    if(!file_exists($favicon_test))
        return;
        
    $favicon = content_url('favicon.ico');
	echo "<link href='{$favicon}' rel='shortcut icon' type='image/x-icon' >";
}
add_action('wp_head', 'tst_favicon', 1);
add_action('admin_head', 'tst_favicon', 1);
add_action('login_head', 'tst_favicon', 1);

/** Add feed link **/
add_action('wp_head', 'tst_feed_link');
function tst_feed_link(){
	
	$name = get_bloginfo('name');
	echo '<link rel="alternate" type="'.feed_content_type().'" title="'.esc_attr($name).'" href="'.esc_url( get_feed_link() )."\" />\n";
}


/** Adds custom classes to the array of body classes **/
add_filter( 'body_class', 'tst_body_classes' );
function tst_body_classes( $classes ) {
	
	if(is_page()){
		$qo = get_queried_object();
		$classes[] = 'slug-'.$qo->post_name;
	}
	return $classes;
}




/** Options in customizer **/
add_action('customize_register', 'tst_customize_register', 15);
function tst_customize_register(WP_Customize_Manager $wp_customize) {
    
	
	$wp_customize->add_setting('footer_text', array(
        'default'   => '',
        'transport' => 'refresh',
		'option' => 'option'
    ));
    
    $wp_customize->add_control('footer_text', array(
        'type'     => 'textarea',		
        'label'    => 'Текст в подвале',
        'section'  => 'title_tagline',
        'settings' => 'footer_text',
        'priority' => 28,
    ));
		
	
	//Images
	/*$wp_customize->add_setting('default_thumbnail', array(
        'default'   => false,
        'transport' => 'refresh', // postMessage
    ));
	
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_thumbnail', array(
        'label'    => 'Миниатюра по умолчанию',
        'section'  => 'title_tagline',
        'settings' => 'default_thumbnail',
        'priority' => 60,
    )));*/
	
		
	
	$wp_customize->remove_setting('site_icon'); //remove favicon	
}



/** Admin bar **/
add_action('wp_head', 'tst_adminbar_corrections');
add_action('admin_head', 'tst_adminbar_corrections');
function tst_adminbar_corrections(){
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	add_action( 'admin_bar_menu', 'tst_adminbar_logo', 10 );
}


function tst_adminbar_logo($wp_admin_bar){	
	
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => '',
	) );
}

add_action('wp_footer', 'tst_adminbar_voices');
add_action('admin_footer', 'tst_adminbar_voices');

function tst_adminbar_voices() {
	
?>
<script>	
	jQuery(document).ready(function($){		
		if ('speechSynthesis' in window) {
			var speech_voices = window.speechSynthesis.getVoices(),
				utterance  = new SpeechSynthesisUtterance();
				
				function set_speach_options() {
					speech_voices = window.speechSynthesis.getVoices();
					utterance.text = "I can't lie to you about your chances, but... you have my sympathies.";
					utterance.lang = 'en-GB'; 
					utterance.volume = 0.9;
					utterance.rate = 0.9;
					utterance.pitch = 0.8;
					utterance.voice = speech_voices.filter(function(voice) { return voice.name == 'Google UK English Male'; })[0];
				}
								
				window.speechSynthesis.onvoiceschanged = function() {				
					set_speach_options();
				};
								
				$('#wp-admin-bar-wp-logo').on('click', function(e){
					
					if (!utterance.voice || utterance.voice.name != 'Google UK English Male') {
						set_speach_options();
					}
					speechSynthesis.speak(utterance);
				});
		}			
	});
</script>
<?php
}

/** == Filter to ensure https for local URLs in content == **/
function tst_force_https($content){
	
	if(!is_ssl())
		return $content;
	
	//protocol relative internal links
	$https_home = home_url('', 'https');
	$http_home = home_url('', 'http');
	$rel_home = str_replace('http:', '', $http_home);
	
	$content = str_replace($http_home, $rel_home, $content);
	$content = str_replace($https_home, $rel_home, $content);
	
	//protocol relative url in src (for external links)
	preg_match_all( '@src="([^"]+)"@' , $content, $match );
	
	if(!empty($match) && isset($match[1])){
		foreach($match[1] as $i => $test_url){
			if(false !== strpos($test_url, 'http:')){
				$replace_url = str_replace('http:', '', $test_url);
				$content = str_replace($test_url, $replace_url, $content);
			}
		}
	}
	
	return $content;
}
