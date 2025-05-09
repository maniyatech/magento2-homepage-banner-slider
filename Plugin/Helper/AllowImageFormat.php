<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Plugin\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ManiyaTech\Core\Helper\Data as CoreHelper;

/**
 * Plugin to override allowed image formats from system config
 */
class AllowImageFormat
{
    /**
     * XML path to allowed extensions config
     */
    public const XML_PATH_ALLOWED_EXTENSIONS = 'homepagebannerslider/general/allowed_formats';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * AllowImageFormat constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Plugin method to override allowed extensions from system config
     *
     * @param CoreHelper $subject
     * @param array $result
     * @return array
     */
    public function afterGetAllowedExtensions(CoreHelper $subject, array $result): array
    {
        $configValue = $this->scopeConfig->getValue(
            self::XML_PATH_ALLOWED_EXTENSIONS,
            ScopeInterface::SCOPE_STORE
        );

        if ($configValue) {
            return array_map('trim', explode(',', strtolower($configValue)));
        }

        return $result;
    }
}
