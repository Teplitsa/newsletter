<?php
/** General template **/

get_header();


$qo = get_queried_object();

?>
<section id="site_container" class="site-container">
	
	<!-- loop -->
	<div class="container">
		
		<?php foreach($wp_query->posts as $i => $p){ ?>
			<article class="tpl-post">
				<h4><?php echo apply_filters('tst_the_title', get_the_title($p)); ?></h4>
				<div class="entry-content"><?php echo apply_filters('the_content', $p->post_content); ?></div>
			</article>
		<?php } ?>
	</div>
</section>
<?php get_footer(); ?>