<?php


namespace Vehica\Model\Post\Field;


use Vehica\Core\Collection;
use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Model\Post\Attachment;
use WP_Post;
use WP_Query;

/**
 * Class AttachmentsField
 * @package Vehica\Model\Post\Field
 */
class AttachmentsField extends Field
{
    const KEY = 'attachments';

    /**
     * @param FieldsUser $fieldsUser
     * @param string $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        if (isset($_POST['vehica_source']) && $_POST['vehica_source'] === 'backend' && empty($_POST[$this->getKey() . '_loaded'])) {
            return;
        }

        $attachmentIds = explode(',', $value);
        global $wpdb;
        $table = $wpdb->prefix . 'posts';

        if (!empty($attachmentIds) && is_array($attachmentIds)) {
            foreach ($attachmentIds as $attachmentId) {
                $wpdb->update($table,
                    [
                        'post_parent' => $fieldsUser->getId(),
                    ],
                    [
                        'ID' => $attachmentId,
                    ]
                );
            }
        }

        $fieldsUser->setMeta($this->getKey(), $value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Attribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new Attribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return array
     * @noinspection DuplicatedCode
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return [];
        }

        $attachments = $fieldsUser->getMeta($this->getKey());
        if (empty($attachments)) {
            return [];
        }

        $attachments = explode(',', $attachments);
        if (empty($attachments) || !is_array($attachments)) {
            return [];
        }

        return Collection::make($attachments)
            ->map(static function ($attachmentId) {
                return (int)$attachmentId;
            })->filter(static function ($attachmentId) {
                return !empty($attachmentId) && get_post($attachmentId) instanceof WP_Post;
            })->all();
    }

    /**
     * @param array|int[] $value
     * @return Collection
     */
    public function getDisplayValue($value)
    {
        if (empty($value) || !is_array($value)) {
            return Collection::make();
        }

        $query = new WP_Query([
            'post_status' => 'any',
            'post_type' => 'attachment',
            'post__in' => $value
        ]);

        return Collection::make($query->posts)->map(static function ($attachment) {
            return new Attachment($attachment);
        });
    }

}