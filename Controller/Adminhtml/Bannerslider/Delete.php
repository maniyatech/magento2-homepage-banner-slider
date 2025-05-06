<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use ManiyaTech\HomepageBannerSlider\Model\BannersliderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var boolean
     */
    protected $_resultFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var BannersliderFactory
     */
    protected $bannersliderFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Delete Construct method
     *
     * @param Context $context
     * @param ManagerInterface $messageManager
     * @param BannersliderFactory $bannersliderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        ManagerInterface $messageManager,
        BannersliderFactory $bannersliderFactory,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->_resultFactory      = $context->getResultFactory();
        $this->messageManager      = $messageManager;
        $this->bannersliderFactory = $bannersliderFactory;
        $this->filesystem          = $filesystem;
    }

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->bannersliderFactory->create()->load($id);
                $title = $model->getTitle();
                $desktopImage = $model->getDesktopImage();
                $mobileImage = $model->getMobileImage();
                if ($desktopImage) {
                    $this->deleteImage($desktopImage);
                }
                if ($mobileImage) {
                    $this->deleteImage($mobileImage);
                }
                $model->delete();
                $this->messageManager->addSuccess(__('<b>%1</b> Banner deleted successfully.', $title));
            } catch (\Exception $e) {
                $this->messageManager->addError('Something went wrong '.$e->getMessage());
            }
        } else {
            $this->messageManager->addError('Banner not found, please try once more.');
        }

        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }

    /**
     * Delete image from media folder
     *
     * @param string|null $imageName
     */
    protected function deleteImage(string $imageName): void
    {
        if (!$imageName) {
            return;
        }

        $mediaPath = 'ManiyaTech/Slider/' . ltrim($imageName, '/');
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        if ($mediaDirectory->isExist($mediaPath)) {
            $mediaDirectory->delete($mediaPath);
        }
    }
}
