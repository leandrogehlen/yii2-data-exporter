<?php


namespace leandrogehlen\exporter\data;

/**
 * Represents the Variable layout element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Variable extends Element
{
    /**
     * @var string the PHP expression content
     *
     * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
     * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
     */
    public $expression;

    /**
     * @var mixed the variable value
     */
    public $value;
}
