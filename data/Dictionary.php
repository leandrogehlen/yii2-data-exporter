<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the layout dictionary element.
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
    public $align;

    /**
     * @var string the character to complete until [[size]]
     */
    public $charComplete;

    /**
     * @var string the display format
     */
    public $format;

    /**
     * @var int the column width
     */
    public $size;
}
