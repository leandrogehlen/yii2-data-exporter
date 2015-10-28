<?php
/**
 * Created by PhpStorm.
 * User: leandro
 * Date: 28/10/2015
 * Time: 10:15
 */

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