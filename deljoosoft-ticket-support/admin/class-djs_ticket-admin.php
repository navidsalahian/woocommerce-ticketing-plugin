<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://deljoosoft.com
 * @since      1.0.0
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/admin
 * @author     Navid Salahian <salahian.navid@gmail.com>
 */
class Djs_ticket_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/djs_ticket-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

        // wp_enqueue_script('djs_ticket_tinymce-editor',  plugin_dir_url( __FILE__ ) . 'js/tinymce-min.js', null, null, false);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/djs_ticket-admin.js', array( 'jquery' ), $this->version, true );

	}
    public function djs_ticket_submenu_settings()
    {
        add_submenu_page('edit.php?post_type=djs_ticket','Settings', 'Settings', 'manage_options', 'djs_ticket_setting', array('DJS_Ticket_Core_Handlers', 'djs_ticket_submenu_settings__handler'));
    }

    public function djs_ticket_submit_reply_ticket_admin()
    {
        if ( !wp_verify_nonce( $_REQUEST['ticket_nonce'], "djs_submit_reply_ticket_admin")) {
            wp_send_json('There is an error to handle this action. Error code #1', 403);
            return;
        }
        $ticket_parent  = sanitize_text_field($_REQUEST['ticket_parent']);
        if($ticket_parent == 0){
            wp_send_json('There is an error to handle this action. Error code #2', 403);
            return;
        }
        $ticket_content = sanitize_textarea_field($_REQUEST['ticket_content']);
        if($ticket_content == ""){
            wp_send_json('There is an error to handle this action. Error code #3', 403);
            return;
        }
        $op_status = wp_insert_post(array(
            'post_content' => sanitize_textarea_field($_POST['ticket_content']),
            'post_type' => 'djs_ticket_reply',
            'post_status' => 'publish',
            'post_parent' => $ticket_parent,
        ));
        if(is_wp_error($op_status)) {
            wp_send_json('There is an error to handle this action. Error code #4', 404);
            return;
        }
        update_post_meta($op_status, 'djs_ticket_reply_type', 'by_admin');
        return wp_send_json('The ticket was submitted successfully.', 200);
    }

    public function djs_ticket_custom_columns($columns)
    {
        return array_merge(
            $columns,
            array(
                'Ticket Status',
                'Ticket User'
            )
        );
    }

    public function djs_ticket_display_custom_columns($column, $post_id)
    {
        switch ($column) {
            case 0:
                $class = get_post_meta($post_id, 'djs_ticket_ticket_status', true);
                $refined = str_replace('_', ' ', ucfirst(get_post_meta($post_id, 'djs_ticket_ticket_status', true)));
                echo '<span class="ticket_status '. $class .'">'. $refined .'</span>';
                break;
            case 1:
                $ticket_user = get_post_meta($post_id, 'djs_ticket_submitted_ticket_by', true);
                echo $ticket_user;
                break;

        }
    }

    public function djs_ticket_save_post($post_id, $post, $update)
    {
        if(isset($_POST['djs_ticket_status'])) {
            update_post_meta($post_id, 'djs_ticket_ticket_status', $_POST['djs_ticket_status']);
        }
    }

    public function djs_ticket_publish_ticket_by_admin( $new_status, $old_status, $post ) {
        if ( $new_status == 'publish' && get_post_type( $post ) == 'djs_ticket' ) {
            if(($old_status == "auto-draft" ||  $old_status == "draft")) {
                $user_id = get_current_user_id();
                $user_login = get_userdata($user_id)->user_login;
                update_post_meta($post->ID, "djs_ticket_submitted_ticket_by", $user_login);
            }
        }
    }



}
