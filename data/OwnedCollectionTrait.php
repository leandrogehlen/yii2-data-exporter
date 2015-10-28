<?php


namespace leandrogehlen\exporter\data;

use Yii;

/**
 * OwnedCollectionTrait implements the common methods for classes that contains collection properties
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
trait OwnedCollectionTrait
{
    /**
     * @param array $collection collection configuration. Each array element represents
     * the configuration for one particular element.
     * @param string $class the element class name
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidConfigException
     */
    protected function initCollection(&$collection, $class, $config = [])
    {
        foreach ($collection as $i => $element) {
            $element = array_merge($config, $element);
            $element = Yii::createObject(array_merge([
                'class' => $class
            ], $element));

            $collection[$i] = $element;
        }
    }
}
