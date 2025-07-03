<?php
/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

namespace ManiyaTech\HomepageBannerSlider\Model;

use ManiyaTech\HomepageBannerSlider\Model\ResourceModel\Bannerslider\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public const BASE_PATH = 'ManiyaTech/Slider/';

    /**
     * @var array
     */
    protected $loadedData = [];

    /**
     * @var string
     */
    public string $basePath;

    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ReadInterface
     */
    protected $mediaDirectory;

    /**
     * @param string                $name
     * @param string                $primaryFieldName
     * @param string                $requestFieldName
     * @param CollectionFactory     $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     * @param array                 $meta
     * @param array                 $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        $this->basePath = self::BASE_PATH;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Provide data to UI form, including image info.
     */
    public function getData(): array
    {
        foreach ($this->collection->getItems() as $model) {
            $data = $model->getData();
            $data['desktop_image'] = $this->prepareImageData($model->getDesktopImage());
            $data['mobile_image'] = $this->prepareImageData($model->getMobileImage());
            $this->loadedData[$model->getId()] = $data;
        }

        return $this->loadedData;
    }

    /**
     * Prepares image data array for UI component
     *
     * @param  string|null $fileName
     * @return array
     */
    protected function prepareImageData(?string $fileName): array
    {
        if (empty($fileName)) {
            return [];
        }

        $filePath = $this->getMediaPath($fileName);
        $size = 0;

        if ($this->mediaDirectory->isExist($filePath)) {
            try {
                $stat = $this->mediaDirectory->stat($filePath);
                $size = $stat['size'] ?? 0;
            } catch (\Exception $e) {
                $size = 0;
            }
        }

        return [[
            'name' => $fileName,
            'url'  => $this->getMediaUrl($fileName),
            'size' => $size,
        ]];
    }

    /**
     * Returns full media URL to file.
     *
     * @param  string $fileName
     * @return string
     */
    protected function getMediaUrl(string $fileName): string
    {
        return $this->storeManager
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . $this->basePath
            . ltrim($fileName, '/');
    }

    /**
     * Returns relative media path.
     *
     * @param  string $fileName
     * @return string
     */
    protected function getMediaPath(string $fileName): string
    {
        return $this->basePath . ltrim($fileName, '/');
    }
}
