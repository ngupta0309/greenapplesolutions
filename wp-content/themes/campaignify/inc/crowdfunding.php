<?php
/**
 * Crowdfunding stuff.
 *
 * @package Campaignify
 * @since Campaignify 1.0
 */

/**
 * Featured Campaign
 *
 * If the static campaign page is setup, but no campaign is defined
 * in the Customizer, things will break. This chooses one for them.
 *
 * @since Campaignify 1.0
 *
 * @return void
 */
function campaignify_get_featured_campaign() {
	if ( false === ( $campaign_id = get_transient( 'campaignify_featured_campaign' ) ) ) {
		$campaigns = get_posts( array(
			'post_type'   => 'download',
			'fields'      => 'ids',
			'numberposts' => 1,
			'meta_query'  => array(
				array(
					'key'   => '_campaign_featured',
					'value' => 1
				)
			)
		) );

		if ( ! empty( $campaigns ) ) {
			$campaign_id = current( $campaigns );
		} else {
			$campaigns = get_posts( array(
				'post_type'   => 'download',
				'fields'      => 'ids',
				'numberposts' => 1
			) );

			$campaign_id = current( $campaigns );
		}

		set_transient( 'campaignify_featured_campaign', $campaign_id, 72 * HOUR_IN_SECONDS );
	}

	return $campaign_id;
}

/**
 * Clear the featured campaign transient when a campaign is savd.
 *
 * @since Campaignify 1.0
 *
 * @param int $post_id Download (Post) ID
 * @return void
 */
function campaignify_featured_campaign_clear_transient( $post_id) {
	global $post;

	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) 
		return $post_id;

	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
		return $post_id;

	if ( ! current_user_can( 'edit_pages', $post_id ) )
		return $post_id;

	delete_transient( 'campaignify_featured_campaign' );
}
add_action( 'save_post', 'campaignify_featured_campaign_clear_transient' );

/**
 * Clear cart shim.
 *
 * Although the Crowdfunding plugins clear cart on campaign
 * pages, this won't work 100% with campaignify since there 
 * could be a static campaign on the homepage. 
 *
 * @since Campaignify 1.0
 *
 * @return void
 */
function campaignify_clear_cart() {
	if ( campaignify_is_crowdfunding() && campaignify_is_campaign_page() )
		edd_empty_cart();
}
add_action( 'template_redirect', 'campaignify_clear_cart' );

/**
 * Figure out if a specific widget is being used on the
 * campaign page.
 *
 * @since Campaignify 1.0
 *
 * @param string $widget_id The base ID of the widget to check for.
 * @return boolean $has_widget If the widget is in use or not.
 */
function campaignify_is_using_widget( $widget_id ) {
	$widgets    = campaignify_campaign_widgets();
	$has_widget = false;
	
	if ( empty( $widgets ) )
		return false;

	foreach ( $widgets as $widget ) {
		if ( $widget[ 'classname' ] == $widget_id ) {
			$has_widget = true;
			break;
		}
	}

	return $has_widget;
}

/**
 * Get all widgets used on the Campaign page.
 *
 * @since Campaignify 1.0
 *
 * @return array $_widgets An array of active widgets
 */
function campaignify_campaign_widgets() {
	global $wp_registered_sidebars, $wp_registered_widgets;

	$index            = 'widget-area-front-page';
	$sidebars_widgets = wp_get_sidebars_widgets();
	$_widgets         = array();

	if ( empty( $sidebars_widgets ) || empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
		return $_widgets;

	foreach ( (array) $sidebars_widgets[$index] as $id ) {
		$_widgets[] = $wp_registered_widgets[$id];
	}

	return $_widgets;
}

/**
 * If the current page is a campaign page.
 *
 * This can either be a singlular download, or the page template
 *
 * @since Campaignify 1.0
 *
 * @return boolean If the current page is a campaign listing or not.
 */
function campaignify_is_campaign_page() {
	if ( is_singular( 'download' ) )
		return true;

	if ( is_page_template( 'page-templates/campaignify.php' ) )
		return true;

	return false;
}

function fundify_reverse_purchase_button_location() {
	remove_action( 'edd_purchase_link_top', 'atcf_purchase_variable_pricing' );
	add_action( 'edd_purchase_link_end', 'atcf_purchase_variable_pricing' );
}
add_action( 'init', 'fundify_reverse_purchase_button_location', 12 );

/**
 * Contribute now list options
 *
 * @since Campaignify 1.0
 *
 * @return void
 */
function campaignify_campaign_contribute_options( $prices, $type, $download_id ) {
	$campaign = atcf_get_campaign( $download_id );
?>
	<div class="edd_price_options <?php echo $campaign->is_active() ? 'active' : 'expired'; ?>">
		<ul>
			<?php foreach ( $prices as $key => $price ) : ?>
				<?php
					$amount  = $price[ 'amount' ];
					$limit   = isset ( $price[ 'limit' ] ) ? $price[ 'limit' ] : '';
					$bought  = isset ( $price[ 'bought' ] ) ? $price[ 'bought' ] : 0;
					$allgone = false;

					if ( $bought == absint( $limit ) && '' != $limit )
						$allgone = true;

					if ( edd_use_taxes() && edd_taxes_on_prices() )
						$amount += edd_calculate_tax( $amount );
				?>
				<li <?php if ( $allgone ) : ?>class="inactive"<?php endif; ?> data-price="<?php echo edd_sanitize_amount( edd_format_amount( $amount ) ); ?>">
					<div class="clear">
						<h3><label for="<?php echo esc_attr( 'edd_price_option_' . $download_id . '_' . $key ); ?>"><?php
							if ( $campaign->is_active() )
								if ( ! $allgone )
									printf(
										'<input type="radio" name="edd_options[price_id][]" id="%1$s" class="%2$s edd_price_options_input" value="%3$s"/>',
										esc_attr( 'edd_price_option_' . $download_id . '_' . $key ),
										esc_attr( 'edd_price_option_' . $download_id ),
										esc_attr( $key )
									);
						?> <?php echo edd_currency_filter( edd_format_amount( $amount ) ); ?></label></h3>

						<?php if ( '' != $limit ) : ?>
						<div class="backers">
							<?php if ( '' != $limit && ! $allgone ) : ?>
								<small class="limit"><?php printf( __( 'Limit of %d &mdash; %d remaining', 'fundify' ), $limit, $limit - $bought ); ?></small>
							<?php elseif ( $allgone ) : ?>
								<small class="gone"><?php _e( 'All gone!', 'fundify' ); ?></small>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
					<?php echo wpautop( esc_html( $price[ 'name' ] ) ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div><!--end .edd_price_options-->
<?php
}
add_action( 'atcf_campaign_contribute_options', 'campaignify_campaign_contribute_options', 10, 3 );

/**
 * Custom price field
 *
 * @since Campaignify 1.0
 *
 * @return void
 */
function campaignify_campaign_contribute_custom_price() {
	global $edd_options;
?>
	<h2><?php echo apply_filters( 'campaignify_pledge_custom_title', __( 'Enter your pledge amount', 'campaignify' ) ); ?></h2>

	<p class="campaignify_custom_price_wrap">
	<?php if ( ! isset( $edd_options['currency_position'] ) || $edd_options['currency_position'] == 'before' ) : ?>
		<span class="currency left"><?php echo edd_currency_filter( '' ); ?></span>
		<input type="text" name="campaignify_custom_price" id="campaignify_custom_price" value="" class="left" />
	<?php else : ?>
		<input type="text" name="campaignify_custom_price" id="campaignify_custom_price" value="" class="right" />
		<span class="currency right"><?php echo edd_currency_filter( '' ); ?></span>
	<?php endif; ?>
	</p>
<?php
}
add_action( 'edd_purchase_link_top', 'campaignify_campaign_contribute_custom_price', 5 );

/**
 * Expired campaign shim.
 *
 * When a campaign is inactive, we display the inactive pledge amounts,
 * but the lack of form around them messes with the styling a bit, and we
 * lose our header. This fixes that. 
 *
 * @since Campaignify 1.0
 *
 * @param object $campaign The current campaign.
 * @return void
 */
function campaignify_contribute_modal_top_expired( $campaign ) {
	if ( $campaign->is_active() )
		return;
?>
	<div class="edd_download_purchase_form">
		<h2><?php printf( __( 'This %s has ended. No more pledges can be made.', 'campaignify' ), edd_get_label_singular() ); ?></h2>
<?php
}
add_action( 'campaignify_contribute_modal_top', 'campaignify_contribute_modal_top_expired' );

/**
 * Expired campaign shim.
 *
 * @since Campaignify 1.0
 *
 * @param object $campaign The current campaign.
 * @return void
 */
function campaignify_contribute_modal_bottom_expired( $campaign ) {
	if ( $campaign->is_active() )
		return;
?>
	</div>
<?php
}
add_action( 'campaignify_contribute_modal_bottom', 'campaignify_contribute_modal_bottom_expired' );

/**
 * Custom pledge level fix.
 *
 * If there is a custom price, figure out the difference
 * between that, and the price level they have chosen. Store
 * the differene in the cart item meta, so it can be added to
 * the total in the future.
 *
 * @since Campaignify 1.0
 *
 * @param array $cart_item The current cart item to be added.
 * @return array $cart_item The modified cart item.
 */
function campaignify_edd_add_to_cart_item( $cart_item ) {
	if ( isset ( $_POST[ 'post_data' ] ) ) {
		$post_data = array();
		parse_str( $_POST[ 'post_data' ], $post_data );

		$custom_price = $post_data[ 'campaignify_custom_price' ];
	} else {
		$custom_price = $_POST[ 'campaignify_custom_price' ];
	}

	$custom_price = edd_sanitize_amount( $custom_price );
	$custom_price = absint( $custom_price );

	$price        = edd_get_cart_item_price( $cart_item[ 'id' ], $cart_item[ 'options' ] );

	if ( $custom_price > $price ) {
		$cart_item[ 'options' ][ 'atcf_extra_price' ] = $custom_price - $price;
	
		return $cart_item;
	}

	return $cart_item;
}
add_filter( 'edd_add_to_cart_item', 'campaignify_edd_add_to_cart_item' );
add_filter( 'edd_ajax_pre_cart_item_template', 'campaignify_edd_add_to_cart_item' );

/**
 * Calculate the cart item total based on the existence of
 * an additional pledge amount.
 *
 * @since Campaignify 1.0
 *
 * @param int $price The current price.
 * @param int $item_id The ID of the cart item.
 * @param array $options Item meta for the current cart item.
 * @return int $price The updated price.
 */
function campaignify_edd_cart_item_price( $price, $item_id, $options = array() ) {
	if ( isset ( $options[ 'atcf_extra_price' ] ) ) {
		$price = $price + $options[ 'atcf_extra_price' ];
	}

	return $price;
}
add_filter( 'edd_cart_item_price', 'campaignify_edd_cart_item_price', 10, 3 );