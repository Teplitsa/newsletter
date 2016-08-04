<?php
/** General page template **/

$qo = get_queried_object();

get_header(); ?>

<article id="page" class="tpl-page-full">
	
	<div class="container">				
		<h2><?php echo apply_filters('tst_the_title', get_the_title($qo)); ?></h2>
		
		<div class="entry-content"><?php echo apply_filters('the_content', $qo->post_content); ?></div>
	</div>
	
</article>

<?php get_footer();