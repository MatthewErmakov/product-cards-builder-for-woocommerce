<?php
/**
 * Plugin Main Page markup
 * 
 * FIXME: ADD TEXTDOMAINS HERE
 */
?>

<div class="product-cards-customiser">
	<div class="product-cards-customiser-inner">

		<h1><?php echo __('Product Card Builder', 'pcbw') ?></h1>

		<div class="wrapping-block">
			<div id="template-editor" class="modal">
				<div class="template-editor-inner">
					<h3><?php echo __('Editor', 'pcbw') ?></h3>
					<textarea name="template-data" id="template-data"><?php echo stripslashes( get_option( 'pcbw_template_shortcode', '' ) ) ?></textarea>

					<h2 class="activate-template-wrapper">
						<button type="button" id="save-template" disabled><?php echo __('Save Template', 'pcbw') ?></button>
						
						<span><?php echo __('Activate Template', 'pcbw') ?></span>
						<div id="activate-template"<?php echo $activate_template === 'yes' ? ' class="active"' : '' ?>>
							<input type="hidden" name="activate-template" value="<?php echo $activate_template ?>">
						</div>
					</h2>
				</div>
			</div>

			<div class="preview-wrapper">
				<h3><?php echo __('Preview', 'pcbw') ?>
					<select name="pcbw_preview_product">
						<option><?php echo __('Choose a product', 'pcbw') ?></option>
						<?php
						foreach ( $products as $product ) { 
						?>
							<option value="<?php echo $product['id'] ?>"<?php echo $preview_product_id === $product['id'] ? ' selected' : '' ?>><?php echo $product['label'] ?></option>
						<?php } ?>
					</select>
				</h3>
				<div class="preview-block"></div>
			</div>
			
		</div>

		<h2><?php echo __('Documentation for usage', 'pcbw') ?></h2>

		<div class="documentation">
			<p><?php echo __('This plugin is intended to help you to build your custom product card layout and design.', 'pcbw') ?></p>
			<p><span class="error"><?php echo __('KEEP IN MIND:', 'pcbw') ?> </span><?php echo __('The content in the preview block may not look exactly the same as on the front pages of the website. Each theme has its own styles that may cause the styling to look different from the product card in the preview block. In case this happens, you can use the "style" and "hover" attributes that can be applied to each shortcode to fix style issues.', 'pcbw') ?></p>

			<table style="line-height: 1.7em">
				<thead>
					<tr>
						<th><?php echo __( 'Shortcode Name', 'pcbw' ) ?></th>
						<th><?php echo __( 'Attributes', 'pcbw') ?></th>
						<th><?php echo __( 'Can Contain Content', 'pcbw' ) ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>[pcbw_add_to_cart]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>view_cart</code> (boolean),
							<code>view_cart_style</code> (CSS style) <?php echo sprintf('%s <code>%s</code> %s', __('or', 'pcwb'), __('inherit', 'pcwb'), __('to inherit the "Add to cart" button styling', 'pcbw')) ?>,
							<code>view_cart_hover</code> (CSS style) <?php echo sprintf('%s <code>%s</code> %s', __('or', 'pcwb'), __('inherit', 'pcwb'), __('to inherit the "Add to cart" button hover styling', 'pcbw')) ?>
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_attributes]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_container]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>product_link</code> (boolean) - <?php echo __('use this attribute in case of you want this block to be a product link', 'pcbw') ?>
						</td>
						<td>Yes</td>
					</tr>
					<tr>
						<td><code>[pcbw_price]</code></td>
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
						<td><code>[pcbw_product_heading]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_product_image]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_rating]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>display_reviews_amount</code>(boolean),
							<code>reviews_amount_location</code>(top, right, bottom, left),
							<code>hide_if_empty</code>(boolean)
						</td>
						<td>No</td>
					</tr>
					<tr>
					<td><code>[pcbw_stock_status]</code></td>
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
						<td><code>[pcbw_taxonomy_terms]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>term_style</code> (CSS style), 
							<code>term_hover</code> (CSS style), 
							<code>count</code> (boolean), 
							<code>links_target</code> (_blank, _self, _parent, _top, framename)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_wrapper]</code></td>
						<td>
							<code>id</code> (CSS style), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>product_link</code> (boolean) - <?php echo __('use this attribute in case of you want this block to be a product link', 'pcbw') ?>
						</td>
						<td>Yes</td>
					</tr>
				</tbody>
			</table>
		</div>
</div>

