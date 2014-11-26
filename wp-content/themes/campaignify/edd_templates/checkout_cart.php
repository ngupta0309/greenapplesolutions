<?php global $post; ?>
<table id="edd_checkout_cart" <?php if ( edd_is_ajax_enabled() ) { echo 'class="ajaxed"'; } ?>>
	<thead>
		<tr class="edd_cart_header_row">
			<?php do_action( 'edd_checkout_table_header_first' ); ?>
			<th class="edd_cart_item_name"><?php echo esc_attr( edd_get_label_singular() ); ?></th>
			<th class="edd_cart_item_price"><?php _e( 'Pledge Amount', 'campaignify' ); ?></th>
			<th class="edd_cart_actions">&nbsp;</th>
			<?php do_action( 'edd_checkout_table_header_last' ); ?>
		</tr>
	</thead>
	<tbody>
		<?php $cart_items = edd_get_cart_contents(); ?>
		<?php if ( $cart_items ) : ?>
			<?php do_action( 'edd_cart_items_before' ); ?>
			<?php foreach ( $cart_items as $key => $item ) : ?>
				<tr class="edd_cart_item" id="edd_cart_item_<?php echo esc_attr( $item['id'] ); ?>">
					<?php do_action( 'edd_checkout_table_body_first', $item['id'] ); ?>
					<td class="edd_cart_item_name">
						<span class="edd_checkout_cart_item_title"><?php echo esc_attr( get_the_title( $item[ 'id' ] ) ); ?></span>

						<span class="edd_checkout_cart_item_description"><?php
							$variable_pricing = edd_has_variable_prices( $item['id'] );
							if ( !empty( $item['options'] ) ) {
								echo edd_get_price_name( $item['id'], $item['options'] );
							}
						?></span>
					</td>
					<td class="edd_cart_item_price"><?php echo edd_cart_item_price( $item['id'], $item['options'] ); ?></td>
					<td class="edd_cart_actions"><a href="<?php echo esc_url( edd_remove_item_url( $key, $post ) ); ?>"><i class="icon-cancel"></i></a></td>
					<?php do_action( 'edd_checkout_table_body_last', $item ); ?>
				</tr>
			<?php endforeach; ?>
			<!-- Show any cart fees, both positive and negative fees -->
			<?php if( edd_cart_has_fees() ) : ?>
				<?php foreach( edd_get_cart_fees() as $fee_id => $fee ) : ?>
					<tr class="edd_cart_fee" id="edd_cart_fee_<?php echo $fee_id; ?>">
						<td class="edd_cart_fee_label"><?php echo esc_html( $fee['label'] ); ?></td>
						<td class="edd_cart_fee_amount"><?php echo esc_html( edd_currency_filter( edd_format_amount( $fee['amount'] ) ) ); ?></td>
						<td></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php do_action( 'edd_cart_items_after' ); ?>
		<?php else: ?>
			<tr class="edd_cart_item">
				<td colspan="3"  class="edd_cart_item_empty"><?php do_action( 'edd_empty_cart' ); ?></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<?php if( edd_use_taxes() ) : ?>
		<tr class="edd_cart_footer_row edd_cart_subtotal_row"<?php if ( !( edd_is_cart_taxed() ) ) echo ' style="display:none;"'; ?>>
			<?php do_action( 'edd_checkout_table_subtotal_first' ); ?>
			<th colspan="3" class="edd_cart_subtotal">
				<?php _e( 'Subtotal', 'campaignify' ); ?>:&nbsp;<span class="edd_cart_subtotal"><?php echo edd_cart_subtotal(); ?></span>
			</th>
			<?php do_action( 'edd_checkout_table_subtotal_last' ); ?>
		</tr>
			<?php if ( ! edd_prices_show_tax_on_checkout() ) : ?>

			<tr class="edd_cart_footer_row edd_cart_tax_row"<?php if( edd_local_taxes_only() && ! edd_local_tax_opted_in() ) echo ' style="display:none;"'; ?>>
				<?php do_action( 'edd_checkout_table_tax_first' ); ?>
				<th colspan="3" class="edd_cart_tax">
					<?php _e( 'Tax', 'campaignify' ); ?>:&nbsp;<span class="edd_cart_tax_amount" data-tax="<?php echo edd_get_cart_tax( false ); ?>"><?php echo esc_html( edd_cart_tax() ); ?></span>
				</th>
				<?php do_action( 'edd_checkout_table_tax_last' ); ?>
			</tr>

			<?php endif; ?>

		<?php endif; ?>
		<tr class="edd_cart_footer_row edd_cart_discount_row" <?php if( ! edd_cart_has_discounts() )  echo ' style="display:none;"'; ?>>
			<?php do_action( 'edd_checkout_table_discount_first' ); ?>
			<th colspan="3" class="edd_cart_discount">
				<?php edd_cart_discounts_html(); ?>
			</th>
			<?php do_action( 'edd_checkout_table_discount_last' ); ?>
		</tr>

		<tr class="edd_cart_footer_row">
			<?php do_action( 'edd_checkout_table_footer_first' ); ?>
			<th colspan="3" class="edd_cart_total"><?php _e( 'Total', 'campaignify' ); ?>: <span class="edd_cart_amount" data-subtotal="<?php echo edd_get_cart_total(); ?>" data-total="<?php echo edd_get_cart_total(); ?>"><?php edd_cart_total(); ?></span></th>
			<?php do_action( 'edd_checkout_table_footer_last' ); ?>
		</tr>
	</tfoot>
</table>