<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://deljoosoft.com
 * @since      1.0.0
 *
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Djs_ticket
 * @subpackage Djs_ticket/includes
 * @author     Navid Salahian <salahian.navid@gmail.com>
 */
class DJS_Ticket_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        delete_option('djs_ticket_flush_rewrite');
        delete_option('djs_ticket_customer_notify_subject');
        delete_option('djs_ticket_customer_notify_body');
	}

}
