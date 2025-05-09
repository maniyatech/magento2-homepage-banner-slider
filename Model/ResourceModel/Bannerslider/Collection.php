<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Id variable
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Initialize construct
     *
     * Initialize Bannerslider models
     */
    public function _construct()
    {
        $this->_init(
            \ManiyaTech\HomepageBannerSlider\Model\Bannerslider::class,
            \ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider::class
        );
    }
}
