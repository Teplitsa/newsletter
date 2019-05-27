/* Scripts */

/** Custom event listener **/
(function(){

	var originalAddClassMethod = jQuery.fn.show;

	jQuery.fn.show = function(){

		var result = originalAddClassMethod.apply( this, arguments );

		jQuery(this).trigger('formSuccess');

		return result;
	}
})();


jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width();
	
	
	
	
	/* Scroll */
	$('.local-scroll').on('click', function(e){
		e.preventDefault();
		
		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();
					
		if (target.top) {			
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}
		
	});

	$(".mailpoet_validate_success").bind('formSuccess', function(){

		$(".tst-mailpoet-form").html('<div class="wysija-msg"><div class="updated"><ul><li>Проверьте Ваш почтовый ящик (если не найдете – то поищите в папке "спам").</li></ul></div></div>');
		$('html, body').animate({scrollTop: 0}, 900);

	});

}); //jQuery

