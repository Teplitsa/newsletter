/* Scripts */
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
	
	$( "label.frm_primary_label:contains('reCAPTCHA')" ).hide();
	
}); //jQuery
