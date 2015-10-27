<?php

namespace leandrogehlen\exporter\data\serializers;

use leandrogehlen\exporter\data\Column;
use leandrogehlen\exporter\data\Dictionary;
use leandrogehlen\exporter\data\Exporter;
use leandrogehlen\exporter\data\Session;
use yii\base\Object;
use yii\helpers\ArrayHelper;


/**
 * Serializer converts DB data into specific before it is sent out
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
abstract class Serializer extends Object
{
    /**
     * @var Exporter
     */
    public $exporter;

    /**
     * Formats the specified session
     * @param Session $session
     * @return array
     */
    public function serialize($session)
    {
        if (!$session->exported) {
            return [];
        }

        $data = [];
        $provider = $this->exporter->findProvider($session->providerName);
        $rows = $this->exporter->db->createCommand($provider->query)->queryAll();

        $i = 0;
        foreach ($rows as $row) {
            $data[] = $this->run($session, $row, $i);
            $session->rows = $i++;
        }

        return implode("\n", $data);
    }

    /**
     * Extract formatted value
     * @param Column $column
     * @param array $row
     * @return mixed|string
     */
    protected function extractValue($column, $row)
    {
        $value = ArrayHelper::getValue($row, $column->name, $column->value);
        $expression = $column->expression;
        $charComplete = $column->charComplete;
        $align = $column->align;
        $size = $column->size;
        $format = $column->format;

        $dictionary = $column->dictionaryName ? $this->exporter->findDictionary($column->dictionaryName) : null;
        if ($dictionary) {
            if ($expression === null) {
                $expression = $dictionary->expression;
            }
            if ($charComplete === null) {
                $charComplete = $dictionary->charComplete;
            }
            if ($align === null) {
                $align = $dictionary->align;
            }
            if ($size === null) {
                $size = $dictionary->size;
            }
            if ($format === null) {
                $format = $dictionary->format;
            }
        }

        if ($expression) {
            $value = $this->exporter->evaluate($expression, ['value' => $value]);
        }

        switch ($align) {
            case Dictionary::ALIGN_LEFT:
                $align = STR_PAD_LEFT;
                break;
            case Dictionary::ALIGN_RIGHT:
                $align = STR_PAD_RIGHT;
                break;
            case Dictionary::ALIGN_BOTH:
                $align = STR_PAD_BOTH;
                break;
        }

        $value = (string) $value;
        $value = ($size > strlen($value)) ? str_pad($value, $size, $charComplete, $align) : substr($value, 0, $size);

        if ($format) {
            $value = $this->exporter->formatter->format($value, $format);
        }

        return $value;
    }

    /**
     * Formats the specified row
     * @param Session $session the current session
     * @param array $row
     * @param integer $index
     * @return array
     */
    protected abstract function run($session, $row, $index);

}