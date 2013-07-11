<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<aside id="secondary" class="widget-area quick-actions" role="complementary">
	<?php if ( is_user_logged_in() ) : ?>
	<ul>
		<?php if ( reality_is_running() ) : ?>
		<li>
			<a href="#" title="Submit a Deal" class="submit-deal">Submit a Deal</a>
			<p>Enter the ID of the Maker card of your deal below.</p>
			<form method="POST" id="reality-submit-deal-begin" action="<?php echo site_url( 'submit' ); ?>">
			
				<input type="text" name="maker_card_id" id="maker_card_id" placeholder="ex. 11006" />
				<input type="hidden" name="reality_action" value="reality_submit_deal_begin" />
				<?php wp_nonce_field( 'reality_submit_deal_begin', '_wpnonce_reality_submit_deal_begin' ); ?>
				<a class="submit-button" onClick="jQuery('form#reality-submit-deal-begin').submit();">Go</a>
			</form>
		</li>
		<?php endif; ?>
		<li><a href="<?php echo site_url( 'deals' ); ?>" title="Browse Deals" class="browse-deals">Browse Deals</a></li>
		<li class="active">
			<a href="#" title="Look Up A Card" class="card-lookup">Look Up A Card</a>
			<p>Carry your cards with you at all times.</p>
			<form method="GET" id="card-lookup-form">	
				<input type="text" name="card" id="card" placeholder="ex. 11006" />
				<a class="submit-button" onClick="jQuery('form#card-lookup-form').submit();">Go</a>
			</form>
			<p>Enter the ID number of the card you want to look up.</p>
		</li>

	</ul>
	<?php endif; ?>
</aside><!-- #secondary -->