<?php
/**
 * ManiyaTech
 *
 * @author   Milan Maniya
 * @package  ManiyaTech_HomepageBannerSlider
 */

declare(strict_types=1);

namespace ManiyaTech\HomepageBannerSlider\Ui\Component\Listing\Columns\Bannerslider;

use Magento\Ui\Component\Listing\Columns\Column;

class CreatedAt extends Column
{
    /**
     * Prepare data source to format the date
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        // Check if the data source contains items
        if (isset($dataSource['data']['items'])) {
            // Loop through each item in the data source
            foreach ($dataSource['data']['items'] as &$item) {
                // Check if 'created_at' exists in the current item
                if (isset($item['created_at'])) {
                    // Get the 'created_at' value
                    $createdAt = $item['created_at'];

                    // Convert the 'created_at' string to a DateTime object
                    $dateTime = new \DateTime($createdAt);

                    // Format the date to 'dd-MM-yyyy'
                    $formattedDate = $dateTime->format('d-m-Y'); // Format as '26-03-2025'

                    // Update the 'created_at' field with the formatted date
                    $item['created_at'] = $formattedDate;
                }
            }
        }

        return $dataSource;
    }
}
