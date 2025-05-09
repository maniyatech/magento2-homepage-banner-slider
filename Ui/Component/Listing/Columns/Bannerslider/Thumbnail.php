<?php
/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Ui\Component\Listing\Columns\Bannerslider;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    private const ALT_FIELD = 'title';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * Thumbnail constructor.
     *
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source for image thumbnail column.
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (empty($item[$fieldName])) {
                continue;
            }

            $imageUrl = $this->storeManager->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . 'ManiyaTech/Slider/' . ltrim($item[$fieldName], '/');

            $item[$fieldName . '_src'] = $imageUrl;
            $item[$fieldName . '_alt'] = $this->getAlt($item);
            $item[$fieldName . '_title'] = $item[self::ALT_FIELD] ?? '';
            $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                'homepagebannerslider/bannerslider/edit',
                ['id' => $item['id']]
            );
            $item[$fieldName . '_orig_src'] = $imageUrl;
        }

        return $dataSource;
    }

    /**
     * Get alt text for the image column.
     *
     * @param  array $row
     * @return string|null
     */
    protected function getAlt(array $row): ?string
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return $row[$altField] ?? null;
    }
}
