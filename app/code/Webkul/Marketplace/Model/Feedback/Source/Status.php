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
namespace OrionAlliance\NewModule\Model\Feedback\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the Feedback available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\Feedback
     */
    protected $marketplaceFeedback;

    /**
     * Construct
     *
     * @param \OrionAlliance\NewModule\Model\Feedback $marketplaceFeedback
     */
    public function __construct(\OrionAlliance\NewModule\Model\Feedback $marketplaceFeedback)
    {
        $this->marketplaceFeedback = $marketplaceFeedback;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceFeedback->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
