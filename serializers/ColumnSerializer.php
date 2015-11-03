<?php

namespace leandrogehlen\exporter\serializers;

/**
 * Formats the given data into an column size or separator char response content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class ColumnSerializer extends Serializer
{

    /**
     * @var string
     */
    public $delimiter;

    /**
     * @inheritdoc
     */
    public function serialize($sessions, $master = [])
    {
        $data = [];
        foreach ($sessions as $session) {
            if ($session->visible) {
                $i = 0;
                $provider = $this->exporter->findProvider($session->providerName);
                $rows = $this->executeProvider($provider, $master);

                foreach ($rows as $row) {

                    $record = [];
                    foreach ($session->columns as $column) {
                        $value = $this->extractValue($column, $row);
                        $record[] = $value;
                    }

                    $data[] = implode($this->delimiter, $record);
                    $children = $this->serialize($session->sessions, $row);

                    foreach ($children as $item) {
                        $data[] = $item;
                    }
                    $session->rows = $i++;
                }
            }
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        return implode("\n", $data);
    }
}
