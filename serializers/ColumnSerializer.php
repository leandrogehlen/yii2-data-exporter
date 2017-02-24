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
     * @var string the column separator
     */
    public $delimiter;

    /**
     * @var string the line break
     */
    public $lineBreak = "\n";

    /**
     * @inheritdoc
     */
    public function serialize($sessions, $master = [])
    {
        $data = [];
        foreach ($sessions as $session) {
            if ($session->visible) {
                $i = 0;
                $provider = $this->exporter->findProvider($session->provider);
                $rows = $this->executeProvider($provider, $master);

                foreach ($rows as $row) {

                    $record = [];
                    foreach ($session->columns as $column) {
                        $value = $this->extractValue($column, $row);
                        $record[] = $value;
                    }

                    $record = implode($this->delimiter, $record);
                    $data[] = $this->beforeSerializeRow($record, $session);
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
        return implode($this->lineBreak, $data);
    }
}
