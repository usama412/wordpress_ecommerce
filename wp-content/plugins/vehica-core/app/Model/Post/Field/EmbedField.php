<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Embed\Adapters\Adapter;
use Embed\Embed;
use Exception;
use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;

/**
 * Class EmbedField
 * @package Vehica\CustomField\Fields
 */
class EmbedField extends Field
{
    const KEY = 'embed';
    const URL = 'url';
    const EMBED = 'embed';
    const ALLOW_RAW_HTML = 'vehica_allow_raw_html';

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            self::IS_REQUIRED,
            self::PANEL_PLACEHOLDER,
            self::ALLOW_RAW_HTML,
        ];
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
     * @param array $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        if (!isset($value[self::URL], $value[self::EMBED])) {
            $value = [
                self::URL => '',
                self::EMBED => ''
            ];
        }

        if (!empty($value[self::URL])) {
            $embed = wp_oembed_get($value[self::URL]);

            if (!$embed && strpos($value[self::URL], '.mp4') !== false) {
                $embed = do_shortcode('[video src="' . $value[self::URL] . '"]');
            }

            $value[self::EMBED] = $embed;
            if (empty($value[self::EMBED]) && $this->allowRawHtml()) {
                $value[self::EMBED] = stripslashes_deep($value[self::URL]);
            }
        }

        $fieldsUser->setMeta($this->getKey(), $value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return array
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return [
                self::URL => '',
                self::EMBED => ''
            ];
        }

        $value = $fieldsUser->getMeta($this->getKey());

        if (!isset($value[self::URL], $value[self::EMBED])) {
            return [
                self::URL => '',
                self::EMBED => ''
            ];
        }

        return $value;
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return string
     */
    public function getEmbedUrl(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);
        return isset($value[self::URL]) ? $value[self::URL] : '';
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return string
     */
    public function getEmbedCode(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);

        return isset($value[self::EMBED]) ? $value[self::EMBED] : '';
    }

    /**
     * @param array $value
     * @return string
     */
    public function getDisplayValue($value)
    {
        return isset($value[self::EMBED]) ? $value[self::EMBED] : '';
    }

    /**
     * @param string $url
     * @return string
     */
    public function getEmbed($url)
    {
        try {
            $embed = Embed::create($url);
        } catch (Exception $e) {
            return '';
        }

        foreach ($embed->getProviders() as $provider) {
            if (strpos($provider->getProviderName(), 'YouTube') !== false) {
                return self::getYouTubeEmbed($embed, $this->getOptions());
            }
        }

        return $embed->getCode();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param Adapter $embed
     * @param array $settings
     * @return string
     */
    public static function getYouTubeEmbed(Adapter $embed, $settings)
    {
        $url = explode('?', $embed->getUrl());
        if (!isset($url[1])) {
            return $embed->getCode();
        }
        $params = explode('&', $url[1]);
        $videoId = '';
        foreach ($params as $param) {
            if (strpos($param, '=') === false) {
                continue;
            }
            $arg = explode('=', $param);
            if (isset($arg[0], $arg[1]) && $arg[0] === 'v') {
                $videoId = $arg[1];
            }
        }
        if (empty($videoId)) {
            return $embed->getCode();
        }

        ob_start();
        ?>
        <iframe
                width="480"
                height="270"
                src="<?php echo esc_url('https://www.youtube.com/embed/' . $videoId . '?feature=oembed'); ?>"
                allow="encrypted-media"
                allowfullscreen
        >
        </iframe>
        <?php
        return ob_get_clean();
    }

    /**
     * @param int $allow
     */
    public function setAllowRawHtml($allow)
    {
        $this->setMeta(self::ALLOW_RAW_HTML, (int)$allow);
    }

    /**
     * @return bool
     */
    public function allowRawHtml()
    {
        return !empty($this->getMeta(self::ALLOW_RAW_HTML));
    }

}