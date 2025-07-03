<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * Banner Slider Collection
     *
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Construct method
     *
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     * @param Filesystem        $filesystem
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Filesystem $filesystem
    ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->filesystem         = $filesystem;
        parent::__construct($context);
    }

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        try {
            $collection  = $this->_filter->getCollection($this->_collectionFactory->create());
            $itemsDelete = 0;
            foreach ($collection as $item) {
                $desktopImage = $item->getDesktopImage();
                $mobileImage = $item->getMobileImage();
                if ($desktopImage) {
                    $this->deleteImage($desktopImage);
                }
                if ($mobileImage) {
                    $this->deleteImage($mobileImage);
                }
                $item->delete();
                $itemsDelete++;
            }

            $this->messageManager->addSuccess(
                __('A total of %1 Banner(s) were deleted successfully.', $itemsDelete)
            );
        } catch (Exception $e) {
            $this->messageManager->addError('Something went wrong while deleting the Banners '.$e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
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
