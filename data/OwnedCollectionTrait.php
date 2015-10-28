<?php

namespace leandrogehlen\exporter\data;

use Yii;

/**
 * OwnedCollectionTrait implements the common methods for classes that contains collection properties.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
trait OwnedCollectionTrait
{
    /**
     * @param array  $collection collection configuration. Each array element represents
     *                           the configuration for one particular element.
     * @param string $class      the element class name
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function initCollection(&$collection, $class)
    {
        foreach ($collection as $i => $element) {
            $element = Yii::createObject(array_merge([
                'class' => $class,
            ], $element));

            $collection[$i] = $element;
        }
    }
}
