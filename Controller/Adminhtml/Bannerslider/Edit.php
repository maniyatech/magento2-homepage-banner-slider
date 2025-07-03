<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use ManiyaTech\HomepageBannerSlider\Model\BannersliderFactory;

class Edit extends Action
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
     * Constructor
     *
     * @param Action\Context      $context
     * @param PageFactory         $resultPageFactory
     * @param BannersliderFactory $bannersliderFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        BannersliderFactory $bannersliderFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->bannersliderFactory = $bannersliderFactory;
    }

    /**
     * Edit Banner action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->bannersliderFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This banner no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ManiyaTech_HomepageBannerSlider::homepagebannerslider');
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Banner "%1"', $model->getTitle()) : __('New Banner')
        );

        return $resultPage;
    }
}
