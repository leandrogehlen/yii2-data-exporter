<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the layout event element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Event extends Element
{
    /**
     * @var string the PHP expression content
     *
     * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
     * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
     */
    public $expression;

}