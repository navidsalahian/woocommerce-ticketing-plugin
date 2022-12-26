
<section class="ticket-submit">
    <div class="ticket-submit__inner">
        <h2 class="mb-3 mt-8">Create the new ticket</h2>
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
        <form action="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ) . 'tickets/'; ?>" method="post" enctype="multipart/form-data">
            <input class="ticket-subject mb-6" name="ticket_subject" type="text" placeholder="Subject">
            <textarea name="ticket_content"></textarea>
            <?php wp_nonce_field('djs_ticket_submit_ticket', 'djs_ticket_submit_ticket_nonce'); ?>
            <div class="upload__box">
                <div class="upload__btn-box">
                    <label class="upload__btn">
                        <p>Upload images</p>
                        <input name="images[]" type="file" multiple data-max_length="3" class="upload__inputfile">
                    </label>
                </div>
                <div class="upload__img-wrap"></div>
            </div>
            <button id="submit_ticket_btn" class="flex jc-center mx-6" name="djs_submit_ticket">Submit your reply</button>
        </form>
    </div>
</section>

