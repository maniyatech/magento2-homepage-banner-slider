<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Model\ResourceModel;

class Bannerslider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Id variable
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Initialize construct ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider
     */
    public function _construct()
    {
        $this->_init('homepagebannerslider', 'id');
    }
}
