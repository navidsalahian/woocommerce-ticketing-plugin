<?php

$ticket_status = get_post_meta($post->ID, 'djs_ticket_ticket_status', true);
$ticket_status = empty($ticket_status) ? '' : $ticket_status;
$options_arr = array('Open', 'Replied', 'In progress', 'Closed');
echo '<div class="ticket-status"><select name="djs_ticket_status">';
foreach ($options_arr as $option) {
    $lower_underlined = str_replace(' ', '_', strtolower($option));
    $selected = $lower_underlined == $ticket_status;
    if ($selected) {
        echo '<option selected="selected" value="' . str_replace(' ', '_', strtolower($option)) . '">' . $option . '</option>';
    } else {
        echo '<option value="' . str_replace(' ', '_', strtolower($option)) . '">' . $option . '</option>';
    }
}
echo '</select></div>';
