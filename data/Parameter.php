<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the Parameter layout element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Parameter extends Variable
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $required = false;
}