<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use ManiyaTech\HomepageBannerSlider\Model\ImageUploader;

/**
 * Class Uploader
 * Handles AJAX image upload to temporary directory in admin form.
 */
class Uploader extends Action
{
    /**
     * @var ImageUploader
     */
    private ImageUploader $imageUploader;

    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Check if user has permissions to upload
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('ManiyaTech_HomepageBannerSlider::upload');
    }

    /**
     * Execute image upload and return JSON response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        try {
            $imageId = $this->getRequest()->getParam('target_element_id');
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

            $session = $this->_getSession();
            $result['cookie'] = [
                'name'     => $session->getName(),
                'value'    => $session->getSessionId(),
                'lifetime' => $session->getCookieLifetime(),
                'path'     => $session->getCookiePath(),
                'domain'   => $session->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = [
                'error'     => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }

        return $resultJson->setData($result);
    }
}
