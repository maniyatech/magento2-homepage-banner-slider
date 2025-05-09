<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider\CollectionFactory;
use ManiyaTech\HomepageBannerSlider\Model\BannersliderFactory;

class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var BannersliderFactory
     */
    protected $bannersliderFactory;

    /**
     * MassStatus Constructor
     *
     * @param Context             $context
     * @param Filter              $filter
     * @param CollectionFactory   $collectionFactory
     * @param BannersliderFactory $bannersliderFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        BannersliderFactory $bannersliderFactory
    ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->bannersliderFactory = $bannersliderFactory;
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
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $updated = 0;
            foreach ($collection as $item) {
                $model = $this->bannersliderFactory->create()->load($item['id']);
                $model->setData('status', $this->getRequest()->getParam('status'));
                $model->save();
                $updated++;
            }
            $this->messageManager->addSuccess(__('A total of %1 Banner(s) were updated successfully.', $updated)); // phpcs:ignore
        } catch (Exception $e) {
            $this->messageManager->addError('Something went wrong while deleting the Banner '.$e->getMessage()); // phpcs:ignore
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
