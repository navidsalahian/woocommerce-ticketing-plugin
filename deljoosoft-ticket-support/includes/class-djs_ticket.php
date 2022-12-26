<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://deljoosoft.com
 * @since      1.0.0
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
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
 * @since      1.0.0
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
 * @author     Navid Salahian <salahian.navid@gmail.com>
 */
class DJS_Ticket {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Djs_ticket_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DJS_TICKET_VERSION' ) ) {
			$this->version = DJS_TICKET_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'djs_ticket';

		$this->load_dependencies();
		$this->set_locale();
        $this->define_core_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Djs_ticket_Loader. Orchestrates the hooks of the plugin.
	 * - Djs_ticket_i18n. Defines internationalization functionality.
	 * - Djs_ticket_Admin. Defines all hooks for the admin area.
	 * - Djs_ticket_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djs_ticket-loader.php';

        /**
         * The class responsible for defining all core actions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djs_ticket-core.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djs_ticket-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-djs_ticket-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-djs_ticket-public.php';

        /**
         * The class responsible for defining all core actions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djs-ticket-handlers.php';

		$this->loader = new DJS_Ticket_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Djs_ticket_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new DJS_Ticket_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new DJS_Ticket_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action('admin_menu', $plugin_admin, 'djs_ticket_submenu_settings');
        $this->loader->add_action('wp_ajax_djs_submit_reply_ticket_admin', $plugin_admin, 'djs_ticket_submit_reply_ticket_admin');
        $this->loader->add_filter('manage_djs_ticket_posts_columns', $plugin_admin, 'djs_ticket_custom_columns');
        $this->loader->add_action('manage_djs_ticket_posts_custom_column', $plugin_admin, 'djs_ticket_display_custom_columns', 10, 2);
        $this->loader->add_action('save_post_djs_ticket', $plugin_admin, 'djs_ticket_save_post', 10, 3);
        $this->loader->add_action('transition_post_status', $plugin_admin, 'djs_ticket_publish_ticket_by_admin', 10, 3);

	}

    /**
     * Register all of the hooks related to the core functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_core_hooks() {

        $plugin_admin = new DJS_Ticket_Core();
        // get current user display_name
        $this->loader->add_filter('djs_ticket_get_current_username', $plugin_admin, 'djs_ticket_get_current_username');
        $this->loader->add_filter('djs_ticket_option_for_text', $plugin_admin, 'djs_ticket_option_for_text');
        $this->loader->add_filter('djs_ticket_option_for_checkbox', $plugin_admin, 'djs_ticket_option_for_checkbox');
        $this->loader->add_action('djs_ticket_prevent_resubmit_form', $plugin_admin, 'djs_ticket_prevent_resubmit_form');
        // add djs_ticket post_type
        $this->loader->add_action('init', $plugin_admin, 'djs_ticket_post_type_ticket');
        // add requirement post meta boxes to djs_ticket post_type
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'djs_ticket_meta_box_ticket_status');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'djs_ticket_meta_box_submitted_ticket_by');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'djs_ticket_meta_box_ticket_conversation');
        // add tickets endpoint to my-account wooCommerce
        $this->loader->add_action('init', $plugin_admin, 'djs_ticket_endpoint_tickets');
        $this->loader->add_filter('query_vars', $plugin_admin,'djs_ticket_query_vars_tickets');
        $this->loader->add_action('woocommerce_account_tickets_endpoint', $plugin_admin, 'djs_ticket_template_tickets');
        // add view-ticket to my-account wooCommerce
        $this->loader->add_action('init', $plugin_admin, 'djs_ticket_endpoint_view_ticket');
        $this->loader->add_filter('query_vars', $plugin_admin,'djs_ticket_query_vars_view_ticket');
        $this->loader->add_action('woocommerce_account_view-ticket_endpoint', $plugin_admin, 'djs_ticket_template_view_ticket');
        // add add-ticket to my-account wooCommerce
        $this->loader->add_action('init', $plugin_admin, 'djs_ticket_endpoint_add_ticket');
        $this->loader->add_filter('query_vars', $plugin_admin,'djs_ticket_query_vars_add_ticket');
        $this->loader->add_action('woocommerce_account_add-ticket_endpoint', $plugin_admin, 'djs_ticket_template_add_ticket');
        // add tickets and view-ticket submenu filter
        $this->loader->add_filter('woocommerce_account_menu_items', $plugin_admin,  'djs_ticket_menu_myaccount');
        $this->loader->add_action('djs_ticket_notify_selected_users_admin', $plugin_admin, 'djs_ticket_notify_selected_users_admin', 10, 3);
        $this->loader->add_filter('djs_ticket_get_user_login', $plugin_admin, 'djs_ticket_get_user_login');
        $this->loader->add_filter('djs_ticket_get_user_upload_url', $plugin_admin, 'djs_ticket_get_user_upload_url');
        $this->loader->add_filter('djs_ticket_is_localhost', $plugin_admin, 'djs_ticket_is_localhost');
        $this->loader->add_filter('djs_ticket_userLogin_to_userEmail', $plugin_admin, 'djs_ticket_userLogin_to_userEmail');

    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new DJS_Ticket_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'djs_ticket_upload_ticket_image', $plugin_public, 'djs_ticket_upload_ticket_image' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Djs_ticket_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
