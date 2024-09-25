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
					<h3><?php echo __('Editor') ?></h3>
					<textarea name="template-data" id="template-data"><?php echo stripslashes( get_option( 'pccw_template_shortcode', '' ) ) ?></textarea>

					<button type="button" id="save-template"><?php echo __('Save Template') ?></button>
				</div>
			</div>

			<div class="preview-wrapper">
				<h3><?php echo __('Preview') ?></h3>
				<div class="preview-block"></div>
			</div>
			
		</div>

	</div>
</div>

