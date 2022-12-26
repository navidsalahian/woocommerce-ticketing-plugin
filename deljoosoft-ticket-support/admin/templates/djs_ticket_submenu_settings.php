<?php

if(isset($_POST['djs_ticket_settings'])){

    // update customers notify subject
    if(isset($_POST['djs_ticket_notify_users'])) {
        $value = sanitize_text_field($_POST['djs_ticket_notify_users']);
        update_option('djs_ticket_notify_users', $value);
    }

    // update sending emails for supports members
    $value = isset($_POST['djs_ticket_enable_sending_emails_supporters']) ? 1 : 0;
    update_option('djs_ticket_enable_sending_emails_supporters', $value);


    // update customers notify subject
    if(isset($_POST['djs_ticket_customer_notify_subject'])) {
        $value = sanitize_text_field($_POST['djs_ticket_customer_notify_subject']);
        update_option('djs_ticket_customer_notify_subject', $value);
    }

    // update customers notify email
    if(isset($_POST['djs_ticket_customer_notify_email'])) {
        $value = sanitize_email($_POST['djs_ticket_customer_notify_email']);
        update_option('djs_ticket_customer_notify_email', $value);
    }

    // update customers notify body
    if(isset($_POST['djs_ticket_customer_notify_body'])) {
        $value = sanitize_textarea_field($_POST['djs_ticket_customer_notify_body']);
        update_option('djs_ticket_customer_notify_body', $value);
    }



}
?>


<div class="wrap">
    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th>Enable Sending Emails For Supports</th>
                <td>
                    <input type="checkbox" <?php echo apply_filters('djs_ticket_option_for_checkbox', 'djs_ticket_enable_sending_emails_supporters'); ?> name="djs_ticket_enable_sending_emails_supporters">
                </td>
            </tr>
            <tr>
                <th>Notify Users Once A Customer Submit A Ticket Or Reply</th>
                <td>
                    <input placeholder="i.e user1,user2,user3" value="<?php echo apply_filters('djs_ticket_option_for_text', 'djs_ticket_notify_users'); ?>" class="regular-text ltr" type="text" name="djs_ticket_notify_users">
                    <p class="description">Separate UserNames with <code>comma (,)</code></p>
                </td>
            </tr>
            <tr>
                <th>Email Subject For Customers</th>
                <td><input value="<?php echo apply_filters('djs_ticket_option_for_text', 'djs_ticket_customer_notify_subject'); ?>" name="djs_ticket_customer_notify_subject" type="text" class="regular-text"></textarea></td>
            </tr>
            <tr>
                <th>Email Address For Customers</th>
                <td><input value="<?php echo apply_filters('djs_ticket_option_for_text', 'djs_ticket_customer_notify_email'); ?>" name="djs_ticket_customer_notify_email" type="email" class="regular-text"></textarea></td>
            </tr>
            <tr>
                <th>Email Body For Customers</th>
                <td>
                    <textarea class="regular-text" name="djs_ticket_customer_notify_body" id="" cols="30" rows="10"><?php echo apply_filters('djs_ticket_option_for_text', 'djs_ticket_customer_notify_body')?></textarea>
                    <p class="description">Use <code>{ticket_id}</code>, <code>{ticket_body}</code>, <code>{customer_username}</code></p>
                </td>

            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="djs_ticket_settings" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>