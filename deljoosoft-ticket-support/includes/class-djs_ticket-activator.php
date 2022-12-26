<?php

/**
 * Fired during plugin activation
 *
 * @link       https://deljoosoft.com
 * @since      1.0.0
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
 * @author     Navid Salahian <salahian.navid@gmail.com>
 */
class DJS_Ticket_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        add_option('djs_ticket_flush_rewrite', 'true');
        add_option('djs_ticket_customer_notify_subject', get_bloginfo('name'));
        add_option('djs_ticket_customer_notify_body', 'Hi, the ticket from '. get_bloginfo('name') .' with ID {ticket_id} from customer {customer_username}  with content {ticket_body} has been posted. '. get_bloginfo('name') .' Ticket Service.');
        add_option('djs_ticket_enable_sending_emails_supporters', 0);
    }

}
