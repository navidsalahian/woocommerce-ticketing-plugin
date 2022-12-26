<?php

$current_ticket_id = get_the_ID(); ?>
    <ul class="ticket-items" data-parent="<?php echo $current_ticket_id; ?>">
        <li class="ticket-item">
            <div class="ticket-info">
                <span class="date"><?php echo get_the_date('', $current_ticket_id) ?></span><span class="time"><?php echo get_the_time('', $current_ticket_id); ?></span><span
                        class="respond-user"><?php echo get_the_author_meta('display_name', get_post_field('post_author', $current_ticket_id)); ?></span>
            </div>
            <div class="ticket-content"><?php echo get_the_content(null, false, $current_ticket_id); ?></div>
            <ul class="upload-images list-reset">
                <?php $upload_images = get_post_meta($current_ticket_id, 'djs_ticket_ticket_images', true);
                if(! empty($upload_images)){
                    $base_user_ticket_image_url = apply_filters('djs_ticket_get_user_upload_url', $current_ticket_id) . DIRECTORY_SEPARATOR;
                    foreach ($upload_images as $upload_image){
                        ?>
                        <li class="upload-image"><span class="dashicons dashicons-admin-links"></span><a target="_blank"
                                                                                                         href="<?php echo $base_user_ticket_image_url . $upload_image; ?>">Open image url</a></li>
                    <?php  }
                }
                ?>
            </ul>
        </li>
        <?php $tickets = new WP_Query(array('post_type' => 'djs_ticket_reply', 'post_parent' => $current_ticket_id, 'order' => 'ASC', 'posts_per_page' => -1)); ?>
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
<?php
    wp_editor('', 'mettaabox_ID', $settings = array('textarea_name'=>'MyInputNAME') );
    wp_nonce_field('djs_submit_reply_ticket_admin', 'djs_submit_reply_ticket_admin_nonce');
    echo '<div class="center"><input type="submit" id="djs_submit_reply_ticket_admin" name="djs_submit_reply_ticket_admin" class="button button-primary button-large mt-5" value="Submit reply"></div>';
