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


<header id="site_header" class="site-header nav-init">
	<!-- panel -->
	<div id="logo_panel" class="logo-panel">
		
		<div class="header-logo">
			<a href="<?php echo home_url('sitemap');?>" id="menu_trigger" class="trigger-button menu"><?php tst_svg_icon('icon-menu'); ?><?php tst_svg_icon('icon-close');?></a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">					
				<?php echo tst_site_logo('short'); ?>					
			</a>
		</div>
		
		<div class="header-title"><?php
			$force_l = (is_singular()) ? false : 50;
			tst_section_title($force_l);
		?></div>
		
		<div class="header-options"><?php
			$sbs = get_page_by_path('subscribe');
			if($sbs) {
		?><a href="<?php echo home_url('subscribe');?>" id="subscribe-trigger" class="trigger-link subscribe">Подпишись</a>
		<?php } ?>
			<?php $s_url = trailingslashit(home_url()).'?s='; //fallback for no JS ?>	
			<a href="<?php echo $s_url;?>" id="search-trigger" class="trigger-button search"><?php tst_svg_icon('icon-search');?><?php tst_svg_icon('icon-close');?></a>
		</div>
	</div>
	
	<!-- search -->
	<div id="site_search" class="site-search"><?php get_search_form();?></div>
	
	<!-- menu -->
	<nav id="site_nav" class="site-nav"><span class="screen-reader-text">Главное меню</span>		
		<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu')); ?>		
		<div class="subscribe-panel"><?php tst_get_subscribe_block('top');?></div>
	</nav>
	
</header>

<main id="site_content" class="site-content"><a name="site_content-a"></a>