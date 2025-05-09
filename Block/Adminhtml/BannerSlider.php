<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Block class for handling banner slider configurations.
 */
class BannerSlider extends Template
{
    /**
     * XML path to allowed extensions in system configuration.
     */
    public const XML_PATH_ALLOWED_EXTENSIONS = 'homepagebannerslider/general/allowed_formats';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * BannerSlider constructor.
     *
     * @param Template\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get allowed image extensions from system configuration.
     *
     * @return string|null
     */
    public function getAllowedExtensions(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ALLOWED_EXTENSIONS,
            ScopeInterface::SCOPE_STORE
        );
    }
}
