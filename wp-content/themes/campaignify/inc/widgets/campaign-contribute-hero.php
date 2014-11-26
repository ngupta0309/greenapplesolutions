<?php
/**
 * Campaignify Widget: Contribute Hero
 *
 * Provides as massive slider powered by Soliloquy. Manage image titles, captions,
 * etc via the Soliloquy interface, and define the gallery via the widget. 
 *
 * Also has the ability to include a animated pledge bar, donate button, and share links.
 *
 * @package Campaignify
 * @since Campaignify 1.0
 */
class Campaignify_Campaign_Hero_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function Campaignify_Campaign_Hero_Widget() {
		$widget_ops = array( 
			'classname' => 'widget_campaignify_hero_contribute', 
			'description' => __( 'Display an image slider behind contribution information.', 'campaignify' ) 
		);

		$this->WP_Widget( 'widget_campaignify_hero_contribute', __( 'Campaign Hero Slider', 'campaignify' ), $widget_ops );

		$this->alt_option_name = 'widget_campaignify_hero_contribute';
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void Echoes its output
	 **/
	function widget( $args, $instance ) {
		global $campaign;

		ob_start();
		extract( $args, EXTR_SKIP );

		echo $before_widget;

		$images  = campaignify_contribute_hero_ids( campaignify_item_meta( 'campaignify_slider', $campaign->ID ), $campaign->ID );
		$percent = $campaign->percent_completed( 'raw' ) > 100 ? '100%' : $campaign->percent_completed();
?>
		<div class="campaign-hero loading <?php echo ! empty( $images ) && isset ( $instance[ 'gallery' ] ) ? 'has-slideshow' : 'no-slideshow'; ?>">
			<div class="campaign-hero-donate-options animated fadeInDown">
				
				<?php if ( $instance[ 'button' ] ) : ?>
				<div class="donation-donate">
					<a href="/contact-us" class="button button-primary contribute">Contact Us</a>
				</div>
				<?php endif; ?>
 			
			</div>
			
			<?php if ( ! empty( $images ) && isset ( $instance[ 'gallery' ] ) ) : ?>
			<div class="campaign-hero-slider">
				<ul class="slides">
					<?php foreach ( $images as $image ) : $data = get_post( $image ); ?>
					<li class="campaign-hero-slider-item">
						<div class="campaign-hero-slider-info">
							<h2 class="campaign-hero-slider-title animated fadeInDown"><?php echo $data->post_title; ?></h2>
							<p class="campaign-hero-slider-desc animated fadeInDown"><?php echo $data->post_content; ?></p>
						</div>
						
						<img src="<?php echo esc_url( wp_get_attachment_url( $data->ID, 'post-thumbnail' ) ); ?>" />
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		<div>
<?php
		echo $after_widget;
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance[ 'gallery' ]     = isset( $new_instance[ 'gallery' ] ) ? 1 : 0;
		$instance[ 'bar' ]         = isset( $new_instance[ 'bar' ] ) ? 1 : 0;
		$instance[ 'button' ]      = isset( $new_instance[ 'button' ] ) ? 1 : 0;
		$instance[ 'button_text' ] = esc_attr( $new_instance[ 'button_text' ] );
		$instance[ 'share' ]       = isset( $new_instance[ 'share' ] ) ? 1 : 0;
		$instance[ 'share_text' ]  = esc_attr( $new_instance[ 'share_text' ] );

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 **/
	function form( $instance ) {
		$gallery     = isset( $instance[ 'gallery' ] ) ? $instance[ 'gallery' ] : 1;
		$bar         = isset( $instance[ 'bar' ] ) ? $instance[ 'bar' ] : 1;
		$button      = isset( $instance[ 'button' ] ) ? $instance[ 'button' ] : 1;
		$button_text = isset( $instance[ 'button_text' ] ) ? esc_attr( $instance[ 'button_text' ] ) : __( 'Donate Now', 'campaignify' );
		$share       = isset( $instance[ 'share' ] ) ? $instance[ 'share' ] : 1;
		$share_text  = isset( $instance[ 'share_text' ] ) ? esc_attr( $instance[ 'share_text' ] ) : __( 'Spread the Word:', 'campaignify' );
?>
			<p>
				<strong><?php _e( 'Gallery:', 'campaignify' ); ?></strong>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'gallery' ) ); ?>">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'gallery' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'gallery' ) ); ?>" value="<?php echo $gallery; ?>" <?php checked( $gallery, 1 ); ?> /> <?php _e( 'Display image gallery slider?', 'campaignify' ); ?>
				</label>
			</p>

			<p>
				<strong><?php _e( 'Donations:', 'campaignify' ); ?></strong>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bar' ) ); ?>">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'bar' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'bar' ) ); ?>" value="<?php echo $bar; ?>" <?php checked( $bar, 1 ); ?> /> <?php _e( 'Display donation progress bar?', 'campaignify' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button' ) ); ?>">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'button' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'button' ) ); ?>" value="<?php echo $button; ?>" <?php checked( $button, 1 ); ?> /> <?php _e( 'Display donation button?', 'campaignify' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Button Text:', 'campaignify' ); ?></label>

				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" value="<?php echo $button_text; ?>" />
			</p>

			<p>
				<strong><?php _e( 'Social:', 'campaignify' ); ?></strong>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'share' ) ); ?>">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'share' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'share' ) ); ?>" value="<?php echo $share; ?>" <?php checked( $share, 1 ); ?> /> <?php _e( 'Display share buttons?', 'campaignify' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'share_text' ) ); ?>"><?php _e( 'Share Text:', 'campaignify' ); ?></label>

				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'share_text' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'share_text' ) ); ?>" value="<?php echo $share_text; ?>" />
			</p>
		<?php
	}
}

function campaignify_contribute_hero_ids( $shortcode, $campaign_id = null ) {
	$images = array();

	if ( '' == $shortcode )
		return $images;
	
	if ( function_exists( 'get_content_galleries' ) ) {
		$galleries = get_content_galleries( $shortcode, false, false, 1 );

		if ( isset( $galleries[0]['ids'] ) )
			 $images = explode( ',', $galleries[0]['ids'] );
	} else {
		$pattern = get_shortcode_regex();
		preg_match( "/$pattern/s", $shortcode, $match );
		$atts = shortcode_parse_atts( $match[3] );
		if ( isset( $atts['ids'] ) )
			$images = explode( ',', $atts['ids'] );
	}

	return $images;
}