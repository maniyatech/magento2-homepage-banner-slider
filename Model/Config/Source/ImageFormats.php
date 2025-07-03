<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ImageFormats implements ArrayInterface
{
    /**
     * Image format Options
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'avif', 'label' => __('AVIF')],
            ['value' => 'gif', 'label' => __('GIF')],
            ['value' => 'jpg', 'label' => __('JPG')],
            ['value' => 'jpeg', 'label' => __('JPEG')],
            ['value' => 'png', 'label' => __('PNG')],
            ['value' => 'svg', 'label' => __('SVG')],
            ['value' => 'webp', 'label' => __('WEBP')],
        ];
    }
}
