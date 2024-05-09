<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Ui\Component\Listing\Columns\Frontend;

class QtyPending extends QtySold
{
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $sellerId = $this->helperData->getCustomerId();
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    $collectionData = $this->collectionFactory->create()
                    ->addFieldToFilter(
                        'mageproduct_id',
                        $item['entity_id']
                    )->addFieldToFilter(
                        'seller_id',
                        $sellerId
                    )->addFieldToFilter(
                        'cpprostatus',
                        0
                    );
                    $data = $collectionData->getAllSoldQty();
                    if (!empty($data)) {
                        $item[$fieldName] = $data['0']['qty'];
                    } else {
                        $item[$fieldName] = 0;
                    }
                }
            }
        }

        return $dataSource;
    }
}
