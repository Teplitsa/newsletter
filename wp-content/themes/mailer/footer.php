</main>

<?php	
	$nextprev = tst_nextprev_links();
	if(!empty($nextprev)){
?>
<nav class="nextprev">
	<?php echo $nextprev['prev'];?>
	<?php echo $nextprev['next'];?>
</nav>
<?php } ?>

<footer class="site-footer" role="contentinfo">
<?php
	$load_more = tst_load_more_link(); //get_load more
	if($load_more) {
?>
<div class="load-more-button">
	<a href="<?php echo $load_more;?>">
		<?php tst_svg_icon('icon-subscribe-button');?><span>Еще материалы</span>
	</a>
</div>
<?php } ?>
<div class="site-container">	
	
	<div id="footer_logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="footer_logo-link">					
			<?php tst_svg_icon('icon-tree'); ?>			
		</a>
	</div>
	
	<div id="footer_social" class="social-buttons in-footer">
		<?php wp_nav_menu(array('theme_location' => 'social', 'container' => false, 'menu_class' => 'social-menu')); ?>	
	</div>
	
	<div id="footer_info" class="links-menu in-footer">
	<?php
		$locations = get_nav_menu_locations(); 
		$menu = (isset($locations['footer'])) ? wp_get_nav_menu_object( $locations['footer'] ) : false;
		$class = '';
		
		if(isset($menu->count)){
			$class = ((int)$menu->count % 2 == 0) ? ' even-items' : ' odd-items';
		}
		
		wp_nav_menu(array('theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu'.$class, 'fallback_cb' => false));
	?>	
	</div>
	
	<div id="footer_caption" class="footer-caption">	
		<?php echo apply_filters('tst_the_content', get_theme_mod('footer_text')); ?>
	</div>
	
</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>