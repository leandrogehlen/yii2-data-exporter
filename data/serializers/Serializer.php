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
     * @param array $master
     * @return string
     */
    public function serialize($session, $master = null)
    {
        if (!$session->exported) {
            return [];
        }

        $data = [];
        $rows = $this->executeProvider($session->providerName, $master);

        $i = 0;
        foreach ($rows as $row) {
            $data[] = $this->run($session, $row, $i, $master);
            $session->rows = $i++;
        }

        return implode("\n", $data);
    }

    /**
     * Executes the query statement and returns ALL rows at once.
     * @param string $providerName the provider name
     * @return array
     */
    protected function executeProvider($providerName, $master)
    {
        $params = [];
        $provider = $this->exporter->findProvider($providerName);

        if (preg_match_all('/:\w+/', $provider->query, $matches)) {
            foreach ($matches as $param) {
                $name = substr($param[0], 1);
                $params[$name] = ArrayHelper::getValue($master, $name);
            }
        }

        return $this->exporter->db->createCommand($provider->query, $params)->queryAll();
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
        $size = $column->size;
        $format = $column->format;
        $align = $column->align;

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

        if ($format) {
            $value = $this->exporter->formatter->format($value, $format);
        }

        if ($expression) {
            $value = $this->exporter->evaluate($expression, ['value' => $value]);
        }

        $value = (string) $value;
        if ($this->exporter->charDelimiter !== null && !$size) {
            return $value;
        } else {
            $padding = $this->toPadding($align);
            return ($size > strlen($value)) ? str_pad($value, $size, $charComplete, $padding) : substr($value, 0, $size);
        }
    }

    /**
     * Converts the [[align]] property to valid padding type
     * @see {@link http://php.net/manual/pt_BR/function.str-pad.php php manual}.
     * @param string $align the column alignment
     * @return int
     */
    private function toPadding($align)
    {
        if ($align == Dictionary::ALIGN_RIGHT) {
            return STR_PAD_LEFT;
        } elseif ($align == Dictionary::ALIGN_BOTH) {
            return STR_PAD_BOTH;
        } else {
            return STR_PAD_RIGHT;
        }
    }

    /**
     * Formats the specified row
     * @param Session $session the current session
     * @param array $row
     * @param integer $index
     * @param array $master
     * @return string
     */
    abstract protected function run($session, $row, $index, $master);

}