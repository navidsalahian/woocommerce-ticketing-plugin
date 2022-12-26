

<?php $user_id = get_current_user_id(); $user_login = get_userdata($user_id)->user_login; ?>


<?php
do_action('djs_ticket_prevent_resubmit_form', 'djs_ticket_prevent_resubmit_form');
if (isset($_POST['djs_submit_ticket'])) {
    if (wp_verify_nonce($_POST['djs_ticket_submit_ticket_nonce'], 'djs_ticket_submit_ticket')) {
        $op_status = wp_insert_post(array(
            'post_content' => sanitize_textarea_field($_POST['ticket_content']),
            'post_type'    => 'djs_ticket',
            'post_status'  => 'publish',
            'post_title'   => sanitize_text_field($_POST['ticket_subject'])
        ));
        if(is_wp_error($op_status)){
            echo '<div class="alert danger">There is an error to submit this ticket.</div>';
        }else{
            $images = apply_filters('djs_ticket_upload_ticket_image', $_FILES['images']);
            if( count($images) == 0 && $_FILES['images']['size'][0] > 0 ){
                echo '<div class="alert danger">The images was not uploaded successfully.</div>';
            }else {
                update_post_meta($op_status, 'djs_ticket_ticket_images', $images);
            }
            update_post_meta($op_status, 'djs_ticket_submitted_ticket_by', get_userdata($user_id)->user_login);
            update_post_meta($op_status, 'djs_ticket_ticket_status', 'open');
            do_action('djs_ticket_notify_selected_users_admin', $op_status, sanitize_textarea_field($_POST['ticket_content']), $user_login);
            echo '<div class="alert success">The ticket was submitted successfully.</div>';
        }
    }
}

?>

<section class="invoices-area">
    <div class="add-new-ticket">
        <a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ) . 'add-ticket/'; ?>">Add new Ticket</a>
    </div>
    <table class="my_account_orders">
        <thead>
        <tr>
            <th><span class="nobr">ID</span></th>
            <th><span class="nobr">Ticket Name</span></th>
            <th><span class="nobr">Date</span></th>
            <th><span class="nobr">Ticket status</span></th>
            <th><span class="nobr">Actions</span></th>
        </tr>
        </thead>
        <?php $tickets = new WP_Query(
            array(
                'post_type' => 'djs_ticket',
                'posts_per_page' => -1,
                'post_parent' => 0,
                'meta_query' => array(
                    array(
                        'key'     => 'djs_ticket_submitted_ticket_by',
                        'value'   => $user_login,
                        'compare' => '=',
                    ),
                ),
            )
        );
        ?>
        <tbody>
        <?php while($tickets->have_posts()): $tickets->the_post(); ?>
            <tr>
                <td><span><?php echo get_the_ID(); ?></span></td>
                <td><a class="ticket_subject" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title()?></a></td>
                <td><span><?php echo get_the_date('j M Y')?></span></td>
                <td><span class="ticket_status <?php echo get_post_meta(get_the_ID(), 'djs_ticket_ticket_status', true); ?>"><?php echo str_replace('_', ' ', ucfirst(get_post_meta(get_the_ID(), 'djs_ticket_ticket_status', true))); ?></span></td>
                <td>
                    <a class="button" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ) . 'view-ticket/' .get_the_ID(); ?>"><span class="dashicons dashicons-visibility"></span>Show</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</section>



