<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Panel\PanelField;


use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class GalleryPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class GalleryPanelField extends CustomFieldPanelField
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'gallery';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $imageIds = $this->getValue($data);

        foreach ($imageIds as $imageId) {
            update_post_meta($imageId, 'vehica_car_gallery', $car->getId());

            wp_update_post([
                'ID' => $imageId,
                'post_parent' => $car->getId(),
            ]);
        }

        $car->setMeta($this->getKey(), implode(',', $imageIds));
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
            'url' => admin_url('admin-post.php?action=vehica_upload_image'),
            'thumbnailWidth' => 200,
            'addRemoveLinks' => true,
            'dictDefaultMessage' => '<i class="far fa-images"></i> ' . esc_attr(vehicaApp('add_images_string')) . '',
            'parallelUploads' => 1,
            'acceptedFiles' => 'image/*',
            'maxFiles' => vehicaApp('settings_config')->getMaxImageNumber(),
            'maxFilesize' => vehicaApp('settings_config')->getMaxImageFileSize(),
            'timeout' => 180000,
            'maxThumbnailFilesize' => vehicaApp('settings_config')->getMaxImageFileSize(),
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