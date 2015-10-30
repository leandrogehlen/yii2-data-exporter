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

                $provider = $this->exporter->findProvider($session->providerName);
                $rows = $this->executeProvider($provider, $master);

                $owner = $session->owner;
                $sameProvider = ($owner !== null) ? ($session->providerName == $owner->providerName) : false;

                foreach ($rows as $row) {
                    $record = [];

                    if ($sameProvider) {
                        $row = $master;
                    }

                    foreach ($session->columns as $column) {
                        $value = $this->extractValue($column, $row);
                        $record[$column->name] = $value;
                    }

                    $children = $this->serialize($session->sessions, $row);
                    if (!empty($children)) {
                        $record = array_merge($record, $children);
                    }

                    if ($sameProvider) {
                        $data = $record;
                        break;
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
