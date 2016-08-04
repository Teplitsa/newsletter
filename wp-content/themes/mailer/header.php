<?php
/**
 * The header for our theme.
 *
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset');?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php wp_head();?>
</head>

<body id="top" <?php body_class(); ?>>
<?php include_once(get_template_directory()."/assets/svg/svg.svg"); //all svgs ?>
<a class="skip-link screen-reader-text" href="#site_content-a">Перейти к содержанию</a>


<header id="site_header" class="site-header">
	<!-- panel -->
	<div id="logo_panel" class="logo-panel">
		
		<div class="header-logo">			
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">					
				<?php echo tst_site_logo(); ?>					
			</a>
		</div>
	</div>
</header>

<main id="site_content" class="site-content"><a name="site_content-a"></a>