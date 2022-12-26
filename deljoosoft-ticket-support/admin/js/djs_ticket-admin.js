

function make_error(text = "Hi", _class ="success"){
    let dashicons_type = _class === "success" ? "dashicons-yes-alt" : "dashicons-dismiss";
    jQuery(".djs-snackbar").remove();
    let snackbar = `<div class="djs-snackbar"><span class="dashicons ${dashicons_type}"></span><span class="text">${text}</span></div>`;
    _class += " active";
    let layout = jQuery(snackbar).addClass(_class);
    jQuery("#wpfooter").append(layout);
    setTimeout(()=>{jQuery(".djs-snackbar").removeClass("active")}, 4000);
}

jQuery(document).ready(function () {

    jQuery(document).on("click", "#djs_submit_reply_ticket_admin", function (e){

        e.preventDefault();
        let parent_ticket_id = jQuery(".ticket-items").data("parent");
        let ticket_nonce = jQuery("#djs_submit_reply_ticket_admin_nonce").val();
        let ticket_content = tinyMCE.editors['mettaabox_ID'].getContent();
        parent_ticket_id = Number.isInteger(parent_ticket_id) ? parent_ticket_id : 0;
        if(parent_ticket_id === 0) {
            make_error('An error was occurred, please report this issue to website supporter', 'danger');
            return;
        }
        if(ticket_content === ""){
            make_error('The ticket body (editor) cannot be null, please fill the field.', 'danger');
            return;
        }
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'djs_submit_reply_ticket_admin',
                ticket_parent: parent_ticket_id,
                ticket_nonce: ticket_nonce,
                ticket_content: ticket_content
            },
            success: function (response) {
                make_error(response, 'success');
                window.location.reload();
            },
            error: function (response) {
                console.log(response);
                make_error(response, 'danger');
            }
        });

    })

});