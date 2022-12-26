
<?php

do_action('djs_ticket_prevent_resubmit_form', 'djs_ticket_prevent_resubmit_form');
$ticket_id = intval(get_query_var('view-ticket'));
$user_id = get_current_user_id();
$user_login = get_userdata($user_id)->user_login;
$ticket_submitted_by = get_post_meta($ticket_id, 'djs_ticket_submitted_ticket_by', true);


$ticket_reply = $_POST['submit_reply'];
if (isset($ticket_reply)) {
    if (wp_verify_nonce($_POST['djs_ticket_submit_ticket_reply_nonce'], 'djs_ticket_submit_ticket_reply')) {
        $op_status = wp_insert_post(array(
            'post_content' => sanitize_textarea_field($_POST['ticket_content']),
            'post_type' => 'djs_ticket_reply',
            'post_status' => 'publish',
            'post_parent' => $ticket_id,
        ));
        if(is_wp_error(false)){
            echo '<div class="alert danger">There is an error to submit this ticket.</div>';
        }else{
            $images = apply_filters('djs_ticket_upload_ticket_image', $_FILES['images']);
            if( count($images) == 0 && $_FILES['images']['size'][0] > 0 ){
                echo '<div class="alert danger">The images was not uploaded successfully.</div>';
            }else {
                update_post_meta($op_status, 'djs_ticket_reply_images', $images);
            }
            update_post_meta($ticket_id, 'djs_ticket_ticket_status', 'open');
            update_post_meta($op_status, 'djs_ticket_reply_type', 'by_customer');
            do_action('djs_ticket_notify_selected_users_admin', $ticket_id, sanitize_textarea_field($_POST['ticket_content']), $user_login);
            echo '<div class="alert success">The ticket reply was submitted successfully.</div>';
        }
    }
}

if ($ticket_id == 0 || is_null(get_post($ticket_id)) || get_post_type($ticket_id) != 'djs_ticket' || has_post_parent($ticket_id) || get_post_status($ticket_id) != "publish" || $ticket_submitted_by != $user_login) {
    echo '<h2 class="center">Wrong ticket address!</h2>';
} else {
    ?>
    <section class="ticket-area">
        <div class="ticket-area__inner">
            <h2><?php echo get_the_title($ticket_id) ?></h2>
            <div class="info">
                Ticket Status: <span class="ticket-status <?php echo get_post_meta($ticket_id, 'djs_ticket_ticket_status', true); ?>"><?php echo str_replace('_', ' ', ucfirst(get_post_meta($ticket_id, 'djs_ticket_ticket_status', true))); ?></span>
            </div>
            <ul class="ticket-items">
                <li class="ticket-item">
                    <div class="ticket-info">
                        <span class="date"><?php echo get_the_date('', $ticket_id) ?></span><span class="time"><?php echo get_the_time('', $ticket_id); ?></span><span
                            class="respond-user"><?php echo get_the_author_meta('display_name', get_post_field('post_author', $ticket_id)); ?></span>
                    </div>
                    <div class="ticket-content"><?php echo get_the_content(null, false, $ticket_id); ?></div>
                    <ul class="upload-images list-reset">
                        <?php $upload_images = get_post_meta($ticket_id, 'djs_ticket_ticket_images', true);
                        if(! empty($upload_images)){
                            $base_user_ticket_image_url = apply_filters('djs_ticket_get_user_upload_url', $ticket_id) . DIRECTORY_SEPARATOR;
                            foreach ($upload_images as $upload_image){
                                ?>
                                <li class="upload-image"><span class="dashicons dashicons-admin-links"></span><a target="_blank"
                                                                                                                 href="<?php echo $base_user_ticket_image_url . $upload_image; ?>">Open image url</a></li>
                            <?php  }
                        }
                        ?>
                    </ul>
                </li>
                <?php $tickets = new WP_Query(array('post_type' => 'djs_ticket_reply', 'post_parent' => $ticket_id, 'order' => 'ASC', 'posts_per_page' => -1)); ?>
                <?php while ($tickets->have_posts()): $tickets->the_post(); ?>
                    <?php
                    $reply_type = get_post_meta(get_the_ID(), 'djs_ticket_reply_type', true);
                    $reply_type = isset($reply_type) ?   $reply_type : '';
                    if(!empty($reply_type)){
                        $reply_type =  $reply_type == 'by_customer' ? '' : 'response';
                    }
                    ?>
                    <li class="ticket-item <?php echo $reply_type?>">
                        <div class="ticket-info"><span class="id"><?php echo '#'. get_the_ID(); ?></span><span class="date"><?php echo get_the_date() ?></span><span class="time"><?php echo get_post_time( 'H:i' , true);; ?></span><span
                                class="respond-user"><?php echo get_the_author_meta('display_name', get_post_field('post_author', get_the_ID())); ?></span>
                        </div>
                        <div class="ticket-content"><?php echo get_the_content(); ?></div>
                        <ul class="upload-images list-reset">
                            <?php $upload_images = get_post_meta(get_the_ID(), 'djs_ticket_reply_images', true);
                            if(! empty($upload_images)){
                                $base_user_ticket_image_url = apply_filters('djs_ticket_get_user_upload_url', get_the_ID()) . DIRECTORY_SEPARATOR;
                                foreach ($upload_images as $upload_image){
                                    ?>
                                    <li class="upload-image"><span class="dashicons dashicons-admin-links"></span><a target="_blank"
                                                                                                                     href="<?php echo $base_user_ticket_image_url . $upload_image; ?>">Open image url</a></li>
                                <?php  }
                            }
                            ?>
                        </ul>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </section>

    <?php if(get_post_meta($ticket_id, 'djs_ticket_ticket_status', true) != "closed"): ?>
    <section class="ticket-submit">
        <div class="ticket-submit__inner">
            <h2 class="mb-3 mt-8">Replying the ticket</h2>
            <script>tinymce.init({
                    selector: 'textarea',
                    menubar: false,
                    statusbar: false,
                    plugins: [
                        'link', 'image', 'lists'
                    ],
                    height: 300,
                    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
                });</script>
            <form action="" method="post" enctype="multipart/form-data">
                <textarea name="ticket_content"></textarea>
                <?php wp_nonce_field('djs_ticket_submit_ticket_reply', 'djs_ticket_submit_ticket_reply_nonce'); ?>
                <div class="upload__box">
                    <div class="upload__btn-box">
                        <label class="upload__btn">
                            <p>Upload images</p>
                            <input name="images[]" type="file" multiple data-max_length="3" class="upload__inputfile">
                        </label>
                    </div>
                    <div class="upload__img-wrap"></div>
                </div>
                <button id="submit_ticket_btn" class="flex jc-center mx-6" name="submit_reply">Submit your reply</button>
            </form>
        </div>
    </section>
    <?php endif; ?>

<?php } ?>


