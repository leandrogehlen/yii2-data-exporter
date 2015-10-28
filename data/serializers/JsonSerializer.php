<?php

namespace leandrogehlen\exporter\data\serializers;

use yii\helpers\Json;

/**
 * Formats the given data into json content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class JsonSerializer extends Serializer
{

    /**
     * @inheritdoc
     */
    public $separator = ",";

    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        return '[' . implode(",", $data) . ']';
    }

    /**
     * @inheritdoc
     */
    protected function run($session, $row, $index, $master = [])
    {
        $record = [];

        foreach ($session->columns as $column) {
            $value = $this->extractValue($column, $row);
            $record[$column->name] = $value;
        }

        if (!empty($record)) {

            foreach ($session->sessions as $child) {
                $record[$child->name] = Json::decode($this->serialize($child, $row));
            }
        }

        return Json::encode($record);
    }
}
