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
use Magento\Framework\Exception\LocalizedException;
use ManiyaTech\HomepageBannerSlider\Model\BannersliderFactory;
use ManiyaTech\Core\Model\ImageUploader;

/**
 * Class Save
 * Handles saving of banner data from admin form.
 */
class Save extends Action
{
    private const BASE_PATH = 'ManiyaTech/Slider/';

    /**
     * @var BannersliderFactory
     */
    protected BannersliderFactory $bannersliderFactory;

    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploaderModel;

    /**
     * @var string
     */
    public string $basePath;

    /**
     * Constructor
     *
     * @param Context             $context
     * @param BannersliderFactory $bannersliderFactory
     * @param ImageUploader       $imageUploaderModel
     */
    public function __construct(
        Context $context,
        BannersliderFactory $bannersliderFactory,
        ImageUploader $imageUploaderModel,
    ) {
        parent::__construct($context);
        $this->bannersliderFactory = $bannersliderFactory;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->basePath = self::BASE_PATH;
    }

    /**
     * Execute method for saving banner.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /**
         * @var Redirect $resultRedirect
         */
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
                    throw new LocalizedException(__('Invalid banner ID.'));
                }
            }

            // Store ID handling
            $data['store_id'] = isset($data['store_id']) && is_array($data['store_id'])
                ? implode(',', $data['store_id'])
                : 0;

            // Process images
            $data['desktop_image'] = $this->imageUploaderModel->processImage(
                'desktop_image',
                $data,
                $model->getDesktopImage(),
                $this->basePath
            );

            $data['mobile_image'] = $this->imageUploaderModel->processImage(
                'mobile_image',
                $data,
                $model->getMobileImage(),
                $this->basePath
            );

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

        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('An error occurred while saving the banner.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
