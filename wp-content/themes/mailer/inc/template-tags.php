<?php
/**
 * Custom template tags for this theme.
 *
 */


/** == Common template functons == **/

/* logos */
function tst_site_logo($size = 'regular') {

	switch($size) {
		case 'fixed':
			$file = 'pic-logo';
			break;			
		default:
			$file = 'icon-logo';
			break;	
	}
	
	$file = esc_attr($file);	
?>
<svg class="logo <?php echo $file;?>">
	<use xlink:href="#<?php echo $file;?>" />
</svg>
<?php
}

function tst_svg_icon($id, $echo = true) {
	
	ob_start();
?>
<svg class="svg-icon <?php echo $id;?>">
	<use xlink:href="#<?php echo $id;?>" />
</svg>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	if($echo)
		echo $out;
	return $out;
}


/* separator */
function tst_get_sep($mark = '//') {
	
	return "<span class='sep'>".$mark."</span>";
}


 
