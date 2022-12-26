<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://deljoosoft.com
 * @since      1.0.0
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/public
 * @author     Navid Salahian <salahian.navid@gmail.com>
 */
class Djs_ticket_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Djs_ticket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Djs_ticket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/djs_ticket-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Djs_ticket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Djs_ticket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script('djs_ticket_tinymce-editor',  plugin_dir_url( __FILE__ ) . 'js/tinymce-min.js', null, null, false);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/djs_ticket-public.js', array( 'jquery' ), $this->version, true );
	}

    public function djs_ticket_upload_ticket_image($files)
    {


        $ticket_image_upload_dir =  DJS_TICKET_USER_IMAGE_UPLOAD_DIR . apply_filters('djs_ticket_get_current_username', null);
        if (!file_exists($ticket_image_upload_dir)) {
            wp_mkdir_p($ticket_image_upload_dir);
        }
        $flag_error = [];
        for ($x = 0; $x < count($files['name']); $x++) {
            $file_name = rand(0, 1000) . $files['name'][$x];
            $file_tmp = $files['tmp_name'][$x];
            $file_target = $ticket_image_upload_dir . DIRECTORY_SEPARATOR . $file_name;

            if (move_uploaded_file($file_tmp, $file_target)) {
                $flag_error[] = $file_name;
            } else {
                $flag_error = [];
            }
        }
        return $flag_error;
	}

}
