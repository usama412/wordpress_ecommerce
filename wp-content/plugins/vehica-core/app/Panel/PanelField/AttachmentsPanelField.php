<?php


namespace Vehica\Panel\PanelField;


use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class AttachmentsPanelField
 * @package Vehica\Panel\PanelField
 */
class AttachmentsPanelField extends CustomFieldPanelField
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'attachments';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $attachmentIds = $this->getValue($data);

        foreach ($attachmentIds as $attachmentId) {
            update_post_meta($attachmentId, 'vehica_car_gallery', $car->getId());

            wp_update_post([
                'ID' => $attachmentId,
                'post_parent' => $car->getId(),
            ]);
        }

        $car->setMeta($this->getKey(), implode(',', $attachmentIds));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if ($attributeData === false || !isset($attributeData['value']) || !is_array($attributeData['value'])) {
            return [];
        }

        return Collection::make($attributeData['value'])->map(static function ($imageId) {
            return (int)$imageId;
        })->all();
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getDropZoneConfig()
    {
        return [
            'url' => admin_url('admin-post.php?action=vehica/attachments/upload'),
            'thumbnailWidth' => 200,
            'addRemoveLinks' => true,
            'dictDefaultMessage' => '<i class="fas fa-paperclip"></i> ' . esc_attr(vehicaApp('add_attachments_string')),
            'parallelUploads' => 1,
            'maxFiles' => vehicaApp('settings_config')->getMaxAttachmentNumber(),
            'maxFilesize' => vehicaApp('settings_config')->getMaxAttachmentFileSize(),
            'timeout' => 180000,
        ];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data)
    {
        if (!$this->isRequired()) {
            return true;
        }

        $attributeData = $this->getAttributeData($data);

        return !(
            $attributeData === false
            || !isset($attributeData['value'])
            || !is_array($attributeData['value'])
        );
    }

}