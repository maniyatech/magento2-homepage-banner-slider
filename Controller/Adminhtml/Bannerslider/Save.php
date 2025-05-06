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
use Magento\Framework\View\Result\PageFactory;
use ManiyaTech\HomepageBannerSlider\Model\BannersliderFactory;
use ManiyaTech\HomepageBannerSlider\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Controller\Result\Redirect;

/**
 * Class Save
 * Handles saving of banner data from admin form.
 */
class Save extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var BannersliderFactory
     */
    protected BannersliderFactory $bannersliderFactory;

    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploaderModel;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param BannersliderFactory $bannersliderFactory
     * @param ImageUploader $imageUploaderModel
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BannersliderFactory $bannersliderFactory,
        ImageUploader $imageUploaderModel,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->bannersliderFactory = $bannersliderFactory;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute method for saving banner.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $id = $this->getRequest()->getParam('id');

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $model = $this->bannersliderFactory->create();

            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Invalid banner ID.'));
                }
            }

            // Store ID handling
            $data['store_id'] = isset($data['store_id']) && is_array($data['store_id'])
                ? implode(',', $data['store_id'])
                : 0;

            // Process images
            $data['desktop_image'] = $this->processImage('desktop_image', $data, $model->getDesktopImage());
            $data['mobile_image'] = $this->processImage('mobile_image', $data, $model->getMobileImage());

            $model->setData($data);
            $model->save();

            $this->messageManager->addSuccess(
                $id
                    ? __('The banner <b>%1</b> has been updated.', $model->getTitle())
                    : __('The banner <b>%1</b> has been added.', $model->getTitle())
            );

            $buttonData = $this->getRequest()->getParam('back');
            return match ($buttonData) {
                'edit' => $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]),
                default => $resultRedirect->setPath('*/*/')
            };

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('An error occurred while saving the banner.'));
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Handle image upload and removal.
     *
     * @param string $field
     * @param array $data
     * @param string|null $existingImage
     * @return string
     */
    protected function processImage(string $field, array $data, ?string $existingImage): string
    {
        $imageData = $data[$field][0] ?? null;

        // Image removed via admin
        if (isset($imageData['deleted']) && $imageData['deleted'] == 1) {
            $this->deleteImage($existingImage);
            return '';
        }

        // New image uploaded
        if (isset($imageData['name']) && isset($imageData['url'])) {
            if ($existingImage && $existingImage !== $imageData['name']) {
                $this->deleteImage($existingImage);
            }
            return $this->imageUploaderModel->saveMediaImage($imageData['name'], $imageData['url']);
        }

        // Keep existing
        return $existingImage ?? '';
    }

    /**
     * Delete image from media folder.
     *
     * @param string $imageName
     * @return void
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
