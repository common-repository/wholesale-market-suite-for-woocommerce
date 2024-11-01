<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tekniskera.com/
 * @since      1.0.0
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 * @author     Teknisk Era  <info@tekniskera.com>
 */
class Tewms
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tewms_Loader    $tewms_loader    Maintains and registers all hooks for the plugin.
	 */
	protected $tewms_loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tewms_plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $tewms_plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tewms_version    The current version of the plugin.
	 */
	protected $tewms_version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('TEWMS_VERSION')) {
			$this->tewms_version = TEWMS_VERSION;
		} else {
			$this->tewms_version = '1.0.0';
		}
		$this->tewms_plugin_name = 'wholesale-market-suite-for-woocommerce';

		$this->tewms_load_dependencies();
		$this->tewms_set_locale();
		$this->tewms_define_admin_hooks();
		$this->tewms_define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tewms_Loader. Orchestrates the hooks of the plugin.
	 * - Tewms_i18n. Defines internationalization functionality.
	 * - Tewms_Admin. Defines all hooks for the admin area.
	 * - Tewms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function tewms_load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tewms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tewms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tewms-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tewms-public.php';

		$this->tewms_loader = new Tewms_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tewms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function tewms_set_locale()
	{

		$tewms_plugin_i18n = new Tewms_i18n();

		$this->tewms_loader->tewms_add_action('plugins_loaded', $tewms_plugin_i18n, 'tewms_load_plugin_textdomain');

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function tewms_define_admin_hooks()
	{

		$tewms_plugin_admin = new Tewms_Admin($this->tewms_get_plugin_name(), $this->tewms_get_version());
		$this->tewms_loader->tewms_add_action('admin_enqueue_scripts', $tewms_plugin_admin, 'tewms_enqueue_styles');
		$this->tewms_loader->tewms_add_action('admin_enqueue_scripts', $tewms_plugin_admin, 'tewms_enqueue_scripts');
		$this->tewms_loader->tewms_add_action('admin_menu', $tewms_plugin_admin, 'tewms_your_plugin_add_menu');
		$this->tewms_loader->tewms_add_action('admin_init', $tewms_plugin_admin, 'tewms_wholesale_register_settings');
		$this->tewms_loader->tewms_add_action('wp_ajax_search_products', $tewms_plugin_admin, 'tewms_search_products');
		$this->tewms_loader->tewms_add_action('wp_ajax_nopriv_search_products', $tewms_plugin_admin, 'tewms_search_products');
		$this->tewms_loader->tewms_add_action('init', $tewms_plugin_admin, 'tewms_add_custom_role');		
		$this->tewms_loader->tewms_add_filter('admin_footer_text', $tewms_plugin_admin, 'tewms_remove_admin_footer_text');
		$this->tewms_loader->tewms_add_action('admin_menu', $tewms_plugin_admin, 'tewms_remove_version_number');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function tewms_define_public_hooks()
	{
		$tewms_plugin_public = new Tewms_Public($this->tewms_get_plugin_name(), $this->tewms_get_version());
		$this->tewms_loader->tewms_add_filter('woocommerce_product_get_price', $tewms_plugin_public, 'tewms_modify_product_price', 10, 2);
		$this->tewms_loader->tewms_add_filter('woocommerce_cart_item_price', $tewms_plugin_public, 'tewms_modify_cart_item_display_price', 10, 3);	
		$this->tewms_loader->tewms_add_action('woocommerce_before_calculate_totals', $tewms_plugin_public, 'tewms_modify_cart_item_price', 10, 1);		
		$this->tewms_loader->tewms_add_filter('woocommerce_order_item_product', $tewms_plugin_public, 'tewms_modify_order_item_price', 10, 2);
		$this->tewms_loader->tewms_add_filter('woocommerce_product_variation_get_price', $tewms_plugin_public, 'tewms_modify_variable_product_price', 10, 2);
		$this->tewms_loader->tewms_add_action('woocommerce_before_add_to_cart_form', $tewms_plugin_public, 'tewms_display_product_details');
		$this->tewms_loader->tewms_add_action('admin_post_add_wholesale_entry', $tewms_plugin_public, 'tewms_add_wholesale_entry');
		$this->tewms_loader->tewms_add_action('woocommerce_process_product_meta', $tewms_plugin_public, 'tewms_save_custom_product_fields');
		$this->tewms_loader->tewms_add_filter('woocommerce_get_price_html', $tewms_plugin_public, 'tewms_modify_simple_product_price_html', 10, 2);
		$this->tewms_loader->tewms_add_action('woocommerce_product_options_pricing', $tewms_plugin_public, 'tewms_add_custom_product_fields');
		$this->tewms_loader->tewms_add_action('woocommerce_save_product_variation', $tewms_plugin_public, 'tewms_save_variation_wholesale_price_fields', 10, 2);
		$this->tewms_loader->tewms_add_action('woocommerce_variation_options_pricing', $tewms_plugin_public, 'tewms_add_variation_wholesale_price_fields', 10, 3);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function tewms_run()
	{
		$this->tewms_loader->tewms_run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function tewms_get_plugin_name()
	{
		return $this->tewms_plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tewms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function tewms_get_loader()
	{
		return $this->tewms_loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function tewms_get_version()
	{
		return $this->tewms_version;
	}

}