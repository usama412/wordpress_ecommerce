<?php
/* @var \Vehica\Widgets\Post\Single\CommentsSinglePostWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaPost = $vehicaCurrentWidget->getPost();
if (!$vehicaPost) {
    return;
}

$vehicaWpPost = $vehicaPost->getModel();

if (post_password_required($vehicaWpPost)) {
    return;
}

$vehicaCommenter = wp_get_current_commenter();
$vehicaRequired = get_option('require_name_email');
$vehicaAriaReq = ($vehicaRequired ? " aria-required='true'" : '');
?>
<div class="vehica-comments">
    <?php
    if (comments_open($vehicaWpPost)) :
        comment_form([
            'logged_in_as' => '',
            'fields' => [
                'author' =>
                    '<p class="comment-form-author"><input id="author" name="author" ' .
                    'placeholder="' . vehicaApp('name_string') . ($vehicaRequired ? '*' : '') . '" type="text" ' .
                    'value="' . esc_attr($vehicaCommenter['comment_author']) . '" size="30" ' . $vehicaAriaReq . ' /></p>',
                'email' =>
                    '<p class="comment-form-email">' .
                    '<input id="email" name="email" placeholder="' . vehicaApp('email_string') . ($vehicaRequired ? '*' : '') .
                    '" type="text" value="' . esc_attr($vehicaCommenter['comment_author_email']) .
                    '" size="30" ' . $vehicaAriaReq . ' /></p>',
                'url' =>
                    '<p class="comment-form-url"><input id="url" name="url" placeholder="' . vehicaApp('website_string') . '" ' .
                    'type="text" value="' . esc_attr($vehicaCommenter['comment_author_url']) . '" size="30" /></p>',
            ],
            'title_reply' => vehicaApp('add_comment_string'),
            'title_reply_to' => vehicaApp('add_comment_to_string') . ' %s',
            'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" ' .
                'placeholder="' . vehicaApp('comment_string') . '" aria-required="true"></textarea></p>',
            'submit_button' => '<button id="submit" class="vehica-button vehica-button--icon vehica-button--icon--submit">' . vehicaApp('post_comment_string') . '</button>'
        ], $vehicaPost->getId());
    endif;

    if ($vehicaPost->hasComments()) :
    ?>
    <h4 id="vehica-comments" class="vehica-comments__heading-top-count">
        <span class="vehica-comments__heading-top-count__title"><?php echo esc_html(vehicaApp('comments_string')); ?></span>
        <span class="vehica-comments__heading-top-count__count">
            <span>(</span><span><?php echo esc_html($vehicaPost->getCommentsCount()); ?></span><span>)</span>
        </span>
    </h4>
    <?php

    wp_list_comments(
    ['style' => 'div',
    'short_ping' => true,
    'avatar_size' => 85,
    'callback' => static function ($comment, $args, $depth) {
    ?>

    <div <?php comment_class(empty($args['has_children']) ? 'vehica-comment' : 'vehica-comment parent'); ?>
            id="comment-<?php comment_ID(); ?>">
        <div class="vehica-comment__content">
            <div class="vehica-comment__avatar">
                <?php
                $vehicaUserImage = \Vehica\Model\User\User::getImageByCommentId(get_comment_ID());
                if ($vehicaUserImage) :?>
                    <img
                            src="<?php echo esc_url($vehicaUserImage); ?>"
                            alt="<?php echo esc_attr(get_comment_author()); ?>"
                    >
                <?php else :
                    echo get_avatar($comment, 85);
                endif;
                ?>
            </div>

            <h4 class="vehica-comment__user-name">
                <?php comment_author_link(); ?>
            </h4>

            <div class="vehica-comment__text">
                <?php comment_text(); ?>
            </div>

            <div class="vehica-comment__footer">

                <?php if ($comment->comment_approved) : ?>
                    <div class="vehica-comment__date">
                        <i class="far fa-calendar"></i>
                        <?php
                        echo esc_html(get_comment_date());
                        ?>
                    </div>

                    <div class="vehica-comment__reply">
                        <?php comment_reply_link(array_merge($args, [
                            'reply_text' => '<span>' . vehicaApp('reply_string') . '</span>',
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
                            'before' => '',
                            'after' => ''
                        ]));
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (!$comment->comment_approved) : ?>
                    <div class="vehica-comment__moderate">
                        <div class="vehica-comment__moderate__inner">
                            <?php echo esc_html(vehicaApp('comment_awaiting_moderation_string')); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
        <?php
        }], $vehicaPost->getComments());
        endif;
        ?>
    </div>
