<?php
// prevent right click - global vars and Galleria init

// frontent JS on header or footer
if(get_option('mg_js_head') != '1') {
	add_action('wp_footer', 'mg_js_flags', 1);
} else { 
	add_action('wp_head', 'mg_js_flags', 999);
}


function mg_js_flags() {
	include_once(MG_DIR.'/functions.php');
	
	$modal_class 	= (get_option('mg_modal_lb')) ? 'mg_modal_lb' : 'mg_classic_lb';
	$box_border 	= (get_option('mg_cells_border')) ? 1 : 0;
	$lb_vert_center = (get_option('mg_lb_not_vert_center')) ? 'false' : 'true';
	$lb_touchswipe 	= (get_option('mg_lb_touchswipe')) ? 'true' : 'false';
	$woocom 		= (mg_woocomm_active()) ? 'true' : 'false';
	?>
	<script type="text/javascript">
	// Media Grid global vars
	mg_boxMargin = <?php echo (int)get_option('mg_cells_margin') ?>;
	mg_boxBorder = <?php echo $box_border ?>;
	mg_imgPadding = <?php echo (int)get_option('mg_cells_img_border') ?>;
	mg_filters_behav = '<?php echo get_option('mg_filters_behav', 'standard') ?>';
	mg_lightbox_mode = "<?php echo $modal_class ?>";
	mg_lb_touchswipe = <?php echo $lb_touchswipe ?>;
	mg_mobile = <?php echo get_option('mg_mobile_treshold', 800) ?>; 
	mg_basescript = "<?php echo MG_URL ?>";
	
	// Galleria global vars
	mg_galleria_fx = '<?php echo get_option('mg_slider_fx', 'fadeslide') ?>';
	mg_galleria_fx_time = <?php echo get_option('mg_slider_fx_time', 400) ?>; 
	mg_galleria_interval = <?php echo get_option('mg_slider_interval', 3000) ?>;
	
	// loader manager for IE 9 and older with body class
	if(	navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
		document.body.className += ' mg_old_loader';
	} else {
		document.body.className += ' mg_new_loader';
	}
	</script>	
	<?php
    
	// if prevent right click
	if(get_option('mg_disable_rclick')) :
		?>
        <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('body').delegate('.mg_grid_wrap *, #mg_full_overlay *', "contextmenu", function(e) {
                e.preventDefault();
            });
		});
		</script>
        <?php	
	endif;
}
