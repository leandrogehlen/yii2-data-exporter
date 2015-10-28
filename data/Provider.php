<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the Provider layout element.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Provider extends Element
{
    /**
     * @var string the SQL statement to be executed
     */
    public $query;

    /**
     * @var string
     */
    public $masterName;
}
