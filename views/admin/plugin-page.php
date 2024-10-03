<?php
/**
 * Plugin Main Page markup
 * 
 * FIXME: ADD TEXTDOMAINS HERE
 */
?>

<div class="product-cards-customiser">
	<div class="product-cards-customiser-inner">

		<h1><?php echo __('Product Card Template Customiser') ?></h1>

		<div class="wrapping-block">
			<div id="template-editor" class="modal">
				<div class="template-editor-inner">
					<h3><?php echo __('Editor', 'pccw') ?></h3>
					<textarea name="template-data" id="template-data"><?php echo stripslashes( get_option( 'pccw_template_shortcode', '' ) ) ?></textarea>

					<button type="button" id="save-template" disabled><?php echo __('Save Template', 'pccw') ?></button>
				</div>
			</div>

			<div class="preview-wrapper">
				<h3><?php echo __('Preview', 'pccw') ?>
					<select name="pccw_preview_product">
						<option><?php echo __('Choose a product', 'pccw') ?></option>
						<?php 
						$preview_product_id = (int)get_option( 'pccw_product_to_preview', false );
						foreach ( $products as $product ) { 
						?>
							<option value="<?php echo $product['id'] ?>"<?php echo $preview_product_id === $product['id'] ? ' selected' : '' ?>><?php echo $product['label'] ?></option>
						<?php } ?>
					</select>
				</h3>
				<div class="preview-block"></div>
			</div>
			
		</div>

		<h2><?php echo __('Documentation usage', 'pccw') ?></h2>

		<div class="documentation">
		<table>
			<thead>
				<tr>
					<th>Shortcode Name</th>
					<th>Attributes</th>
					<th>Can Contain Content</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><code>[pccw_add_to_cart]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style), 
						<code>multiple</code> (boolean), 
						<code>view_cart</code> (boolean)
					</td>
					<td>No</td>
				</tr>
				<tr>
					<td><code>[pccw_attributes]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style), 
						<code>display_attribute_name</code> (boolean)
					</td>
					<td>No</td>
				</tr>
				<tr>
					<td><code>[pccw_container]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style), 
						<code>product_link</code> (boolean)
					</td>
					<td>Yes</td>
				</tr>
				<tr>
					<td><code>[pccw_price]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style), 
						<code>sale_price_style</code> (CSS style), 
						<code>sale_price_hover</code> (CSS style)
					</td>
					<td>No</td>
				</tr>
				<tr>
					<td><code>[pccw_product_heading]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style)
					</td>
					<td>No</td>
				</tr>
				<tr>
					<td><code>[pccw_product_image]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style)
					</td>
					<td>No</td>
				</tr>
				<tr>
					<td><code>[pccw_rating]</code></td>
					<td>
						<code>id</code> (CSS style), 
						<code>style</code> (CSS style), 
						<code>hover</code> (CSS style), 
						<code>display_reviews_amount</code>(boolean),
						<code>reviews_amount_location</code>(CSS style),
						<code>hide_if_empty</code>(boolean)
					</td>
					<td>No</td>
				</tr>
				<tr>
			<td><code>[pccw_stock_status]</code></td>
				<td>
					<code>id</code> (CSS style), 
					<code>style</code> (CSS style), 
					<code>hover</code> (CSS style), 
					<code>show_quantity</code> (boolean), 
					<code>in_stock_style</code> (CSS style), 
					<code>out_of_stock_style</code> (CSS style)
				</td>
				<td>No</td>
			</tr>
			<tr>
				<td><code>[pccw_taxonomy_terms]</code></td>
				<td>
					<code>id</code> (CSS style), 
					<code>style</code> (CSS style), 
					<code>hover</code> (CSS style), 
					<code>term_style</code> (CSS style), 
					<code>term_hover</code> (CSS style), 
					<code>count</code> (boolean), 
					<code>links_target</code> (CSS style)
				</td>
				<td>No</td>
			</tr>
			<tr>
				<td><code>[pccw_wrapper]</code></td>
				<td>
					<code>id</code> (CSS style), 
					<code>style</code> (CSS style), 
					<code>hover</code> (CSS style), 
					<code>product_link</code> (boolean)
				</td>
				<td>Yes</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>

