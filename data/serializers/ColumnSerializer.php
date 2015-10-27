<?php

namespace leandrogehlen\exporter\data\serializers;

/**
 * Formats the given data into an column size or separator char response content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class ColumnSerializer extends Serializer
{

    /**
     * @inheritdoc
     */
    protected function run($session, $row, $index)
    {
        $record = [];
        $data = [];

        foreach ($session->columns as $column) {
            $value = $this->extractValue($column, $row);
            $record[] = $value;
        }

        if (!empty($record)) {
            $data[] = implode($this->exporter->charDelimiter, $record);
        }

        foreach ($session->sessions as $child) {
            $data[] = $this->serialize($child);
        }

        return implode("\n", $data);
    }
}