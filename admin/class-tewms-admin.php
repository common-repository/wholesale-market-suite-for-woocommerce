<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tekniskera.com/
 * @since      1.0.0
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 * @author     Teknisk Era  <info@tekniskera.com>
 */
class Tewms_Admin
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
	 * @param      string    $tewms_plugin_name       The name of this plugin.
	 * @param      string    $tewms_version    The version of this plugin.
	 */
	public function __construct($tewms_plugin_name, $tewms_version)
	{
		$this->tewms_plugin_name = $tewms_plugin_name;
		$this->tewms_version = $tewms_version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function tewms_enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tewms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tewms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// echo'version'.$this->tewms_version;
		$tewms_current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
		if ($tewms_current_page === 'tewms-wholesale-settings') {

			wp_enqueue_style($this->tewms_plugin_name, plugin_dir_url(__FILE__) . 'css/tewms-admin.css', array(), $this->tewms_version, 'all');
			wp_enqueue_style('select2', plugins_url('woocommerce/assets/css/select2.css'), array(), '4.1.0-rc.0');
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function tewms_enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tewms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tewms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$tewms_current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
		$tewms_allowed_pages = array(
			'tewms-wholesale-settings',
		);
		if (in_array($tewms_current_page, $tewms_allowed_pages)) {

			wp_enqueue_script($this->tewms_plugin_name, plugin_dir_url(__FILE__) . 'js/tewms-admin.js', array('jquery'), $this->tewms_version, true);
			wp_enqueue_script('select2', plugins_url('woocommerce/assets/js/select2/select2.full.min.js'), array('jquery'), '4.1.0-rc.0', true);
			wp_localize_script(
				$this->tewms_plugin_name,
				'select2Localization',
				array(
					'placeholder' => esc_attr__('Select an option', 'wholesale-market-suite-for-woocommerce'),
					'noResults' => esc_attr__('No results found', 'wholesale-market-suite-for-woocommerce')
				)
			);
		}
	}
	/**
	 * function for custom user role
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_add_custom_role()
	{
		add_role(
			'wholesaler',
			esc_html__('Wholesaler', 'wholesale-market-suite-for-woocommerce'),
			array(
				'read' => true,
				'edit_posts' => false,
				'delete_posts' => false,
				'edit_published_posts' => false,
				'publish_posts' => false,
				'edit_private_posts' => false,
				'read_private_posts' => true,
				'manage_woocommerce' => true,
				'manage_woocommerce_product_discounts' => true,
			)
		);
	}
	/**
	 * Set the user role after registration
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_wpw_set_user_role($tewms_user_id)
{
    // Check if nonce is set and verify it
    if (!isset($_POST['tewms_user_role_nonce']) || !wp_verify_nonce($_POST['tewms_user_role_nonce'], 'tewms_user_role_nonce_action')) {
        // If nonce verification fails, exit early
        return;
    }

    // Continue only if the nonce is verified
    if (isset($_POST['user_role']) && !empty($_POST['user_role'])) {
        $tewms_user_role = sanitize_text_field($_POST['user_role']);

        switch ($tewms_user_role) {
            case 'wholesaler':
                $tewms_role = 'wholesaler';
                break;
            case 'manager':
                $tewms_role = 'manager';
                break;
            default:
                $tewms_role = 'customer';
                break;
        }

        $tewms_user = new WP_User($tewms_user_id);
        $tewms_user->set_role($tewms_role);
    }
}

	/**
	 * Add a menu item to the WordPress dashboard
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_your_plugin_add_menu()
	{

		add_menu_page(
			'Wholesale',
			esc_html__('Wholesale Settings', 'wholesale-market-suite-for-woocommerce'),
			'manage_options',
			'tewms-wholesale-settings',
			array($this, 'tewms_wholesale_settings_page_callback'),
			esc_html__('dashicons-cart', 'wholesale-market-suite-for-woocommerce'),
			50
		);
	}
	/**
	 * wholesale regiester setting
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_wholesale_register_settings()
	{

		register_setting('wholesale_settings_group', 'wholesale_quantity');
		register_setting('wholesale_settings_group', 'wholesale_discount');
		register_setting('wholesale_settings_group', 'wholesale_entries');
	}

	/**
	 * function use for search product 
	 * The version of this plugin.tewms_add_custom_product_fields
	 *
	 * @since    1.0.0
	 */
	function tewms_search_products() {
		if (isset($_POST['tewms_wholesale_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tewms_wholesale_settings_nonce'])), 'tewms_wholesale_settings_nonce')) {
			if (isset($_POST['keyword'])) {
				$tewms_keyword = sanitize_text_field($_POST['keyword']);
				$tewms_args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					's' => $tewms_keyword,
				);
				$tewms_products = new WP_Query($tewms_args);
				if ($tewms_products->have_posts()) {
					$tewms_counter = 1;
					$product_id = esc_html(get_the_ID());
                    $product_title = esc_html(get_the_title());
					while ($tewms_products->have_posts()) {
						$tewms_products->the_post();
						echo '<div class="product-search-item">';
						esc_html_e('product-search-item', 'wholesale-market-suite-for-woocommerce');
						echo '</div>';
						echo sprintf(
							'<a href="#" class="search-item-link" data-product-id="%s">%s (#%s)</a><br>',
							$product_id,
							$product_title,
							$product_id
						);
						echo '<input type="hidden" name="pro_id" value="' . esc_html(get_the_ID()) . '" />';
						echo '</div><br>';
						$tewms_counter++;
					}
					wp_reset_postdata();
				} else {
					esc_html_e('No products found.', 'wholesale-market-suite-for-woocommerce');
				}
				wp_die();
			}
		}
	}
	/**
	 * function for wholesale page callback
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_wholesale_page_callback()
	{
		echo '<div>';
		esc_html_e('Wholesale setting', 'wholesale-market-suite-for-woocommerce');
		echo '</div>';
	}



	/**
	 * function for remove the background text
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_remove_admin_footer_text($tewms_text)
	{
		//  variable is set to an empty string, and then it is returned.
		$tewms_text = '';
		return $tewms_text;
	}
	/**
	 * function for remove the background text
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_remove_version_number()
	{
		remove_filter('update_footer', 'core_update_footer');
	}
	/**
	 * function for wholesale setting page callback
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function tewms_wholesale_settings_page_callback()
	{
		$tewms_active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
		?>
		<div class="tewms-wrap1 wrap">
			<div class="w-section--head">
				<h1 class="tewms-heading">
					<?php esc_html_e('Welcome to the Wholesale Market Suite for WooCommerce Plugin', 'wholesale-market-suite-for-woocommerce'); ?>
				</h1>
				<p><?php esc_html_e('Thank you for using the WholeSale Market Suite for WooCommerce Plugin. This is the Setting Page.', 'wholesale-market-suite-for-woocommerce') ?>
				</p>
			</div>
		</div>
		<div class="tewms-ws-content__wrap">
			<h4 class="tewms-subheading">
				<?php esc_html_e('Wholesale Settings', 'wholesale-market-suite-for-woocommerce'); ?>
			</h4>
			<div class="tewms-nav-tab-wrapper">
				<a href="?page=tewms-wholesale-settings&tab=general"
					class="tewms-nav-tab <?php echo ($tewms_active_tab === 'general' ? 'tewms-nav-tab-active' : ''); ?>">
					<?php esc_html_e('General', 'wholesale-market-suite-for-woocommerce'); ?>
				</a>
				<a href="?page=tewms-wholesale-settings&tab=product"
					class="tewms-nav-tab <?php echo ($tewms_active_tab === 'product' ? 'tewms-nav-tab-active' : ''); ?>">
					<?php esc_html_e('Product', 'wholesale-market-suite-for-woocommerce'); ?>
				</a>
				<a href="?page=tewms-wholesale-settings&tab=category"
					class="tewms-nav-tab <?php echo ($tewms_active_tab === 'category' ? 'tewms-nav-tab-active' : ''); ?>">
					<?php esc_html_e('Category', 'wholesale-market-suite-for-woocommerce'); ?>
				</a>

			</div>
			<?php
			?>
			<?php if ($tewms_active_tab === 'general'):
				?>
				<div class="wrap">
					<form method="post" action="">
						<div class="w-input-form--group_wrap">
							<?php

							if (isset($_POST['submit'])) {
								$tewms_checkboxes = isset($_POST['checkboxes']) ? sanitize_text_field($_POST['checkboxes']) : array();
								update_option('tewms_checkboxes', $tewms_checkboxes);
								if (isset($_POST['tewms_enable_dis_radio'])) {
									$tewms_enable_pro_dis = sanitize_text_field($_POST['tewms_enable_dis_radio']);
								}
								if ($tewms_enable_pro_dis == '1') {
									update_option('tewms_enable_dis_radio', $tewms_enable_pro_dis);
								} elseif ($tewms_enable_pro_dis == '0') {
									update_option('tewms_enable_dis_radio', $tewms_enable_pro_dis);
								} else if ($tewms_enable_pro_dis == '-1') {
									update_option('tewms_enable_dis_radio', $tewms_enable_pro_dis);
								} else {
									update_option('tewms_enable_dis_radio', $tewms_enable_pro_dis);
								}

								if (isset($_POST['tewms_enable_sale_priceo'])) {
									$tewms_enable_price = sanitize_text_field($_POST['tewms_enable_sale_priceo']);
									if ($tewms_enable_price == '0') {
										update_option('tewms_enable_sale_priceo', $tewms_enable_price);
									} else if ($tewms_enable_price == '1') {
										update_option('tewms_enable_sale_priceo', $tewms_enable_price);
									} else if ($tewms_enable_price == '-1') {
										update_option('tewms_enable_sale_priceo', $tewms_enable_price);
									} else {
										update_option('tewms_enable_sale_priceo', $tewms_enable_price);
									}
								}

								echo '<div class="notice notice-success"><p>';
								esc_html_e('Data saved successfully.', 'wholesale-market-suite-for-woocommerce');
								echo '</p></div>';
							}

							$tewms_checkboxes = get_option('checkboxes', []);
							$tewms_sanitizedCheckboxes = array_map('sanitize_text_field', $tewms_checkboxes);
							?>
							<div class="w-input-form--group">
								<h4>
									<?php esc_html_e('Manage discounts', 'wholesale-market-suite-for-woocommerce'); ?>
								</h4>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_dis_radio" value="0" <?php checked((get_option('tewms_enable_dis_radio')), 0); ?>>
									<span>
										<?php esc_html_e('Enable Priority ', 'wholesale-market-suite-for-woocommerce'); ?>
										<?php esc_html_e('Product-wise Discount', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_dis_radio" value="1" <?php checked((get_option('tewms_enable_dis_radio')), 1); ?>>
									<span>
										<?php esc_html_e('Enable Priority Categry discount', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_dis_radio" value="-1" <?php checked(get_option(('tewms_enable_dis_radio')), -1); ?>>
									<span>
										<?php esc_html_e('Disable all the priority', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
							</div>
							<div class="w-input-form--group">
								<h4>
									<?php esc_html_e('Manage Product Price', 'wholesale-market-suite-for-woocommerce'); ?>
								</h4>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_sale_priceo" value="0" <?php checked(get_option('tewms_enable_sale_priceo'), 0); ?>>
									<span>
										<?php esc_html_e('Enable Discount on ', 'wholesale-market-suite-for-woocommerce'); ?>
										<?php esc_html_e('Regular Price', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_sale_priceo" value="1" <?php checked(get_option('tewms_enable_sale_priceo'), 1); ?>>
									<span>
										<?php esc_html_e('Enable Discount on Sale Price', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
								<label class="w-form--label">
									<input type="radio" name="tewms_enable_sale_priceo" value="-1" <?php checked(get_option('tewms_enable_sale_priceo'), -1); ?>>
									<span>
										<?php esc_html_e('Enable Discount on Wholesale Price', 'wholesale-market-suite-for-woocommerce'); ?>
									</span>
								</label>
							</div>
							<p class="submit">
								<input type="submit" name="submit" class="button-primary"
									value="<?php esc_attr_e('Save Changes', 'wholesale-market-suite-for-woocommerce'); ?>" />
							</p>
						</div>
					</form>
				</div>
			<?php elseif ($tewms_active_tab === 'product'):
				?>
				<div class="wrap">
					<form method="post" action="">
						<?php
						wp_nonce_field('tewms_wholesale_settings_nonce', 'tewms_wholesale_settings_nonce');
						?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enable the product-wise discount', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="checkbox" name="tewms_enable_pro_dis_checkbox" value="1" <?php checked(get_option('tewms_enable_pro_dis')); ?>>
								</td>
							</tr>
							<?php
							$tewms_products = wc_get_products(
								array(
									'limit' => -1,
								)
							);
							?>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Search your Product to get the details about the product', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td>
									<div class="wrapper">
										<div id="clr_cont" class="contains">
											<select class="tewms_fav_clr form-control" name="productss[]" multiple="multiple">
												<?php
												$tewms_html = '';
												foreach ($tewms_products as $tewms_product) {
													$tewms_html .= '<option value="' . $tewms_product->get_id() . '">' . $tewms_product->get_name() . '</option>';
												}
												echo wp_kses($tewms_html, array('option' => array('value' => array())));
												echo '</select></div></div></td></tr>';
												?>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Minimun Products quantity', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_min_product_qty" name="tewms_min_product_qty"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Quantity', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Maximum Products quantity', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_max_product_qty" name="tewms_max_product_qty"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Quantity', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Products Discount', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_product_discount" name="tewms_product_discount"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Discount %', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Message', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_product_msg" name="tewms_product_msg" class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Message', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
						</table>
						<p class="submit">
							<input type="submit" name="submit" class="button-primary"
								value="<?php esc_attr_e('Save Changes', 'wholesale-market-suite-for-woocommerce'); ?>" />
						</p>
					</form>
				</div>
				<?php
				$tewms_savedData_product = get_option('tewms_product_data', array());
				if (!empty($tewms_savedData_product)) {
					echo '<table class="wp-list-table widefat striped ws-custom-data__table">';
					echo '<tr><th>';
					esc_html_e('Product Name', 'wholesale-market-suite-for-woocommerce');
					echo '</th><th>';
					esc_html_e('Product Quantity', 'wholesale-market-suite-for-woocommerce');
					echo '</th><th>';
					esc_html_e('Product Discount', 'wholesale-market-suite-for-woocommerce');
					echo '</th><th>';
					esc_html_e('Message', 'wholesale-market-suite-for-woocommerce');
					echo '</th><th>';
					esc_html_e('Action', 'wholesale-market-suite-for-woocommerce');
					echo '</th></tr>';
					foreach ($tewms_savedData_product as $tewms_index => $tewms_data) {
						$tewms_productDiscount = sanitize_text_field($tewms_data['p_discount']);
						$tewms_productname = sanitize_text_field($tewms_data['p_name']);
						$tewms_productprice = sanitize_text_field($tewms_data['p_price']);
						$tewms_productDiscountMsg = sanitize_text_field($tewms_data['p_msg']);
						$tewms_minqty = sanitize_text_field($tewms_data['p_min_qty']);
						$tewms_maxqty = sanitize_text_field($tewms_data['p_max_qty']);
						$tewms_productQty = $tewms_minqty . ' - ' . $tewms_maxqty;
						$tewms_pro_price = explode(',', $tewms_productprice);
						$tewms_lowestPrice = min($tewms_pro_price);
						$tewms_highestPrice = max($tewms_pro_price);
						$tewms_range = $tewms_lowestPrice . '-' . $tewms_highestPrice;
						?>
						<tr>
							<td><?php echo esc_html($tewms_productname); ?></td>
							<td><?php echo esc_html($tewms_productQty); ?></td>
							<td><?php echo esc_html($tewms_productDiscount); ?></td>
							<td><?php echo esc_html($tewms_productDiscountMsg); ?></td>
							<td>
								<a href="<?php echo esc_url('?page=tewms-wholesale-settings&tab=product&delete=' . $tewms_index); ?>"
									class="button"><?php esc_html_e('Delete', 'wholesale-market-suite-for-woocommerce'); ?></a>
							</td>
						</tr>
						<?php
					}
					echo '</table>';
				}
				?>
				<?php
				if (isset($_GET['delete'])) {
					$tewms_delete_index = absint(sanitize_text_field($_GET['delete']));
					$tewms_entries = get_option('tewms_product_data', []);
					if (isset($tewms_entries[$tewms_delete_index])) {
						unset($tewms_entries[$tewms_delete_index]);
						$tewms_entries = array_values($tewms_entries);
						update_option('tewms_product_data', $tewms_entries);
						$tewms_delete_url = remove_query_arg('delete');
						wp_redirect($tewms_delete_url);
					}
				}

				if (isset($_POST['tewms_wholesale_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tewms_wholesale_settings_nonce'])), 'tewms_wholesale_settings_nonce')) {

					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						// Retrieve and sanitize the 'productss' data from $_POST
					$tewms_selectedProducts = isset($_POST['productss']) && is_array($_POST['productss']) ? array_map('sanitize_text_field', $_POST['productss']) : array();

						foreach ($tewms_selectedProducts as &$product) {
							$product = filter_var($product, FILTER_SANITIZE_NUMBER_INT);
						}


						if (
							empty($tewms_selectedProducts) || empty($_POST['tewms_product_discount']) || empty($_POST['tewms_product_msg']) ||
							empty($_POST['tewms_max_product_qty']) || empty($_POST['tewms_min_product_qty'])
						) {
							if (isset($_POST['tewms_enable_pro_dis_checkbox'])) {
								$tewms_enable_pro_dis = sanitize_text_field($_POST['tewms_enable_pro_dis_checkbox']);
								update_option('tewms_enable_pro_dis', 1);
							} else {
								update_option('tewms_enable_pro_dis', 0);
							}
						} else {
							$tewms_searchedproductId = "";
							foreach ($tewms_selectedProducts as $tewms_productId) {

								$tewms_searchedproductId .= $tewms_productId . '@';
							}

							$tewms_searchedproductId = substr($tewms_searchedproductId, 0, -1);

							if (isset($_POST['tewms_enable_pro_dis_checkbox'])) {
								update_option('tewms_enable_pro_dis', 1);
							} else {
								update_option('tewms_enable_pro_dis', 0);
							}
							$tewms_discount = isset($_POST['tewms_product_discount']) ? sanitize_text_field($_POST['tewms_product_discount']) : '';
							$tewms_msg = isset($_POST['tewms_product_msg']) ? sanitize_text_field($_POST['tewms_product_msg']) : '';
							$tewms_maxqty = isset($_POST['tewms_max_product_qty']) ? sanitize_text_field($_POST['tewms_max_product_qty']) : '';
							$tewms_minqty = isset($_POST['tewms_min_product_qty']) ? sanitize_text_field($_POST['tewms_min_product_qty']) : '';

							$tewms_string = $tewms_searchedproductId;
							$tewms_array = explode('@', $tewms_string);
							$tewms_p_name = "";
							$tewms_p_id = "";
							$tewms_p_price = "";
							$tewms_priceArray = array();
							for ($i = 0; $i < count($tewms_array); $i++) {
								$tewms_p_id = $tewms_p_id . $tewms_array[$i] . ',';
								$tewms_product = wc_get_product($tewms_array[$i]);
								if ($tewms_product->get_type() == 'variable') {
									array_push($tewms_priceArray, $tewms_product->get_price());
									$tewms_p_price = $tewms_p_price . $tewms_product->get_price() . ',';
									$tewms_p_name = $tewms_p_name . $tewms_product->get_name() . ',';
								} else {
									array_push($tewms_priceArray, $tewms_product->get_price());
									$tewms_p_price = $tewms_p_price . $tewms_product->get_price() . ',';
									$tewms_p_name = $tewms_p_name . $tewms_product->get_name() . ',';
								}
							}
							$tewms_p_price = substr($tewms_p_price, 0, -1);
							$tewms_p_name = substr($tewms_p_name, 0, -1);
							$tewms_p_id = substr($tewms_p_id, 0, -1);

							$tewms_savedData_product = get_option('tewms_product_data', array());

							$tewms_sanitizedData = array_map('sanitize_text_field', $tewms_savedData_product);
							$tewms_product = array(
								'p_id' => ($tewms_p_id),
								'p_name' => ($tewms_p_name),
								'p_min_qty' => ($tewms_minqty),
								'p_max_qty' => ($tewms_maxqty),
								'p_discount' => ($tewms_discount),
								'p_msg' => ($tewms_msg),
								'p_price' => ($tewms_p_price),
							);
							$tewms_savedData_product[] = $tewms_product;


							update_option('tewms_product_data', $tewms_savedData_product);
						}
						wp_redirect(esc_url_raw(admin_url('/admin.php?page=tewms-wholesale-settings&tab=product')));
						exit();
					}
				}
				?>
			<?php elseif ($tewms_active_tab === 'category'):
				?>
				<div class="wrap">
					<form method="post" action="">
						<?php

						wp_nonce_field('tewms_category_settings_nonce', 'tewms_category_settings_nonce');
						?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enable the category-wise discount', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="checkbox" name="tewms_enable_category_dis_checkbox" value="1" <?php checked(get_option('tewms_enable_category_dis'), 1); ?>>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Search Your Category', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td>
									<div class="wrapper">
										<div id="clr_cont" class="contains">
											<select class="tewms_fav_clr2 form-control" name="categoriess[]" multiple="multiple">
												<?php
												$tewms_categories = get_terms(
													array(
														'taxonomy' => 'product_cat',
														'hide_empty' => false,
													)
												);

												$html = '';
												foreach ($tewms_categories as $tewms_category) {

													$html .= '<option value="' . $tewms_category->term_id . '">' . $tewms_category->name . '</option>';
												}
												echo wp_kses($html, array('option' => array('value' => array())));

												echo '</select></div></div></td></tr>';
												?>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Minimun Products quantity', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_min_cat_product_qty" name="tewms_min_cat_product_qty"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Quantity', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Maximum Products quantity', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_max_cat_product_qty" name="tewms_max_cat_product_qty"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Quantity', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Category-wise Discount', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_product_cat_discount" name="tewms_product_cat_discount"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Discount %', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Enter Message', 'wholesale-market-suite-for-woocommerce'); ?>
								</th>
								<td><input type="text" id="tewms_product_cat_msg" name="tewms_product_cat_msg"
										class="tewms-w-form-control"
										placeholder="<?php esc_attr_e('Enter Message', 'wholesale-market-suite-for-woocommerce'); ?>">
								</td>
							</tr>
						</table>
						<p class="submit">
							<input type="submit" name="save_product" class="button-primary"
								value="<?php esc_attr_e('Save Changes', 'wholesale-market-suite-for-woocommerce'); ?>" />
						</p>
					</form>
				</div>
				<?php
				if (isset($_POST['tewms_category_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tewms_category_settings_nonce'])), 'tewms_category_settings_nonce')) {

					if (isset($_POST['save_product'])) {
						$tewms_selectedcategory = isset($_POST['categoriess']) ? wp_kses_post_deep($_POST['categoriess']) : array();
						if (
							empty($tewms_selectedcategory) || empty($_POST['tewms_product_cat_discount']) || empty($_POST['tewms_product_cat_msg']) ||
							empty($_POST['tewms_max_cat_product_qty']) || empty($_POST['tewms_min_cat_product_qty'])
						) {
							if (isset($_POST['tewms_enable_category_dis_checkbox'])) {
								update_option('tewms_enable_category_dis', 1);
							} else {
								update_option('tewms_enable_category_dis', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_shop_checkbox'])) {
								update_option('tewms_enable_category_dis_shop', 1);
							} else {
								update_option('tewms_enable_category_dis_shop', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_cart_checkbox'])) {
								update_option('tewms_enable_category_dis_cart', 1);
							} else {
								update_option('tewms_enable_category_dis_cart', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_checkout_checkbox'])) {
								update_option('tewms_enable_category_dis_checkout', 1);
							} else {
								update_option('tewms_enable_category_dis_checkout', 0);
							}
						} else {
							// Initialize the result variable
							$tewms_searchedcategoryId = '';

							// Ensure $tewms_selectedcategory is an array
							if (is_array($tewms_selectedcategory)) {
								foreach ($tewms_selectedcategory as $categoryId) {
									// Sanitize each category ID (assuming IDs are integers)
									$sanitizedCategoryId = intval($categoryId);
									
									// Append the sanitized ID to the result string with a delimiter
									$tewms_searchedcategoryId .= $sanitizedCategoryId . '@';
								}

								// Remove trailing delimiter
								$tewms_searchedcategoryId = rtrim($tewms_searchedcategoryId, '@');
							}

							$tewms_discount = isset($_POST['tewms_product_cat_discount']) ? sanitize_text_field($_POST['tewms_product_cat_discount']) : '';
							$tewms_msg = isset($_POST['tewms_product_cat_msg']) ? sanitize_text_field($_POST['tewms_product_cat_msg']) : '';
							$tewms_maxqty = isset($_POST['tewms_max_cat_product_qty']) ? sanitize_text_field($_POST['tewms_max_cat_product_qty']) : '';
							$tewms_minqty = isset($_POST['tewms_min_cat_product_qty']) ? sanitize_text_field($_POST['tewms_min_cat_product_qty']) : '';
							if (isset($_POST['tewms_enable_category_dis_checkbox'])) {
								update_option('tewms_enable_category_dis', 1);
							} else {
								update_option('tewms_enable_category_dis', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_shop_checkbox'])) {
								update_option('tewms_enable_category_dis_shop', 1);
							} else {
								update_option('tewms_enable_category_dis_shop', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_cart_checkbox'])) {
								update_option('tewms_enable_category_dis_cart', 1);
							} else {
								update_option('tewms_enable_category_dis_cart', 0);
							}
							if (isset($_POST['tewms_enable_category_dis_checkout_checkbox'])) {
								update_option('tewms_enable_category_dis_checkout', 1);
							} else {
								update_option('tewms_enable_category_dis_checkout', 0);
							}
							$tewms_string = sanitize_text_field($tewms_searchedcategoryId);
							$tewms_array = explode('@', $tewms_string);
							$tewms_p_name = "";
							$tewms_p_id = "";
							for ($tewms_i = 0; $tewms_i < count($tewms_array); $tewms_i++) {
								$tewms_p_id = $tewms_p_id . $tewms_array[$tewms_i] . ',';
								$category = get_term_by('id', $tewms_array[$tewms_i], 'product_cat');
								if (is_object($category)) {
									$tewms_p_name = $tewms_p_name . $category->name . ',';
								}
							}
							$tewms_p_name = substr($tewms_p_name, 0, -1);
							$tewms_p_id = substr($tewms_p_id, 0, -1);
							$tewms_p_id = substr($tewms_p_id, 0, -1);

							$tewms_savedData = get_option('tewms_category_wise_discount', array());

							$tewms_sanitizedData = array_map('sanitize_text_field', $tewms_savedData);

							$tewms_NewEntry = array(
								'category_id' => ($tewms_p_id),
								'category_name' => ($tewms_p_name),
								'category_discount' => ($tewms_discount),
								'max_qty' => ($tewms_maxqty),
								'min_qty' => ($tewms_minqty),
								'product_category_msg' => ($tewms_msg),
							);

							$tewms_savedData[] = $tewms_NewEntry;

							if ($tewms_discount === '' && $tewms_maxqty === '' && $tewms_minqty === '' && $tewms_msg === '') {
							} else {
								update_option('tewms_category_wise_discount', $tewms_savedData);
							}
						}
						wp_redirect(esc_url_raw(admin_url('/admin.php?page=tewms-wholesale-settings&tab=category')));
						exit();
					}
				}
				if (isset($_GET['delete'])) {
					$tewms_delete_index = absint(sanitize_text_field($_GET['delete']));

					$tewms_entries = get_option('tewms_category_wise_discount', []);

					if (isset($tewms_entries[$tewms_delete_index])) {
						unset($tewms_entries[$tewms_delete_index]);

						$tewms_entries = array_values($tewms_entries);

						update_option('tewms_category_wise_discount', $tewms_entries);
						$tewms_delete_url = remove_query_arg('delete');
						wp_redirect($tewms_delete_url);
					}
				}
				$tewms_savedData = get_option('tewms_category_wise_discount', array());

				$tewms_sanitizedData = array_map('sanitize_text_field', $tewms_savedData);
				if (!empty($tewms_savedData)): ?>
					<div class="wrap2">
						<h2>
							<?php esc_html_e('Saved Category-wise Discount Data', 'wholesale-market-suite-for-woocommerce'); ?>
						</h2>
						<table class="wp-list-table widefat striped ws-custom-data__table">
							<thead>
								<tr>
									<th>
										<?php esc_html_e('Category Name', 'wholesale-market-suite-for-woocommerce'); ?>
									</th>
									<th>
										<?php esc_html_e('Discount', 'wholesale-market-suite-for-woocommerce'); ?>
									</th>
									<th>
										<?php esc_html_e('Quantity', 'wholesale-market-suite-for-woocommerce'); ?>
									</th>
									<th>
										<?php esc_html_e('Message', 'wholesale-market-suite-for-woocommerce'); ?>
									</th>
									<th>
										<?php esc_html_e('Action', 'wholesale-market-suite-for-woocommerce'); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($tewms_savedData as $tewms_index => $tewms_data): ?>
									<?php
									$tewms_minqty = sanitize_text_field($tewms_data['min_qty']);
									$tewms_maxqty = sanitize_text_field($tewms_data['max_qty']);
									$tewms_productQty = $tewms_minqty . ' - ' . $tewms_maxqty;
									$tewms_category_name = sanitize_text_field($tewms_data['category_name']);
									$tewms_cat_disc = sanitize_text_field($tewms_data['category_discount']);
									$tewms_cat_massage = sanitize_text_field($tewms_data['product_category_msg']);
									?>
									<tr>
										<td><?php echo esc_html($tewms_category_name); ?></td>
										<td><?php echo esc_html($tewms_productQty); ?></td>
										<td><?php echo esc_html($tewms_cat_disc); ?></td>
										<td><?php echo esc_html($tewms_cat_massage); ?></td>
										<td>
											<a href="<?php echo esc_url('?page=tewms-wholesale-settings&tab=category&delete=' . $tewms_index); ?>"
												class="button">
												<?php esc_html_e('Delete', 'wholesale-market-suite-for-woocommerce') ?>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		</div>
		<?php
	}
}