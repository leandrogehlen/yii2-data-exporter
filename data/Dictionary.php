<?php

namespace leandrogehlen\exporter\data;

use yii\base\InvalidConfigException;

/**
 * Represents the layout dictionary element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Dictionary extends Variable
{
    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';
    const ALIGN_BOTH = 'both';

    /**
     * @var string the alignment of value
     */
    public $align = self::ALIGN_LEFT;

    /**
     * @var string the character to complete until [[size]]
     */
    public $charComplete;

    /**
     * @var string the display format
     */
    public $format;

    /**
     * @var integer the column width
     */
    public $size;

    /**
     * Converts the [[align]] property to valid padding type
     * @see {@link http://php.net/manual/pt_BR/function.str-pad.php php manual}.
     * @throws InvalidConfigException
     * @return int
     */
    public function getPadding()
    {
        switch ($this->align) {
            case Dictionary::ALIGN_LEFT:
                return STR_PAD_LEFT;
            case Dictionary::ALIGN_RIGHT:
                return STR_PAD_RIGHT;
            case Dictionary::ALIGN_BOTH:
                return STR_PAD_BOTH;
            default:
                throw new InvalidConfigException('The "align" property must be valid');
        }
    }
}