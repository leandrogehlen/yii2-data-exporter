<?php

namespace leandrogehlen\exporter\serializers;

use yii\helpers\Json;

/**
 * Formats the given data into json content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class JsonSerializer extends HierarchicalSerializer
{
    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        return Json::encode($data);
    }
}
