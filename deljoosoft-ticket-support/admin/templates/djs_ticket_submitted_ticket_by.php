<?php
$submitted_user = get_post_meta($post->ID, 'djs_ticket_submitted_ticket_by', true);
$submitted_user = empty($submitted_user) ? '' : $submitted_user; ?>
<div class="wrap">
    <input class="cursor-forbidden" type="text" disabled value="<?php echo $submitted_user; ?>">
</div>