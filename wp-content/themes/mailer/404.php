<?php
/** General page template **/

$email = get_theme_mod('support_email', 'support@te-st.ru'); //option

get_header();
?>
<section class="site-container">
	<div class="tst-card big-border content-404">
		<div class="tst-card-title"><h4>404: Страница не найдена</h4></div>
		
		<div class="card-content">
			<h5>К сожалению, эта страница отсутствует!</h5>
			<h5>Если вы перешли сюда по ссылке, пожалуйста, <a href="mailto:<?php echo $email;?>" target="_blank" class="bordered">дайте нам знать</a>, чтобы мы исправили это как можно быстрее.</h5>
			
			<div class="search-trigger-404"><a href="#" id="additional-search-trigger" class="bordered">Найти на сайте</a></div>			
		</div>
		
	</div>
</section>

<aside class="prefooter site-container">
<?php
	
	$sorted_menu_items = tst_get_main_menu_items();
	$reports = get_term_by('slug', 'reports', 'section');
	
	if(!empty($sorted_menu_items)) { 
?>
	<div class="flex-row">
		<div class="f-item l-1-3 m-2-4">
			<?php tst_print_section_widget($sorted_menu_items[1]->object_id, 3, 'big-border tst-card');?>			
			<?php tst_print_section_widget($sorted_menu_items[2]->object_id, 3, 'big-border tst-card');?>			
		</div>
		
		<div class="f-item l-1-3 m-2-4 l-left m-left">
			<?php tst_print_section_widget($sorted_menu_items[3]->object_id, 3, 'big-border tst-card');?>			
			<?php tst_print_section_widget($sorted_menu_items[4]->object_id, 3, 'big-border tst-card');?>					
		</div>
		
		<div class="f-item l-1-3  l-left hide-upto-large">
			<?php tst_print_section_widget($sorted_menu_items[5]->object_id, 3, 'big-border tst-card');?>			
			<?php tst_print_section_widget($reports->term_id, 3, 'big-border tst-card');?>	
		</div>		
	</div><!-- flex-row -->
	
<?php } ?>
<?php tst_get_subscribe_block('bottom');?>	
</aside>
<?php get_footer(); ?>