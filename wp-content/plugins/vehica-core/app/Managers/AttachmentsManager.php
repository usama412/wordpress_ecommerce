<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Attachment;

/**
 * Class AttachmentsManager
 * @package Vehica\Managers
 */
class AttachmentsManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica/attachments/info', [$this, 'attachmentsInfo']);
    }

    public function attachmentsInfo()
    {
        if (empty($_POST['attachments']) || !is_array($_POST['attachments'])) {
            return;
        }

        echo json_encode(Attachment::getAttachments($_POST['attachments'])->map(static function ($attachment) {
            /* @var Attachment $attachment */
            return [
                'mcID' => $attachment->getId(),
                'name' => $attachment->getName(),
                'url' => $attachment->getIconUrl(),
                'src' => $attachment->getIconUrl(),
            ];
        })->all());
    }

}