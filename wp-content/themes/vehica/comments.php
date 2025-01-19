<?php

if (post_password_required()) {
    return;
}

$vehicaCommenter = wp_get_current_commenter();
$vehicaRequired = get_option('require_name_email');
$vehicaAriaReq = ($vehicaRequired ? " aria-required='true'" : '');
?>
<div class="vehica-comments">
    <?php
    if (comments_open()) :
        comment_form([
            'logged_in_as' => '',
            'fields' => [
                'author' =>
                    '<p class="comment-form-author"><input id="author" name="author" ' .
                    'placeholder="' . esc_html__('Name', 'vehica') . ($vehicaRequired ? '*' : '') . '" type="text" ' .
                    'value="' . esc_attr($vehicaCommenter['comment_author']) . '" size="30" ' . $vehicaAriaReq . ' /></p>',
                'email' =>
                    '<p class="comment-form-email">' .
                    '<input id="email" name="email" placeholder="' . esc_html__('E-mail', 'vehica') . ($vehicaRequired ? '*'
                        : '') .
                    '" type="text" value="' . esc_attr($vehicaCommenter['comment_author_email']) .
                    '" size="30" ' . $vehicaAriaReq . ' /></p>',
                'url' =>
                    '<p class="comment-form-url"><input id="url" name="url" placeholder="' . esc_html__('Website',
                        'vehica')
                    . '" ' .
                    'type="text" value="' . esc_attr($vehicaCommenter['comment_author_url']) . '" size="30" /></p>',
            ],
            'title_reply' => esc_html__('Add comment', 'vehica'),
            'title_reply_to' => esc_html__('Add comment to', 'vehica') . ' %s',
            'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" ' .
                'placeholder="' . esc_html__('Comments', 'vehica')
                . '" aria-required="true"></textarea></p>',
            'submit_button' => '<button id="submit" class="vehica-button vehica-button--icon vehica-button--icon--submit">'
                . esc_html__('Add', 'vehica') . '</button>'
        ]);
    endif;

    if (get_comments_number() > 0) :
    ?>
    <h4 id="vehica-comments" class="vehica-comments__heading-top-count">
        <span class="vehica-comments__heading-top-count__title"><?php esc_html_e('Comments', 'vehica'); ?></span>
        <span class="vehica-comments__heading-top-count__count">
            <span>(</span><span><?php echo esc_html(get_comments_number()); ?></span><span>)</span>
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
                <?php echo get_avatar($comment, 85); ?>
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
                        <?php echo esc_html(get_comment_date()); ?>
                    </div>

                    <div class="vehica-comment__reply">
                        <?php comment_reply_link(array_merge($args, [
                            'reply_text' => '<span>' . esc_html__('Reply', 'vehica') . '</span>',
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
                            <?php esc_html_e('Comment awaiting moderation', 'vehica'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        }]);
        endif;

        the_comments_navigation();
        ?>
    </div>
