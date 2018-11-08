</main>

<footer class="site-footer" role="contentinfo">

<div class="container">	
	
	<div class="footer-content">
    
    Теплица социальных технологий
    
    <?php wp_nav_menu(array('menu' => 'footer-links', 'container' => false, 'menu_class' => 'pd-footer-links')); ?>
    
    </div>
    
</div>
</footer>

<?php wp_footer(); ?>

<script>
jQuery(function($){
    $('.mdl-textfield--file .mdl-textfield__input').click(function(){
        $(this).closest('.mdl-textfield--file').find('input[type=file]').click();
    });
    
    $('.mdl-textfield--file .material-icons').click(function(){
        $(this).closest('.mdl-textfield--file').find('input[type=file]').click();
    });
    
    $('.mdl-textfield--file input[type=file]').change(function(){
        $(this).closest('.mdl-textfield--file').find('.mdl-textfield__input').val($(this).val().replace(/^.*[\\\/]/, ''));
    });
});
</script>

</body>
</html>