<?php
/** General page template **/

get_header(); ?>

<article id="single-post" class="tpl-page-full page-container no-sharing">
	
	<div class="p-frame">				
		<div id="entry-mid-content" class="p-bit-middle">				
			<header class="page-full-header">				
				<h1 class="entry-title"><?php echo get_the_title($post); ?></h1>							
			</header>
			<div class="entry-content">				
				<?php echo apply_filters('tst_entry_the_content', $post->post_content); ?>				
			</div>					
		</div>
		
		<div class="p-bit-right">
			<aside id="single_sidebar" class="sidebar"><?php dynamic_sidebar( 'page_right-sidebar' ); ?></aside>
		</div>
	</div>
	
</article>

<?php
	if(tst_has_prefooter()) {
		get_template_part('partials/aside', 'page_footer');		
	}
	
	get_footer();