<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tekniskera.com/
 * @since      1.0.0
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 * @author     Teknisk Era  <info@tekniskera.com>
 */

class Tewms_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tewms_plugin_name    The ID of this plugin.
	 */
	private $tewms_plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tewms_version    The current version of this plugin.
	 */
	private $tewms_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $tewms_plugin_name       The name of the plugin.
	 * @param      string    $tewms_version    The version of this plugin.
	 */
	public function __construct($tewms_plugin_name, $tewms_version)
	{

		$this->tewms_plugin_name = $tewms_plugin_name;
		$this->version = $tewms_version;

	}
	/**
	 *  display on product page
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_display_product_details()
	{
		$tewms_priority = sanitize_text_field(get_option('tewms_enable_dis_radio'));
		$tewms_cat_priority = sanitize_text_field(get_option('tewms_enable_dis_radio'));
		$tewms_enable_pro = sanitize_text_field(get_option('tewms_enable_pro_dis'));
		$tewms_enable_cat = sanitize_text_field(get_option('tewms_enable_category_dis'));
		if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
			$tewms_product_id = get_the_ID();
			$tewms_savedData = get_option('tewms_product_data', array());
			if (!empty($tewms_savedData)) {
				?>
				<h2>
					<?php esc_html_e('Buy more Save more!', 'wholesale-market-suite-for-woocommerce'); ?>
				</h2>
				<table class="wp-list-table widefat striped">
					<tr>
						<th>
							<?php esc_html_e('Product QTY', 'wholesale-market-suite-for-woocommerce'); ?>
						</th>
						<th>
							<?php esc_html_e('Product Discount', 'wholesale-market-suite-for-woocommerce'); ?>
						</th>
					</tr>
					<?php
					$tewms_qt = 1000;
					$tewms_qtt = 1000;
					$tewms_msgg = '';
					foreach ($tewms_savedData as $tewms_NewEntry) {
						$tewms_pid = $tewms_NewEntry['p_id'];
						$tewms_pro_id_array = explode(',', $tewms_pid);
						for ($i = 0; $i < count($tewms_pro_id_array); $i++) {
							if ($tewms_pro_id_array[$i] == $tewms_product_id) {
								$tewms_pro_price = explode(',', $tewms_NewEntry['p_price']);
								$tewms_lowestPrice = min($tewms_pro_price);
								$tewms_highestPrice = max($tewms_pro_price);
								$tewms_range = $tewms_lowestPrice . '-' . $tewms_highestPrice;
								?>
								<tr>
									<td>
										<?php echo esc_html($tewms_NewEntry['p_min_qty']) . ' - ' . esc_html($tewms_NewEntry['p_max_qty']); ?>
									</td>
									<td>
										<?php echo esc_html($tewms_NewEntry['p_discount']); ?>%
									</td>
								</tr>
								<?php
								$tewms_msgg = esc_html($tewms_NewEntry['p_msg']);
								echo '<h5>' . $tewms_msgg . '</h5>';
							}
						}
					}
					echo '</table>';
			}
		} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
			$tewms_product_id = get_the_ID();
			$product = wc_get_product($tewms_product_id);
			$tewms_category_id = $product->get_category_ids();

			$tewms_savedData = get_option('tewms_category_wise_discount', array());
			if (!empty($tewms_savedData)) {
				?>
						<h2>
						<?php esc_html_e('Buy more Save more!', 'wholesale-market-suite-for-woocommerce'); ?>
						</h2>
						<table class="wp-list-table widefat striped">
							<tr>
								<th>
								<?php esc_html_e('Product QTY', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<th>
								<?php esc_html_e('Category-wise Discount', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>

							</tr>
							<?php
							$tewms_qt = 1000;
							$tewms_qtt = 1000;
							$tewms_msgg = '';
							foreach ($tewms_savedData as $tewms_NewEntry) {
								$tewms_cid = "";
								$tewms_cid = $tewms_NewEntry['category_id'];
								$tewms_cat_id_array = explode(',', $tewms_cid);
								for ($i = 0; $i < count($tewms_cat_id_array); $i++) {
									if (in_array($tewms_cat_id_array[$i], $tewms_category_id)) {
										$tewms_minQty = $tewms_NewEntry['min_qty'];
										$tewms_maxQty = $tewms_NewEntry['max_qty'];
										$tewms_range = $tewms_minQty . '-' . $tewms_maxQty;
										?>
										<tr>
											<td>
											<?php
											echo $tewms_range;
											?>
											</td>
											<td>
												<?php
												echo $tewms_NewEntry['category_discount'] .'%'; ?>
											</td>
										</tr>
										<?php
										$tewms_msgg = $tewms_NewEntry['product_category_msg'];
										echo '<h5>' . $tewms_msgg . '</h5>';
									}
								}
							}
							echo '</table>';
			}
		} else {
		}
	}
	/**
	 * Add wholesale entry
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_add_wholesale_entry()
	{
		$tewms_quantity = sanitize_text_field($_POST['wholesale_quantity']);
		$tewms_discount = sanitize_text_field($_POST['wholesale_discount']);
		$tewms_entry = array(
			'quantity' => $tewms_quantity,
			'discount' => $tewms_discount,
		);
		$tewms_entries = get_option('tewms_wholesale_entries', array());
		$tewms_entries[] = array_map('sanitize_text_field', $tewms_entry);
		update_option('tewms_wholesale_entries', $tewms_entries);
		exit;
	}
	/**
	 * function for Save custom fields data
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_save_custom_product_fields($tewms_product_id)
	{
		$tewms_wholesale_price = isset($_POST['tewms_wholesale_price']) ? sanitize_text_field($_POST['tewms_wholesale_price']) : '';
		$tewms_wholesale_price = sanitize_text_field($tewms_wholesale_price);
		update_post_meta($tewms_product_id, 'tewms_wholesale_price', $tewms_wholesale_price);
	}
	/**
	 * function for modify simple product price html
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_simple_product_price_html($tewms_price, $tewms_product)
	{
		$tewms_enable_price = sanitize_text_field(get_option('tewms_enable_sale_priceo'));
		if ($tewms_enable_price == 0) {
			$tewms_original_price = $tewms_product->get_regular_price();
			if (!empty($tewms_original_price)) {
				$tewms_price = wc_price($tewms_original_price);
			}
		} else if ($tewms_enable_price == 1) {
			$tewms_regular_price = $tewms_product->get_regular_price();
			$tewms_wholesale_price = $tewms_product->get_sale_price();
			if (!empty($tewms_wholesale_price)) {
				$tewms_price = '<del>' . wc_price($tewms_regular_price) . '</del> ' . wc_price($tewms_wholesale_price);
			}
		} else if ($tewms_enable_price == -1) {
			$tewms_regular_price = $tewms_product->get_regular_price();
			$tewms_wholesale_price = sanitize_text_field(get_post_meta($tewms_product->get_id(), 'tewms_wholesale_price', true));
			if (!empty($tewms_wholesale_price)) {
				$tewms_price = '<del>' . wc_price($tewms_regular_price) . '</del> ' . wc_price($tewms_wholesale_price);
			}
		} else {
		}
		return $tewms_price;
	}
	/**
	 * function for get product added quantity
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_get_product_added_qty($tewms_product_id)
	{
		$tewms_product_qty = 0;
		$tewms_cart = WC()->cart;
		foreach ($tewms_cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
			if ($tewms_cart_item['product_id'] == $tewms_product_id) {
				$tewms_product_qty = $tewms_cart_item['quantity'];
				break;
			}
		}
		return $tewms_product_qty;
	}
	/**
	 * Add custom fields in product for wholesale price
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_add_custom_product_fields()
	{
		woocommerce_wp_text_input(
			array(
				'id' => 'tewms_wholesale_price',
				'label' => esc_html_e('WholeSale Price', 'wholesale-market-suite-for-woocommerce'),
				'desc_tip' => true
			)
		);
	}
	/**
	 * Save custom fields
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_save_variation_wholesale_price_fields($tewms_variation_id, $tewms_i)
	{
		$tewms_wholesale_price = isset($_POST['_wholesale_price'][$tewms_i]) ? sanitize_text_field($_POST['_wholesale_price'][$tewms_i]) : '';
		if (!empty($tewms_wholesale_price)) {
			update_post_meta($tewms_variation_id, '_wholesale_price', $tewms_wholesale_price);
		} else {
			delete_post_meta($tewms_variation_id, '_wholesale_price');
		}
	}
	/**
	 * Add custom fields to the variation editing screen
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_add_variation_wholesale_price_fields($tewms_loop, $tewms_variation_data, $tewms_variation)
	{
		woocommerce_wp_text_input(
			array(
				'id' => '_wholesale_price[' . $tewms_loop . ']',
				'label' => esc_html__('Wholesale Price', 'wholesale-market-suite-for-woocommerce'),
				$value = sanitize_meta('_wholesale_price', get_post_meta($tewms_variation->ID, '_wholesale_price', true), 'post'),
				'data_type' => 'price',
				'wrapper_class' => 'form-row form-row-first',
			)
		);
	}

	/**
	 * Modify the displayed product amount to show the wholesale amount for variable products
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_variable_product_price($tewms_price, $tewms_variation)
	{
		$tewms_priority = get_option('tewms_enable_dis_radio');
		$tewms_enable_pro = get_option('tewms_enable_pro_dis');
		$tewms_enable_cat = get_option('tewms_enable_category_dis');
		$tewms_enable_price = get_option('tewms_enable_sale_priceo');
		$tewms_sale_price = 0;
		if ($tewms_enable_price == 0) {
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
				$tewms_original_price = $tewms_variation->get_regular_price();
				$product_id = $tewms_variation->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
							$tewms_varitation = 0;
							$tewms_savedData = get_option('tewms_product_data', array());
							$tewms_cpid = $tewms_cart_item['data']->get_id();
							$tewms_cpid_nov = $tewms_cart_item['data']->get_id();
							$tewms_variation = wc_get_product($tewms_cpid);
							$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
							if ($tewms_product_id) {
								$tewms_cpid = $tewms_variation->get_parent_id();
								$tewms_varitation = 1;

							}
							$tewms_productp = $tewms_original_price;
							$tewms_chkqty = 0;
							$tewms_dis_per = "";

							foreach ($tewms_savedData as $tewms_newEntry) {
								$tewms_pid = $tewms_newEntry['p_id'];
								$tewms_pro_id_array = explode(',', $tewms_pid);
								$tewms_maxqty = $tewms_newEntry['p_max_qty'];
								$tewms_minqty = $tewms_newEntry['p_min_qty'];
								$tewms_dis = $tewms_newEntry['p_discount'];
								for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
									$tewms_cqty = $tewms_cart_item['quantity'];
									if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $product_id == $tewms_cpid_nov) {
										if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
											if ($tewms_chkqty < $tewms_maxqty) {
												$tewms_chkqty = $tewms_maxqty;
												$tewms_dis_per = $tewms_dis;
											}
										}
									}
								}
							}
							if ($product_id == $tewms_cpid_nov) {
								if ($tewms_chkqty != 0) {
									if ($tewms_varitation == 1) {
										$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
										$tewms_productp = preg_replace('/,/', '', $tewms_productp);
										$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									} else {
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									}
								} else {
									if ($tewms_varitation == 1) {
										$tewms_sale_price = $tewms_productp;
									} else {
										$tewms_sale_price = $tewms_productp;
									}
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}

			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {

				$tewms_original_price = $tewms_variation->get_regular_price();

				$tewms_categorie_product_id = wp_get_post_terms($tewms_variation->get_parent_id(), 'product_cat', array('fields' => 'ids'));

				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($i = 0; $i < $tewms_cart_items_count; $i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_variation->get_parent_id()) {
								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));
										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];
												if (in_array($tewms_category_id, $tewms_pro_id_array)) {
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}

									}
								}
								if ($tewms_chkqty != 0) {

									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;

								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else {
			}
		} else if ($tewms_enable_price == 1) {
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
				$tewms_original_price = $tewms_variation->get_sale_price();
				$product_id = $tewms_variation->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
							$tewms_varitation = 0;
							$tewms_savedData = get_option('tewms_product_data', array());
							$tewms_cpid = $tewms_cart_item['data']->get_id();
							$tewms_cpid_nov = $tewms_cart_item['data']->get_id();
							$tewms_variation = wc_get_product($tewms_cpid);
							$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
							if ($tewms_product_id) {
								$tewms_cpid = $tewms_variation->get_parent_id();
								$tewms_varitation = 1;
							}
							$tewms_productp = $tewms_original_price;
							$tewms_chkqty = 0;
							$tewms_dis_per = "";
							foreach ($tewms_savedData as $tewms_newEntry) {
								$tewms_pid = $tewms_newEntry['p_id'];
								$tewms_pro_id_array = explode(',', $tewms_pid);
								$tewms_maxqty = $tewms_newEntry['p_max_qty'];
								$tewms_minqty = $tewms_newEntry['p_min_qty'];
								$tewms_dis = $tewms_newEntry['p_discount'];
								for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
									$tewms_cqty = $tewms_cart_item['quantity'];
									if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $product_id == $tewms_cpid_nov) {
										if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
											if ($tewms_chkqty < $tewms_maxqty) {
												$tewms_chkqty = $tewms_maxqty;
												$tewms_dis_per = $tewms_dis;
											}
										}
									}
								}
							}
							if ($product_id == $tewms_cpid_nov) {
								if ($tewms_chkqty != 0) {
									if ($tewms_varitation == 1) {
										$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
										$tewms_productp = preg_replace('/,/', '', $tewms_productp);
										$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									} else {
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									}
								} else {
									if ($tewms_varitation == 1) {
										$tewms_sale_price = $tewms_productp;
									} else {
										$tewms_sale_price = $tewms_productp;
									}
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
				$tewms_original_price = $tewms_variation->get_sale_price();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($i = 0; $i < $tewms_cart_items_count; $i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_variation->get_parent_id()) {
								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));
										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];
												if (in_array($tewms_category_id, $tewms_pro_id_array)) {
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}

									}
								}
								if ($tewms_chkqty != 0) {

									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;

								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else {
			}
		} else if ($tewms_enable_price == -1) {
			$tewms_wholesale_price = get_post_meta($tewms_variation->get_id(), '_wholesale_price', true);
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
				$tewms_original_price = get_post_meta($tewms_variation->get_id(), '_wholesale_price', true);
				$product_id = $tewms_variation->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
							$tewms_varitation = 0;
							$tewms_savedData = get_option('tewms_product_data', array());
							$tewms_cpid = $tewms_cart_item['data']->get_id();
							$tewms_cpid_nov = $tewms_cart_item['data']->get_id();
							$tewms_variation = wc_get_product($tewms_cpid);
							$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
							if ($tewms_product_id) {
								$tewms_cpid = $tewms_variation->get_parent_id();
								$tewms_varitation = 1;
							}
							$tewms_productp = $tewms_original_price;
							$tewms_chkqty = 0;
							$tewms_dis_per = "";
							foreach ($tewms_savedData as $tewms_newEntry) {
								$tewms_pid = $tewms_newEntry['p_id'];
								$tewms_pro_id_array = explode(',', $tewms_pid);
								$tewms_maxqty = $tewms_newEntry['p_max_qty'];
								$tewms_minqty = $tewms_newEntry['p_min_qty'];
								$tewms_dis = $tewms_newEntry['p_discount'];
								for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
									$tewms_cqty = $tewms_cart_item['quantity'];
									if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $product_id == $tewms_cpid_nov) {
										if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
											if ($tewms_chkqty < $tewms_maxqty) {
												$tewms_chkqty = $tewms_maxqty;
												$tewms_dis_per = $tewms_dis;
											}
										}
									}
								}
							}
							if ($product_id == $tewms_cpid_nov) {
								if ($tewms_chkqty != 0) {
									if ($tewms_varitation == 1) {
										$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
										$tewms_productp = preg_replace('/,/', '', $tewms_productp);
										$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									} else {
										$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
									}
								} else {
									if ($tewms_varitation == 1) {
										$tewms_sale_price = $tewms_productp;
									} else {
										$tewms_sale_price = $tewms_productp;
									}
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
				$tewms_original_price = get_post_meta($tewms_variation->get_id(), '_wholesale_price', true);
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($i = 0; $i < $tewms_cart_items_count; $i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_variation->get_parent_id()) {
								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));

										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];
												if (in_array($tewms_category_id, $tewms_pro_id_array)) {
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}

									}
								}
								if ($tewms_chkqty != 0) {

									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;

								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else {
			}
		} else {
		}
		return $tewms_price;
	}
	/**
	 * modify the product price
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_product_price($tewms_price, $tewms_product)
	{
		$tewms_priority = get_option('tewms_enable_dis_radio');
		$tewms_enable_pro = get_option('tewms_enable_pro_dis');
		$tewms_enable_cat = get_option('tewms_enable_category_dis');
		$tewms_price2 = get_option('tewms_enable_sale_priceo');
		$tewms_sale_price = 0;
		if ($tewms_price2 == 0) {
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {

				$tewms_variation = wc_get_product($tewms_product->get_id());
				if ($tewms_variation->get_type() === 'simple') {
					$tewms_original_price = $tewms_product->get_regular_price();
					$product_id = $tewms_product->get_id();
					if (!empty($tewms_original_price)) {
						$tewms_woocommerce = WC();
						$tewms_productp = 0;
						if (!empty($tewms_woocommerce->cart)) {
							foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
								$tewms_varitation = 0;
								$tewms_savedData = get_option('tewms_product_data', array());
								$tewms_cpid = $tewms_cart_item['data']->get_id();
								$tewms_cpid_nov = $tewms_cart_item['data']->get_id();
								$tewms_variation = wc_get_product($tewms_cpid);
								$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
								if ($tewms_product_id) {
									$tewms_cpid = $tewms_variation->get_parent_id();
									$tewms_varitation = 1;
								}
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {

									$tewms_pid = $tewms_newEntry['p_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['p_max_qty'];
									$tewms_minqty = $tewms_newEntry['p_min_qty'];
									$tewms_dis = $tewms_newEntry['p_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_cqty = $tewms_cart_item['quantity'];
										if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $product_id == $tewms_cpid) {
											if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
												if ($tewms_chkqty < $tewms_maxqty) {
													$tewms_chkqty = $tewms_maxqty;
													$tewms_dis_per = $tewms_dis;
												}
											}
										}
									}
								}
								if ($product_id == $tewms_cpid) {
									if ($tewms_chkqty != 0) {
										if ($tewms_varitation == 1) {
											$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
											$tewms_productp = preg_replace('/,/', '', $tewms_productp);
											$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										} else {
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										}
									} else {
										if ($tewms_varitation == 1) {
											$tewms_sale_price = $tewms_productp;
										} else {
											$tewms_sale_price = $tewms_productp;
										}
									}

								}
							}
						}
						$tewms_price = $tewms_sale_price;
					}
				} else {
					$tewms_vobject = wc_get_product($tewms_variation->get_id());
					$tewms_variation_ids = $tewms_vobject->get_children();
					foreach ($tewms_variation_ids as $tewms_variation_id) {
						$tewms_vobject2 = wc_get_product($tewms_variation_id);
						$tewms_wholesale_price = $tewms_vobject2->get_regular_price();
						$tewms_woocommerce = WC();
						$tewms_productp = 0;
						if (!empty($tewms_woocommerce->cart)) {
							foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
								$tewms_varitation = 0;
								$tewms_savedData = get_option('tewms_product_data', array());
								$tewms_cpid = $tewms_cart_item['data']->get_id();
								$tewms_vid = $tewms_cart_item['data']->get_id();
								$tewms_variation = wc_get_product($tewms_cpid);
								$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
								if ($tewms_product_id) {
									$tewms_cpid = $tewms_variation->get_parent_id();
									$tewms_varitation = 1;
								}
								$tewms_productp = $tewms_wholesale_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['p_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['p_max_qty'];
									$tewms_minqty = $tewms_newEntry['p_min_qty'];
									$tewms_dis = $tewms_newEntry['p_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_cqty = $tewms_cart_item['quantity'];
										if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $tewms_variation_id == $tewms_vid) {
											if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
												if ($tewms_chkqty < $tewms_maxqty) {
													$tewms_chkqty = $tewms_maxqty;
													$tewms_dis_per = $tewms_dis;
												}
											}
										}
									}
								}
								if ($tewms_variation_id == $tewms_vid) {
									if ($tewms_chkqty != 0) {
										if ($tewms_varitation == 1) {
											$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
											$tewms_productp = preg_replace('/,/', '', $tewms_productp);
											$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));

											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										} else {
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										}
									} else {
										if ($tewms_varitation == 1) {
											$tewms_sale_price = $tewms_productp;
										} else {
											$tewms_sale_price = $tewms_productp;
										}
									}

								}
							}
						}
						$tewms_price = $tewms_sale_price;
					}
				}
			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
				$tewms_original_price = $tewms_product->get_regular_price();
				$tewms_product_id = $tewms_product->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($tewms_i = 0; $tewms_i < $tewms_cart_items_count; $tewms_i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$tewms_i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_product->get_id()) {
								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_j = 0; $tewms_j < count($tewms_pro_id_array); $tewms_j++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));	
										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];
												if (in_array($tewms_category_id, $tewms_pro_id_array)) {
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}
									}
								}

								if ($tewms_chkqty != 0) {
									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;

								} else {
									$tewms_sale_price = $tewms_productp;

								}
							}

						}
					}
					$tewms_price = $tewms_sale_price;


				}
			} else {
			}
		} else if ($tewms_price2 == 1) {
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
				$tewms_sale_price = $tewms_product->get_sale_price();
				$tewms_product_id2 = $tewms_product->get_id();
				if (!empty($tewms_sale_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
							$tewms_varitation = 0;
							$tewms_savedData = get_option('tewms_product_data', array());
							$tewms_cpid = $tewms_cart_item['data']->get_id();
							$tewms_variation = wc_get_product($tewms_cpid);
							$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
							if ($tewms_product_id) {
								$tewms_cpid = $tewms_variation->get_parent_id();
								$tewms_varitation = 1;
							}
							$tewms_productp = $tewms_sale_price;
							$tewms_chkqty = 0;
							$tewms_dis_per = "";
							foreach ($tewms_savedData as $tewms_newEntry) {
								$tewms_pid = $tewms_newEntry['p_id'];
								$tewms_pro_id_array = explode(',', $tewms_pid);
								$tewms_maxqty = $tewms_newEntry['p_max_qty'];
								$tewms_minqty = $tewms_newEntry['p_min_qty'];
								$tewms_dis = $tewms_newEntry['p_discount'];
								for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
									$tewms_cqty = $tewms_cart_item['quantity'];
									if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $tewms_product_id2 == $tewms_cpid) {
										if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
											if ($tewms_chkqty < $tewms_maxqty) {
												$tewms_chkqty = $tewms_maxqty;
												$tewms_dis_per = $tewms_dis;
											}
										}
									}
								}
							}
							if ($tewms_product_id2 == $tewms_cpid) {
								if ($tewms_chkqty != 0) {
									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
				$tewms_original_price = $tewms_product->get_sale_price();
				$tewms_product_id = $tewms_product->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($tewms_i = 0; $tewms_i < $tewms_cart_items_count; $tewms_i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$tewms_i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_product->get_id()) {

								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_j = 0; $tewms_j < count($tewms_pro_id_array); $tewms_j++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));
										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];
												if (in_array($tewms_category_id, $tewms_pro_id_array))
												{
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}
									}
								}	
								if ($tewms_chkqty != 0) {
									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else {
			}
		} else if ($tewms_price2 == -1) {
			if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
				$tewms_variation = wc_get_product($tewms_product->get_id());
				if ($tewms_variation->get_type() === 'simple') {
					$tewms_wholesale_price = get_post_meta($tewms_product->get_id(), 'tewms_wholesale_price', true);
					$tewms_product_id = $tewms_product->get_id();
					$tewms_product_id2 = $tewms_product->get_id();
					if (!empty($tewms_wholesale_price)) {
						$tewms_woocommerce = WC();
						$tewms_productp = 0;
						if (!empty($tewms_woocommerce->cart)) {
							foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
								$tewms_varitation = 0;
								$tewms_savedData = get_option('tewms_product_data', array());
								$tewms_cpid = $tewms_cart_item['data']->get_id();
								$tewms_variation = wc_get_product($tewms_cpid);
								$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
								if ($tewms_product_id) {
									$tewms_cpid = $tewms_variation->get_parent_id();
									$tewms_varitation = 1;
								}
								$tewms_productp = $tewms_wholesale_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['p_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['p_max_qty'];
									$tewms_minqty = $tewms_newEntry['p_min_qty'];
									$tewms_dis = $tewms_newEntry['p_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_cqty = $tewms_cart_item['quantity'];
										if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $tewms_product_id2 == $tewms_cpid) {
											if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
												if ($tewms_chkqty < $tewms_maxqty) {
													$tewms_chkqty = $tewms_maxqty;
													$tewms_dis_per = $tewms_dis;
												}
											}
										}
									}
								}
								if ($tewms_product_id2 == $tewms_cpid) {
									if ($tewms_chkqty != 0) {
										if ($tewms_varitation == 1) {
											$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
											$tewms_productp = preg_replace('/,/', '', $tewms_productp);
											$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										} else {
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										}
									} else {
										if ($tewms_varitation == 1) {
											$tewms_sale_price = $tewms_productp;
										} else {
											$tewms_sale_price = $tewms_productp;
										}
									}
								}
							}
						}
						$tewms_price = $tewms_sale_price;
					}
				} else {
					$tewms_vobject = wc_get_product($tewms_variation->get_id());
					$tewms_variation_ids = $tewms_vobject->get_children();
					foreach ($tewms_variation_ids as $tewms_variation_id) {
						$tewms_wholesale_price = get_post_meta($tewms_variation_id, '_wholesale_price', true);
						$tewms_woocommerce = WC();
						$tewms_productp = 0;
						if (!empty($tewms_woocommerce->cart)) {
							foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
								$tewms_varitation = 0;
								$tewms_savedData = get_option('tewms_product_data', array());
								$tewms_cpid = $tewms_cart_item['data']->get_id();
								$tewms_vid = $tewms_cart_item['data']->get_id();
								$tewms_variation = wc_get_product($tewms_cpid);
								$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
								if ($tewms_product_id) {
									$tewms_cpid = $tewms_variation->get_parent_id();
									$tewms_varitation = 1;
								}
								$tewms_productp = $tewms_wholesale_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['p_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['p_max_qty'];
									$tewms_minqty = $tewms_newEntry['p_min_qty'];
									$tewms_dis = $tewms_newEntry['p_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_cqty = $tewms_cart_item['quantity'];
										if ($tewms_cpid == $tewms_pro_id_array[$tewms_i] && $tewms_variation_id == $tewms_vid) {
											if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
												if ($tewms_chkqty < $tewms_maxqty) {
													$tewms_chkqty = $tewms_maxqty;
													$tewms_dis_per = $tewms_dis;
												}
											}
										}
									}
								}
								if ($tewms_variation_id == $tewms_vid) {
									if ($tewms_chkqty != 0) {
										if ($tewms_varitation == 1) {
											$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
											$tewms_productp = preg_replace('/,/', '', $tewms_productp);
											$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										} else {
											$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
										}
									} else {
										if ($tewms_varitation == 1) {
											$tewms_sale_price = $tewms_productp;
										} else {
											$tewms_sale_price = $tewms_productp;
										}
									}
								}

							}
						}
						$tewms_price = $tewms_sale_price;
					}
				}
			} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
				$tewms_original_price = get_post_meta($tewms_product->get_id(), 'tewms_wholesale_price', true);
				$tewms_product_id = $tewms_product->get_id();
				if (!empty($tewms_original_price)) {
					$tewms_woocommerce = WC();
					$tewms_productp = 0;
					if (!empty($tewms_woocommerce->cart)) {
						$tewms_cart_items = $tewms_woocommerce->cart->get_cart();
						$tewms_cart_items_count = count($tewms_cart_items);
						for ($i = 0; $i < $tewms_cart_items_count; $i++) {
							$tewms_cart_item_key = array_keys($tewms_cart_items)[$i];
							$tewms_cart_item = $tewms_cart_items[$tewms_cart_item_key];
							if ($tewms_cart_item['product_id'] === $tewms_product->get_id()) {
								$tewms_savedData = get_option('tewms_category_wise_discount', array());
								$tewms_productp = $tewms_original_price;
								$tewms_chkqty = 0;
								$tewms_dis_per = "";
								foreach ($tewms_savedData as $tewms_newEntry) {
									$tewms_pid = $tewms_newEntry['category_id'];
									$tewms_pro_id_array = explode(',', $tewms_pid);
									$tewms_maxqty = $tewms_newEntry['max_qty'];
									$tewms_minqty = $tewms_newEntry['min_qty'];
									$tewms_dis = $tewms_newEntry['category_discount'];
									for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
										$tewms_product_id_cart = $tewms_cart_item['product_id'];
										$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));
										if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
											foreach ($tewms_product_categories as $index => $cat_id) {
												$tewms_category_id = $cat_id;
												$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
												$tewms_productp = preg_replace('/,/', '', $tewms_productp);
												$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
												$tewms_cqty = $tewms_cart_item['quantity'];											
												if (in_array($tewms_category_id, $tewms_pro_id_array)) {
													if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
														if ($tewms_chkqty < $tewms_maxqty) {
															$tewms_chkqty = $tewms_maxqty;
															$tewms_dis_per = $tewms_dis;
														}
													}
												}
											}
										}
									}
								}
								if ($tewms_chkqty != 0) {
									$tewms_sale_price = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
								} else {
									$tewms_sale_price = $tewms_productp;
								}
							}
						}
					}
					$tewms_price = $tewms_sale_price;
				}
			} else {
			}
		}
		return $tewms_price;
	}
	/**
	 * cart item displayed price
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_cart_item_display_price($tewms_price_html, $tewms_cart_item, $tewms_cart_item_key)
	{
		$tewms_productp = $tewms_cart_item['data']->get_price();
		$tewms_priority = get_option('tewms_enable_dis_radio');
		$tewms_enable_pro = get_option('tewms_enable_pro_dis');
		$tewms_enable_cat = get_option('tewms_enable_category_dis');
		if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
			$tewms_variation = wc_get_product($tewms_cart_item['data']->get_id());
			if ($tewms_variation->get_type() === 'simple') {
				$tewms_savedData = get_option('tewms_product_data', array());
				$tewms_varitation = 0;
				$tewms_cpid = $tewms_cart_item['data']->get_id();
				$tewms_variation = wc_get_product($tewms_cpid);
				$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
				if ($tewms_product_id) {
					$tewms_cpid = $tewms_variation->get_parent_id();
					$tewms_varitation = 1;
				}
				$tewms_productp = $tewms_cart_item['data']->get_price();
				$tewms_chkqty = 0;
				$tewms_dis_per = "";
				foreach ($tewms_savedData as $tewms_newEntry) {
					$tewms_pid = $tewms_newEntry['p_id'];
					$tewms_pro_id_array = explode(',', $tewms_pid);
					$tewms_maxqty = $tewms_newEntry['p_max_qty'];
					$tewms_minqty = $tewms_newEntry['p_min_qty'];
					$tewms_dis = $tewms_newEntry['p_discount'];
					for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
						$tewms_cqty = $tewms_cart_item['quantity'];
						if ($tewms_cpid == $tewms_pro_id_array[$tewms_i]) {
							if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
								if ($tewms_chkqty < $tewms_maxqty) {
									$tewms_chkqty = $tewms_maxqty;
									$tewms_dis_per = $tewms_dis;
								}
							}
						}
					}
				}
				if ($tewms_chkqty != 0) {
					if ($tewms_varitation == 1) {
						$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
						$tewms_productp = preg_replace('/,/', '', $tewms_productp);
						$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
						$dprice = ((float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100);
						$tewms_cart_item['data']->set_price($dprice);
						$tewms_price_html = '<del>' . wc_price($this->tewms_update_price_pro(get_option('tewms_enable_sale_priceo'), $tewms_cart_item)) . '</del> ' . wc_price($tewms_productp);
					} else {
						$tewms_dprice = ((float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100);
						$tewms_cart_item['data']->set_price($tewms_dprice);
						$tewms_price_html = '<del>' . wc_price($this->tewms_update_price_pro(get_option('tewms_enable_sale_priceo'), $tewms_cart_item)) . '</del> ' . wc_price($tewms_productp);

					}
				} else {
					if ($tewms_varitation == 1) {
						$tewms_cart_item['data']->set_price($tewms_productp);
						$tewms_price_html = ($tewms_productp);
					} else {
						$tewms_cart_item['data']->set_price($tewms_productp);
						$tewms_price_html = wc_price($tewms_productp);
					}
				}
			} else {
				$tewms_varitation = 0;
				$tewms_savedData = get_option('tewms_product_data', array());
				$tewms_cpid = $tewms_cart_item['data']->get_id();
				$tewms_vid = $tewms_cart_item['data']->get_id();
				$tewms_variation = wc_get_product($tewms_cpid);
				$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
				if ($tewms_product_id) {
					$tewms_cpid = $tewms_variation->get_parent_id();
					$tewms_varitation = 1;
				}
				$tewms_productp = $tewms_cart_item['data']->get_price();
				$tewms_chkqty = 0;
				$tewms_dis_per = "";
				foreach ($tewms_savedData as $tewms_newEntry) {
					$tewms_pid = $tewms_newEntry['p_id'];
					$tewms_pro_id_array = explode(',', $tewms_pid);
					$tewms_maxqty = $tewms_newEntry['p_max_qty'];
					$tewms_minqty = $tewms_newEntry['p_min_qty'];
					$tewms_dis = $tewms_newEntry['p_discount'];
					for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
						$tewms_cqty = $tewms_cart_item['quantity'];
						if ($tewms_cpid == $tewms_pro_id_array[$tewms_i]) {
							if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
								if ($tewms_chkqty < $tewms_maxqty) {
									$tewms_chkqty = $tewms_maxqty;
									$tewms_dis_per = $tewms_dis;
								}
							}
						}
					}
				}
				if ($tewms_chkqty != 0) {
					if ($tewms_varitation == 1) {
						$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
						$tewms_productp = preg_replace('/,/', '', $tewms_productp);
						$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
						$tewms_price_html = '<del>' . wc_price($this->tewms_update_price_pro(get_option('tewms_enable_sale_priceo'), $tewms_cart_item)) . '</del> ' . wc_price($tewms_productp);
					} else {
						$tewms_price_html = '<del>' . wc_price($this->tewms_update_price_pro(get_option('tewms_enable_sale_priceo'), $tewms_cart_item)) . '</del> ' . wc_price($tewms_productp);
					}
				} else {
					if ($tewms_varitation == 1) {
						$tewms_price_html = ($tewms_productp);
					} else {
						$tewms_price_html = ($tewms_productp);
					}
				}
			}
			return $tewms_price_html;
		} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
			$tewms_savedData = get_option('tewms_category_wise_discount', array());
			$tewms_chkqty = 0;
			$tewms_dis_per = "";
			foreach ($tewms_savedData as $tewms_newEntry) {
				$tewms_pid = $tewms_newEntry['category_id'];
				$tewms_pro_id_array = explode(',', $tewms_pid);
				$tewms_maxqty = $tewms_newEntry['max_qty'];
				$tewms_minqty = $tewms_newEntry['min_qty'];
				$tewms_dis = $tewms_newEntry['category_discount'];
				for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
					$tewms_product_id_cart = $tewms_cart_item['product_id'];
					$tewms_product_categories = wp_get_post_terms($tewms_product_id_cart, 'product_cat', array('fields' => 'ids'));		
					if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
						foreach ($tewms_product_categories as $index => $cat_id) {
							$tewms_category_id = $cat_id;
							$tewms_productp = $tewms_cart_item['data']->get_price();
							$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
							$tewms_productp = preg_replace('/,/', '', $tewms_productp);
							$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
							$tewms_cqty = $tewms_cart_item['quantity'];							
							if (in_array($tewms_category_id, $tewms_pro_id_array)) {
								if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
									if ($tewms_chkqty < $tewms_maxqty) {
										$tewms_chkqty = $tewms_maxqty;
										$tewms_dis_per = $tewms_dis;
									}
								}
							}
						}
					}
				}
			}
			if ($tewms_chkqty != 0) {
				$tewms_price_html = '<del>' . wc_price($this->tewms_update_price_pro(get_option('tewms_enable_sale_priceo'), $tewms_cart_item)) . '</del> ' . wc_price($tewms_productp);
			} else {
				$tewms_price_html = wc_price($tewms_productp);
			}
			return $tewms_price_html;
		} else {
			return $tewms_price_html;
		}
	}
	/**
	 *  function to modify the cart item price
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_cart_item_price($tewms_cart)
	{
		$tewms_woocommerce = WC();
		if (is_admin() && !defined('DOING_AJAX'))
			return;
		if (did_action('woocommerce_before_calculate_totals') >= 2)
			return;
		$tewms_priority = get_option('tewms_enable_dis_radio');
		$tewms_enable_pro = get_option('tewms_enable_pro_dis');
		$tewms_enable_cat = get_option('tewms_enable_category_dis');
		if ($tewms_enable_pro === "1" && $tewms_priority === "0") {
			$tewms_cart = $tewms_woocommerce->cart;
			foreach ($tewms_cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
				$tewms_savedData = get_option('tewms_product_data', array());
				$tewms_varitation = 0;
				$tewms_cpid = $tewms_cart_item['data']->get_id();
				$tewms_variation = wc_get_product($tewms_cpid);
				$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
				if ($tewms_product_id) {
					$tewms_cpid = $tewms_variation->get_parent_id();
					$tewms_varitation = 1;
				}
				$tewms_productp = $tewms_cart_item['data']->get_price();
				$tewms_chkqty = 0;
				$tewms_dis_per = "";
				foreach ($tewms_savedData as $tewms_newEntry) {
					$tewms_pid = $tewms_newEntry['p_id'];
					$tewms_pro_id_array = explode(',', $tewms_pid);
					$tewms_maxqty = $tewms_newEntry['p_max_qty'];
					$tewms_minqty = $tewms_newEntry['p_min_qty'];
					$tewms_dis = $tewms_newEntry['p_discount'];
					for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
						$tewms_cqty = $tewms_cart_item['quantity'];
						if ($tewms_cpid == $tewms_pro_id_array[$tewms_i]) {
							if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
								if ($tewms_chkqty < $tewms_maxqty) {
									$tewms_chkqty = $tewms_maxqty;
									$tewms_dis_per = $tewms_dis;
								}
							}
						}
					}
				}
				if ($tewms_chkqty != 0) {
					if ($tewms_varitation == 1) {
						$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
						$tewms_productp = preg_replace('/,/', '', $tewms_productp);
						$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
						$tewms_discp = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
						$tewms_cart_item['data']->set_price($tewms_discp);
					} else {
						$tewms_discp = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
						$tewms_cart_item['data']->set_price($tewms_dis_per);
					}
				} else {
					if ($tewms_varitation == 1) {
						$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
						$tewms_productp = preg_replace('/,/', '', $tewms_productp);
						$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
						$tewms_cart_item['data']->set_price($tewms_productp);
					} else {
						$tewms_cart_item['data']->set_price($tewms_productp);
					}
				}
			}
			$tewms_cart = WC()->cart;
			$tewms_cart_items = $tewms_cart->get_cart();
			foreach ($tewms_cart_items as $cart_item_key => $cart_item) {
				$product = $cart_item['data'];
				if ($product->get_type() === 'variation') {
					$tewms_price = $product->get_price();
				} else {
					$tewms_price = $product->get_price();
				}
			}
		} else if ($tewms_enable_cat === "1" && $tewms_priority === "1") {
			$tewms_cart = WC()->cart;
			$tewms_cart_items = $tewms_cart->get_cart();
		} else {
		}
	}

	/**
	 * function for modify order item price
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_modify_order_item_price($product, $cart_item = null)
	{
		return $product;
	}
	/**
	 * function for productwise discount amount
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_get_productwise_discount_amount()
	{
		$tewms_woocommerce = WC();
		$tewms_total_dis = 0;
		foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
			$tewms_savedData = get_option('tewms_product_data', array());
			$tewms_varitation = 0;
			$tewms_cpid = $tewms_cart_item['data']->get_id();
			$tewms_variation = wc_get_product($tewms_cpid);
			$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
			if ($tewms_product_id) {
				$tewms_cpid = $tewms_variation->get_parent_id();
				$tewms_varitation = 1;
			}
			$tewms_productp = $tewms_cart_item['data']->get_price();
			$tewms_chkqty = 0;
			$tewms_dis_per = "";
			foreach ($tewms_savedData as $tewms_newEntry) {
				$tewms_pid = $tewms_newEntry['p_id'];
				$tewms_pro_id_array = explode(',', $tewms_pid);
				$tewms_maxqty = $tewms_newEntry['p_max_qty'];
				$tewms_minqty = $tewms_newEntry['p_min_qty'];
				$tewms_dis = $tewms_newEntry['p_discount'];
				for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
					$tewms_cqty = $tewms_cart_item['quantity'];
					if ($tewms_cpid == $tewms_pro_id_array[$tewms_i]) {
						if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
							if ($tewms_chkqty < $tewms_maxqty) {
								$tewms_chkqty = $tewms_maxqty;
								$tewms_dis_per = $tewms_dis;
							}
						}
					}
				}
			}
			if ($tewms_chkqty != 0) {
				if ($tewms_varitation == 1) {
					$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
					$tewms_productp = preg_replace('/,/', '', $tewms_productp);
					$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
					$tewms_discp = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
					$tewms_total_dis += $tewms_productp * $tewms_cart_item['quantity'];
				} else {
					$tewms_discp = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
					$tewms_total_dis += $tewms_productp * $tewms_cart_item['quantity'];
				}
			} else {
				if ($tewms_varitation == 1) {
					$tewms_total_dis += (float) $tewms_productp * $tewms_cart_item['quantity'];
				} else {
					$tewms_total_dis += $tewms_productp * $tewms_cart_item['quantity'];
				}
			}
		}
		return $tewms_total_dis;
	}
	/**
	 * function for categorywise discount
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_get_categorywise_discount_amount()
	{
		$tewms_productp = 0;
		$tewms_woocommerce = WC();
		$tewms_total_dis = 0;
		foreach ($tewms_woocommerce->cart->get_cart() as $tewms_cart_item_key => $tewms_cart_item) {
			$tewms_savedData = get_option('tewms_category_wise_discount', array());
			$tewms_chkqty = 0;
			$tewms_dis_per = "";
			foreach ($tewms_savedData as $tewms_newEntry) {
				$tewms_pid = $tewms_newEntry['category_id'];
				$tewms_pro_id_array = explode(',', $tewms_pid);
				$tewms_maxqty = $tewms_newEntry['max_qty'];
				$tewms_minqty = $tewms_newEntry['min_qty'];
				$tewms_dis = $tewms_newEntry['category_discount'];
				for ($tewms_i = 0; $tewms_i < count($tewms_pro_id_array); $tewms_i++) {
					if (!empty($tewms_product_categories) && is_array($tewms_product_categories)) {
						foreach ($tewms_product_categories as $index => $cat_id) {
							$tewms_category_id = $cat_id;
							$tewms_productp = $tewms_cart_item['data']->get_price();
							$tewms_productp = preg_replace('/&#8377;/', '', $tewms_productp);
							$tewms_productp = preg_replace('/,/', '', $tewms_productp);
							$tewms_productp = floatval(preg_replace("/[^0-9\.]/", "", $tewms_productp));
							$tewms_cqty = $tewms_cart_item['quantity'];
							if (in_array($tewms_category_id, $tewms_pro_id_array)) {
								if ($tewms_cqty >= $tewms_minqty && $tewms_cqty <= $tewms_maxqty) {
									if ($tewms_chkqty < $tewms_maxqty) {
										$tewms_chkqty = $tewms_maxqty;
										$tewms_dis_per = $tewms_dis;
									}
								}
							}
						}
					}
				}
			}
			if ($tewms_chkqty != 0) {
				$tewms_discp = (float) $tewms_productp - (float) $tewms_productp * (float) $tewms_dis_per / 100;
				$tewms_total_dis += $tewms_productp * $tewms_cart_item['quantity'];
			} else {
				$tewms_total_dis += $tewms_productp * $tewms_cart_item['quantity'];
			}
		}
		return $tewms_total_dis;
	}
	/**
	 * function for update price of product
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_update_price_pro($tewms_price, $cart_item)
	{
		$tewms_original_price = 0;
		$tewms_varitation = 0;
		$tewms_cpid = $cart_item['data']->get_id();
		$tewms_variation = wc_get_product($tewms_cpid);
		$tewms_product_id = $tewms_variation->get_parent_id() !== 0;
		if ($tewms_product_id) {
			$tewms_varitation = 1;
		}
		$tewms_variationobj = wc_get_product($tewms_cpid);
		if ($tewms_varitation == 1) {
			if ($tewms_price == 0) {
				$tewms_original_price = $tewms_variationobj->get_regular_price();
			} else if ($tewms_price == 1) {
				$tewms_original_price = $tewms_variationobj->get_sale_price();
			} else if ($tewms_price == -1) {
				$tewms_original_price = get_post_meta($tewms_variationobj->get_id(), '_wholesale_price', true);
			} else {
				$tewms_original_price = $cart_item['data']->get_price();
			}
		} else {
			if ($tewms_price == 0) {
				$tewms_original_price = $cart_item['data']->get_regular_price();
			} else if ($tewms_price == 1) {
				$tewms_original_price = $cart_item['data']->get_sale_price();
			} else if ($tewms_price == -1) {
				$tewms_original_price = get_post_meta($cart_item['data']->get_id(), 'tewms_wholesale_price', true);
			} else {
				$tewms_original_price = $cart_item['data']->get_price();
			}
		}
		return $tewms_original_price;
	}
}