<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Model;

class Bannerslider extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize construct ManiyaTech\HomepageBannerSlider\Model\Bannerslider
     */
    public function _construct()
    {
        $this->_init(\ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider::class);
    }
}
