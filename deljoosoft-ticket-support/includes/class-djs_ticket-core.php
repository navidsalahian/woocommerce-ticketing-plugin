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
class DJS_Ticket_Core
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct()
    {

    }

    public function djs_ticket_post_type_ticket()
    {

        $labels = [
            'name' => _x('Tickets', 'Post Type General Name', 'djs-ticket-manager'),
            'singular_name' => _x('Ticket', 'Post Type Singular Name', 'djs-ticket-manager'),
            'menu_name' => __('Tickets', 'djs-ticket-manager'),
            'name_admin_bar' => __('Tickets', 'djs-ticket-manager'),
            'archives' => __('Item Archives', 'djs-ticket-manager'),
            'attributes' => __('Item Attributes', 'djs-ticket-manager'),
            'parent_item_colon' => __('Parent Item:', 'djs-ticket-manager'),
            'all_items' => __('All Items', 'djs-ticket-manager'),
            'add_new_item' => __('Add New Item', 'djs-ticket-manager'),
            'add_new' => __('Add New', 'djs-ticket-manager'),
            'new_item' => __('New Item', 'djs-ticket-manager'),
            'edit_item' => __('Edit Item', 'djs-ticket-manager'),
            'update_item' => __('Update Item', 'djs-ticket-manager'),
            'view_item' => __('View Item', 'djs-ticket-manager'),
            'view_items' => __('View Items', 'djs-ticket-manager'),
            'search_items' => __('Search Item', 'djs-ticket-manager'),
            'not_found' => __('Not found', 'djs-ticket-manager'),
        ];

        $args = [
            'label' => __('Tickets', 'djs-ticket-manager'),
            'description' => __('', 'djs-ticket-manager'),
            'labels' => $labels,
            'supports' => ['title'],
            'hierarchical' => false,
            'public' => false,
            'menu_icon' => 'dashicons-sos',
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 35,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        ];

        register_post_type('djs_ticket', $args);

        if (get_option('djs_ticket_flush_rewrite') == 'true') {
            flush_rewrite_rules();
            delete_option('djs_ticket_flush_rewrite');
        }
    }

    public function djs_ticket_meta_box_submitted_ticket_by()
    {
        add_meta_box('djs_ticket_submitted_ticket_by', 'Assigned Ticket By', array('DJS_Ticket_Core_Handlers', 'djs_ticket_submitted_ticket_by__handler'), 'djs_ticket', 'side');
    }

    public function djs_ticket_meta_box_ticket_status()
    {
        add_meta_box('djs_ticket_ticket_status', 'Ticket Status', array('DJS_Ticket_Core_Handlers', 'djs_ticket_ticket_status__handler'), 'djs_ticket', 'side');
    }

    public function djs_ticket_meta_box_ticket_conversation()
    {
        add_meta_box('djs_ticket_ticket_conversations', 'Conversations', array('DJS_Ticket_Core_Handlers', 'djs_ticket_ticket_conversations__handler'), 'djs_ticket', 'normal');
    }

    // add tickets endpoint after my-account page path
    public function djs_ticket_endpoint_tickets()
    {
        add_rewrite_endpoint('tickets', EP_ROOT | EP_PAGES);
    }

    public function djs_ticket_query_vars_tickets($vars)
    {
        $vars[] = 'tickets';
        return $vars;
    }

    public function djs_ticket_template_tickets()
    {
        include DJS_TICKET_PUBLIC_TPL . DIRECTORY_SEPARATOR . "djs_ticket_template_tickets.php";
    }

    // add view-ticket endpoint after my-account page path
    public function djs_ticket_endpoint_view_ticket()
    {
        add_rewrite_endpoint('view-ticket', EP_ROOT | EP_PAGES);
    }

    public function djs_ticket_query_vars_view_ticket($vars)
    {
        $vars[] = 'view-ticket';
        return $vars;
    }

    public function djs_ticket_template_view_ticket()
    {

        include DJS_TICKET_PUBLIC_TPL . DIRECTORY_SEPARATOR . "djs_ticket_template_view_ticket.php";
    }

    // add add-ticket endpoint after my-account page path
    public function djs_ticket_endpoint_add_ticket()
    {
        add_rewrite_endpoint('add-ticket', EP_ROOT | EP_PAGES);
    }

    public function djs_ticket_query_vars_add_ticket($vars)
    {
        $vars[] = 'add-ticket';
        return $vars;
    }

    public function djs_ticket_template_add_ticket()
    {

        include DJS_TICKET_PUBLIC_TPL . DIRECTORY_SEPARATOR . "djs_ticket_template_add_ticket.php";
    }

    public function djs_ticket_menu_myaccount($items)
    {
        $items['tickets'] = 'Tickets';
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);
        $items['customer-logout'] = $logout;
        return $items;
    }

    public function djs_ticket_get_current_username($value)
    {
        $current_user = wp_get_current_user();
        $current_user = isset($current_user->user_login) ? $current_user->user_login : 'tmp-user_';
        return $current_user;
    }

    public function djs_ticket_is_localhost() {
        $whitelist = ['127.0.0.1', '::1'];
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }

    public function djs_ticket_userLogin_to_userEmail($string_users)
    {
        $string_users = esc_attr($string_users);
        $users = explode(",", $string_users);
        $email_users = "";
        foreach ($users as $user){
            $email_users .= get_user_by('login', $user)->user_email . ",";
        }
        $email_users = rtrim($email_users, ",");
        return $email_users;
    }

    public function djs_ticket_notify_selected_users_admin($ticket_id, $ticket_body, $customer_username)
    {
        $is_localhost = apply_filters('djs_ticket_is_localhost', null);
        $send_supporters_email = intval(get_option('djs_ticket_enable_sending_emails_supporters'));
        if(! $is_localhost && $send_supporters_email > 0) {
            $string_users = get_option('djs_ticket_notify_users');
            $users        = apply_filters('djs_ticket_userLogin_to_userEmail', $string_users);
            $raw_body     = get_option('djs_ticket_customer_notify_body');
            $body         = str_replace(['{ticket_id}', '{ticket_body}', '{customer_username}'], ["\"{$ticket_id}\"", "\"{$ticket_body}\"", "\"{$customer_username}\""], $raw_body);
            $subject      = get_option('djs_ticket_customer_notify_subject');
            $headers      = 'From: ' . get_option('djs_ticket_customer_notify_email') . "\r\n" ;
            $additional_params = "-f" . get_option('djs_ticket_customer_notify_email');
            mail($users, $subject, $body, $headers, $additional_params);
        }
    }


    public function djs_ticket_option_for_checkbox($meta_key)
    {
        $value = intval(get_option($meta_key));
        if ($value > 0) {
            return 'checked';
        }
        return null;
    }
    public function djs_ticket_option_for_text($meta_key)
    {

        $value = get_option($meta_key);
        if (isset($value) && !empty($value)) {
            return $value;
        }
        return null;
    }

    public function djs_ticket_get_user_upload_url($post_id)
    {
        $author_id  = get_post_field ('post_author', $post_id);
        $user_login = get_userdata($author_id)->user_login;
        return DJS_TICKET_USER_IMAGE_UPLOAD_URL . $user_login;
    }

    public function djs_ticket_get_user_login($post_id)
    {
        $author_id  = get_post_field ('post_author', $post_id);
        $user_login = get_userdata($author_id)->user_login;
        return $user_login;
    }

    public function djs_ticket_prevent_resubmit_form()
    {
        echo '<script>';
        echo 'if ( window.history.replaceState ){ window.history.replaceState( null, null, window.location.href ); }';
        echo '</script>';
    }


}
