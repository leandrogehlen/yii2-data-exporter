<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the Provider layout element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Provider extends Element
{
    /**
     * @var string|\yii\db\Query the SQL statement to be executed
     */
    public $query;
}
