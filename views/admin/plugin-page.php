<?php
/**
 * Plugin Main Page markup
 */
?>

<div class="product-cards-customiser">
	<div class="product-cards-customiser-inner">

		<h1><?php echo esc_html__('Product Card Builder', 'product-cards-builder-for-woocommerce') ?></h1>

		<div class="wrapping-block">
			<div id="template-editor" class="modal">
				<div class="template-editor-inner">
					<h3><?php echo esc_html__('Editor', 'product-cards-builder-for-woocommerce') ?></h3>
					<textarea name="template-data" id="template-data"><?php echo wp_kses( stripslashes( get_option( 'pcbw_template_shortcode', '' ) ) ) ?></textarea>

					<h2 class="activate-template-wrapper">
						<button type="button" id="save-template" disabled><?php echo esc_html__('Save Template', 'product-cards-builder-for-woocommerce') ?></button>
						
						<span><?php echo esc_html__('Activate Template', 'product-cards-builder-for-woocommerce') ?></span>
						<div id="activate-template"<?php echo $activate_template === 'yes' ? ' class="active"' : '' ?>>
							<input type="hidden" name="activate-template" value="<?php echo esc_attr( $activate_template ) ?>">
						</div>
					</h2>
				</div>
			</div>

			<div class="preview-wrapper">
				<h3><?php echo esc_html__('Preview', 'product-cards-builder-for-woocommerce') ?>
					<select name="pcbw_preview_product">
						<option><?php echo esc_html__('Choose a product', 'product-cards-builder-for-woocommerce') ?></option>
						<?php
						foreach ( $products as $product ) { 
						?>
							<option value="<?php echo esc_attr( $product['id'] ) ?>"<?php echo $preview_product_id === $product['id'] ? ' selected' : '' ?>><?php echo esc_html( $product['label'] ) ?></option>
						<?php } ?>
					</select>
				</h3>
				<div class="preview-block"></div>
			</div>
			
		</div>

		<h2><?php echo esc_html__('Documentation for usage', 'product-cards-builder-for-woocommerce') ?></h2>

		<div class="documentation">
			<p><?php echo esc_html__('This plugin is intended to help you to build your custom product card layout and design.', 'product-cards-builder-for-woocommerce') ?></p>
			<p><span class="error"><?php echo esc_html__('KEEP IN MIND:', 'product-cards-builder-for-woocommerce') ?> </span><?php echo esc_html__('The content in the preview block may not look exactly the same as on the front pages of the website. Each theme has its own styles that may cause the styling to look different from the product card in the preview block. In case this happens, you can use the "style" and "hover" attributes that can be applied to each shortcode to fix style issues.', 'product-cards-builder-for-woocommerce') ?></p>

			<table style="line-height: 1.7em">
				<thead>
					<tr>
						<th><?php echo esc_html__( 'Shortcode Name', 'product-cards-builder-for-woocommerce' ) ?></th>
						<th><?php echo esc_html__( 'Attributes', 'product-cards-builder-for-woocommerce') ?></th>
						<th><?php echo esc_html__( 'Can Contain Content', 'product-cards-builder-for-woocommerce' ) ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>[pcbw_add_to_cart]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>view_cart</code> (boolean),
							<code>view_cart_style</code> (CSS style) <?php echo sprintf('%s <code>%s</code> %s', esc_html__('or', 'pcwb'), esc_html__('inherit', 'pcwb'), esc_html__('to inherit the "Add to cart" button styling', 'product-cards-builder-for-woocommerce')) ?>,
							<code>view_cart_hover</code> (CSS style) <?php echo sprintf('%s <code>%s</code> %s', esc_html__('or', 'pcwb'), esc_html__('inherit', 'pcwb'), esc_html__('to inherit the "Add to cart" button hover styling', 'product-cards-builder-for-woocommerce')) ?>
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_attributes]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_container]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>product_link</code> (boolean) - <?php echo esc_html__('use this attribute in case of you want this block to be a product link', 'product-cards-builder-for-woocommerce') ?>
						</td>
						<td>Yes</td>
					</tr>
					<tr>
						<td><code>[pcbw_price]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
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
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_product_image]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style)
						</td>
						<td>No</td>
					</tr>
					<tr>
						<td><code>[pcbw_rating]</code></td>
						<td>
							<code>id</code> (Unique CSS selector), 
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
							<code>id</code> (Unique CSS selector), 
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
							<code>id</code> (Unique CSS selector), 
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
							<code>id</code> (Unique CSS selector), 
							<code>style</code> (CSS style), 
							<code>hover</code> (CSS style), 
							<code>product_link</code> (boolean) - <?php echo esc_html__('use this attribute in case of you want this block to be a product link', 'product-cards-builder-for-woocommerce') ?>
						</td>
						<td>Yes</td>
					</tr>
				</tbody>
			</table>
		</div>
</div>

