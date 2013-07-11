<div class="wrap">
<h2>Reality Card Importer</h2>
<p>Upload Card CSV and Images below.</p>

<?php global $reality_importer_messages; ?>

<?php if ( !empty( $reality_importer_messages['messages'] ) ) : ?>
<div class="messages">
	<?php if ( $reality_importer_messages['success'] ) : ?>
		<div class="message">Successfully added <?php echo $reality_importer_messages['added_cards_count']; ?> Cards!</div>
	<?php endif; ?>
	<?php if ( isset( $reality_importer_messages['messages'] ) && is_array( $reality_importer_messages['messages'] ) ) : ?>
		<?php foreach( $reality_importer_messages['messages'] as $message ) : ?>
			<div class="message"><?php echo $message; ?></div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ( $reality_importer_messages['unfound_images_count'] != 0 ) : ?>
		<div class="message">
			<p>Could not find <?php echo $reality_importer_messages['unfound_images_count']; ?> images:
				<ol>
					<?php foreach ( $reality_importer_messages['unfound_images'] as $image ) : ?>
						<li><?php echo $image; ?></li>
					<?php endforeach; ?>
				</ol>
			</p>
		</div>
	<?php endif; ?>
</div>
<?php endif; ?>
<hr>
<h3>Important Note</h3>
<p>The .CSV file must be in a very specific format.  The headers <strong>MUST</strong> be named <strong>EXACTLY</strong> as follows.</p>
<style>
	th { background: #eee; }
	table { margin: 1em 0; }

</style>
<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Set</th>
			<th>Set member #</th>
			<th>Set Size</th>
			<th>Collectible</th>
			<th>Description</th>
			<th>Img filename</th>
			<th>Gerund/Prefix</th>
			<th>Face ("playable side")</th>
			<th>Card Type</th>
			<th>Face Category</th>
			<th>Outs</th>
			<th>R, L or S template</th>
			<th>Special Out</th>
			<th>Pts AAA</th>
			<th>Pts AA</th>
			<th>Pts A</th>
			<th>Face Description (optional except for Maker cards)</th>
			<th>Powerup (optional)</th>
		</tr>
	</thead>
</table>
<p>You may copy and paste this table into your spreadsheet application</p>
<h3>Other Limitations</h3>
<ul>
	<li>Image names must have no spaces, only characters and underscores.</li>
	<li>Special characters like "é" or quotation signs like "˝" may not translate properly</li>
</ul>
<h3>Please be patient with the upload!</h3>
<p>If you are uploading a lot of images, it may take a while for the site to load once you hit "Import"</p>
<hr>
<form id="reality_card_import_form" method="POST" enctype="multipart/form-data">

<fieldset>
<label>Card CSV</label>
<input type="file" name="reality_import_cards_csv" id="" required>
</fieldset>
<fieldset>
<label>Card Images</label>
<input type="file" name="reality_import_cards_images[]" multiple accept="image/*">
</fieldset>
<?php wp_nonce_field( 'reality_import_cards', '_wp_reality_import_cards_nonce' ); ?>
<input type="submit" value="Import Cards">
</form>

</div>