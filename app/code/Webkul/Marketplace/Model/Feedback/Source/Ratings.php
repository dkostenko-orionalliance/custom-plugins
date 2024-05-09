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
 * Class Ratings is used tp get the Feedback rating options
 */
class Ratings implements OptionSourceInterface
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
        $availableOptions = $this->marketplaceFeedback->getAllRatingOptions();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'row_label' =>  '<span class="mpfeedback"><span class="ratingslider-box">
                <span class="rating" style="width:'.$key.'%;"></span>
            </span></span>',
                'value' => $key,
            ];
        }
        return $options;
    }
}
