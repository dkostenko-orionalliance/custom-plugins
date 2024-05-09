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

namespace OrionAlliance\NewModule\Ui\Component\Listing;

class ExportButton extends \Magento\Ui\Component\ExportButton
{
    /**
     * Prepare config options
     */
    public function prepare()
    {
        $context = $this->getContext();
        $config = $this->getData('config');
        if (isset($config['options']['csv'])) {
            $config['options']['csv']['url'] =  $config['options']['cvs']['url'];
            unset($config['options']['cvs']);
        }
        if (isset($config['options'])) {
            $options = [];
            foreach ($config['options'] as $option) {
                $additionalParams = $this->getAdditionalParams($config, $context);
                $option['url'] = $this->urlBuilder->getUrl($option['url'], $additionalParams);
                $options[] = $option;
            }
            $config['options'] = $options;
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
