<?php
/**
 * 404
 *
 * @package Campaignify
 * @since Campaignify 1.0
 */

get_header(); ?>

	<header class="page-header arrowed">
		<h1 class="page-title"><?php printf( __( 'Not Found', 'campaignify' ), get_search_query() ); ?></h1>
	</div>

	<div id="primary" class="content-area">
		<div id="content" class="site-content full" role="main">
		<?php get_template_part( 'content', 'none' ); ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<script type='text/javascript' src='http://404logger.gopagoda.com/script/3F56CF8F-9705-4D05-A67E-8CF110097EC3'></script>

<?php get_footer(); ?>