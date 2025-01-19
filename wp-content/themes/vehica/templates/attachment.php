<div class="vehica-attachment-page">
    <div class="vehica-attachment-page__inner">
        <?php
        global $post;
        echo apply_filters('the_content', $post->post_content);
        ?>
    </div>
</div>