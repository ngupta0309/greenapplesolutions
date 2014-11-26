<?php
/**
 * Campaignify Widget: Backers
 *
 * Display a slider of backers with their avatar, name, and amount.
 *
 * Also has the ability to set a title, description
 *
 * @package Campaignify
 * @since Campaignify 1.0
 */
class Campaignify_Campaign_Backers_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function Campaignify_Campaign_Backers_Widget() {
		$widget_ops = array( 
			'classname' => 'widget_campaignify_campaign_backers', 
			'description' => __( 'Display a slider of all the people who have contributed to the campaign.', 'campaignify' ) 
		);

		$this->WP_Widget( 'widget_campaignify_campaign_backers', __( 'Campaign Backers', 'campaignify' ), $widget_ops );

		$this->alt_option_name = 'widget_campaignify_campaign_backers';
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
		
		$title       = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? __( 'Campaign Backers', 'campaignify' ) : $instance[ 'title' ], $instance, $this->id_base);
		$description = isset ( $instance[ 'description' ] ) ? $instance[ 'description' ] : null;
		$backers     = $campaign->backers();

		echo $before_widget;
?>
		<div class="container">
			<?php
				if ( '' != $title ) {
					echo $before_title;
					echo $title;
					echo $after_title;
				}
			?>

			<div class="campaign-backers-description">
			<?php if ( ! empty( $backers ) ) :  ?>
				<?php echo wpautop ( $description ); ?>
			<?php else : ?>
				<p><?php _e( 'No backers yet. Be the first!', 'campaignify' ); ?></p>
				<a href="#" class="contribute button button-primary"><?php _e( 'Donate Now', 'campaignify' ); ?></a>
			<?php endif; ?>
			</div>

			<?php if ( ! empty( $backers ) ) : ?>
			<div class="campaign-backers-slider-wrap">
				<div class="campaign-backers-slider">
					<ul class="slides clear">
						<?php 
							foreach ( $backers as $backer ) : 
								$payment_id = get_post_meta( $backer->ID, '_edd_log_payment_id', true );
								$payment    = get_post( $payment_id );

								if ( ! is_object( $payment ) )
									continue;

								$meta       = edd_get_payment_meta( $payment->ID );
								$user_info  = edd_get_payment_meta_user_info( $payment_id );

								if ( empty( $user_info ) )
									continue;

								$anon       = isset ( $meta[ 'anonymous' ] ) ? $meta[ 'anonymous' ] : 0;
						?>
						<li class="campaign-backers-slider-item">
							<?php echo get_avatar( $anon ? '' : $user_info[ 'email' ], 75 ); ?>

							<h3 class="campaign-backers-name">
								<?php if ( $anon ) : ?>
									<?php _ex( 'Anonymous', 'Backer chose to hide their name', 'campaignify' ); ?>
								<?php else : ?>
									<?php echo $user_info[ 'first_name' ]; ?> <?php echo $user_info[ 'last_name' ]; ?></h3>
								<?php endif; ?>
							<p class="campaign-backers-donation"><?php printf( __( 'Donated %s', 'campaignify' ), edd_payment_amount( $payment_id ) ); ?></p>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php endif; ?>
		</div>
<?php		
		echo $after_widget;
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'title' ]       = isset( $new_instance[ 'title' ] ) ? esc_attr( $new_instance[ 'title' ] ) : '';
		$instance[ 'description' ] = isset( $new_instance[ 'description' ] ) ? esc_textarea( $new_instance[ 'description' ] ) : '';

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 **/
	function form( $instance ) {
		$title       = isset ( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : __( 'Campaign Backers', 'campaignify' );
		$description = isset ( $instance[ 'description' ] ) ? $instance[ 'description' ] : null;
?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'campaignify' ); ?></label>

				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo $title; ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php _e( 'Description:', 'campaignify' ); ?></label>

				<textarea class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" rows="10"><?php echo $description; ?></textarea>
			</p>
		<?php
	}
}