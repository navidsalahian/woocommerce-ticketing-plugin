<?php

Class DJS_Ticket_Core_Handlers{

    public function __construct()
    {

    }
    public static function djs_ticket_submitted_ticket_by__handler($post)
    {
        include DJS_TICKET_ADMIN_TPL . DIRECTORY_SEPARATOR . "djs_ticket_submitted_ticket_by.php";
    }

    public static function djs_ticket_ticket_status__handler($post)
    {
        include DJS_TICKET_ADMIN_TPL . DIRECTORY_SEPARATOR . "djs_ticket_ticket_status.php";
    }

    public static function djs_ticket_ticket_conversations__handler()
    {
        include DJS_TICKET_ADMIN_TPL . DIRECTORY_SEPARATOR . "djs_ticket_ticket_conversations.php";
    }

    public static function djs_ticket_submenu_settings__handler()
    {
        include DJS_TICKET_ADMIN_TPL . DIRECTORY_SEPARATOR . "djs_ticket_submenu_settings.php";
    }


}