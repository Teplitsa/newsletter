<?php
/** General template **/

get_header();

$section_title = '';
$qo = get_queried_object();

if(is_tax() || is_category()){
	$section_title = single_term_title('', false);
}
elseif(is_tag()) {
	$section_title = single_term_title('', false);
	$section_title = "[<span class='tag-title-label'>$section_title</span>]";
}


function tst_build_tag_count_label($number){
	
	$label = "Найдено %d материалов";	
	$test = $number % 10;
	
	if($test == 1)
		$label = "Найден %d материал";
	elseif($test > 1 && $test < 5)
		$label = "Найдено %d материала";
	
	//11 case
	if($number % 100 >= 11 &&  $number % 100 <= 19){
		$label = "Найдено %d материалов";
	}
	
	return sprintf($label, $number);
}

?>

<section class="site-container">
	<div class="s-frame">
	
		<div class="pagination-container s-bit lg-8">
			
			<div class="tst-card  posts-list general-loop inherit-section-color">				
				<!-- header for mobile -->
				<div class="tst-card-title hide-on-medium">
					<h1 class="post-list-title"><?php echo $section_title;?></h1>
				</div>
								
				<?php if(is_tag() && !empty($qo->description)) {  $curator = tst_get_curator_block($qo); ?>
				<div class="card-content card-section supertag-card">
					<div class="s-frame">
					<?php if(!empty($curator)) { ?>
						<div class="s-bit md-3 lg-4"><?php echo $curator;?></div>
					<?php } ?>
						<div class="s-bit <?php if(!empty($curator)) { echo 'md-9 lg-8';} ?>">
							<div class="tag-desc"><?php echo apply_filters('tst_the_content', $qo->description);?></div>
						</div>
					</div>
				</div>
				<?php } ?>
				
				<?php if($wp_query->found_posts > 0 && is_tag()) { ?>
				<div class="card-content card-section right-link ">					
					<span class="card-ut-num"><?php echo tst_build_tag_count_label($wp_query->found_posts);?></span>
					<span class="card-ut-link"><a href='<?php echo home_url('tag');?>'>Все теги</a></span>
				</div>			
				<?php } ?>	
				
				<!-- loop -->
				<div class="card-content card-section">
					<!-- results -->
					<?php						
						if(!$wp_query->have_posts()){
							echo "<h5>По вашему запросу ничего не найдено.</h5>";							
						}
						else {
							foreach($wp_query->posts as $i => $sp){
								tst_print_loop_card($sp);								
							}
						}
					?>
				</div>
				
				<!-- paging -->
				<?php
					if($wp_query->have_posts()){
						tst_paging_nav();
					}
				?>				
			</div>
			
			
		</div>
		
		<div class="s-bit lg-4 hide-upto-large">
			<aside class="sidebar"><?php dynamic_sidebar( 'right-sidebar' ); ?></aside>
		</div>
	</div><!-- .s-frame -->
</section>
<?php get_footer(); ?>