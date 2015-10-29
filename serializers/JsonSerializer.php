<?php

namespace leandrogehlen\exporter\serializers;

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
    public function serialize($sessions, $master = [])
    {
        $result = [];
        foreach ($sessions as $session) {
            if ($session->exported) {
                $data = [];
                $rows = $this->executeProvider($session->providerName, $master);

                foreach ($rows as $row) {
                    $record = [];
                    foreach ($session->columns as $column) {
                        $value = $this->extractValue($column, $row);
                        $record[$column->name] = $value;
                    }

                    $children = $this->serialize($session->sessions, $row);
                    if (!empty($children)) {
                        $record = array_merge($record, $children);
                    }

                    $data[] = $record;
                }

                $result[$session->name] = $data;
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        return Json::encode($data);
    }
}
