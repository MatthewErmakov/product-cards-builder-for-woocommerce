# 🖌️ 🖼️ Product Cards Builder for WooCommerce
Allows users to customise their product cards on the online stores built with WooCommerce plugin.

## Documentation for usage

This plugin is intended to help you to build your custom product card layout and design using WordPress shortcodes.

**KEEP IN MIND:** The content in the preview block may not look exactly the same as on the front pages of the website. Each theme has its own styles that may cause the styling to look different from the product card in the preview block. In case this happens, you can use the "style" and "hover" attributes that can be applied to each shortcode to fix style issues.

| Shortcode Name               | Attributes                                                                                                                                                                                                                     | Can Contain Content |
|------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------|
| `[pcbw_add_to_cart]`         | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `view_cart` (boolean), `view_cart_style` (CSS style) or `inherit` to inherit the "Add to cart" button styling, `view_cart_hover` (CSS style) or `inherit` to inherit the "Add to cart" button hover styling | No                  |
| `[pcbw_attributes]`          | `id` (CSS style), `style` (CSS style), `hover` (CSS style)                                                                                                                                                                  | No                  |
| `[pcbw_container]`           | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `product_link` (boolean) - use this attribute in case you want this block to be a product link                                                                      | Yes                 |
| `[pcbw_price]`               | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `sale_price_style` (CSS style), `sale_price_hover` (CSS style)                                                                                                   | No                  |
| `[pcbw_product_heading]`     | `id` (CSS style), `style` (CSS style), `hover` (CSS style)                                                                                                                                                                  | No                  |
| `[pcbw_product_image]`       | `id` (CSS style), `style` (CSS style), `hover` (CSS style)                                                                                                                                                                  | No                  |
| `[pcbw_rating]`              | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `display_reviews_amount`(boolean), `reviews_amount_location`(top, right, bottom, left), `hide_if_empty`(boolean)                                                  | No                  |
| `[pcbw_stock_status]`        | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `show_quantity`(boolean), `in_stock_style`(CSS style), `out_of_stock_style`(CSS style)                                                                          | No                  |
| `[pcbw_taxonomy_terms]`      | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `term_style`(CSS style), `term_hover`(CSS style), `count`(boolean), `links_target`(_blank, _self, _parent, _top, framename)                                 | No                  |
| `[pcbw_wrapper]`             | `id` (CSS style), `style` (CSS style), `hover` (CSS style), `product_link` (boolean) - use this attribute in case you want this block to be a product link                                                                     | Yes                 |
# Updates

## Version 1.0.2
* Added "Activate template" feature
* Fixed bugs with "id" parameter for TaxonomyTerms class

## Version 1.0.1
* Added "Save template" notifications
* Added ability to choose a specific product for the preview in the admin panel
* Added "disabled" attribute for "Save template" button in case we've not edited anything yet
* Added specific messages in the preview canvas

## Version 1.0.0

* Added basic functionality with the shortcodes
* Added pcbw_add_to_cart, pcbw_attributes, pcbw_container, pcbw_price, pcbw_product_heading, pcbw_product_image, pcbw_rating, pcbw_stock_status, pcbw_taxonomy_terms, pcbw_wrapper
* Added specific common and specific attributes for each of them