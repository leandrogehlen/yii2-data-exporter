<?php

namespace leandrogehlen\exporter\serializers;

/**
 * Formats the given data into an column size or separator char response content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
abstract class HierarchicalSerializer extends Serializer
{

    /**
     * @inheritdoc
     */
    public function serialize($sessions, $master = [])
    {
        $result = [];
        foreach ($sessions as $session) {
            if ($session->visible) {
                $data = [];

                $provider = $this->exporter->findProvider($session->provider);
                $rows = $this->executeProvider($provider, $master);

                $owner = $session->owner;
                $sameProvider = ($owner !== null) ? ($session->provider == $owner->provider) : false;

                foreach ($rows as $row) {
                    $record = [];

                    if ($sameProvider) {
                        $row = $master;
                    }

                    foreach ($session->columns as $column) {
                        $value = $this->extractValue($column, $row);
                        $record[$column->name] = $value;
                    }

                    $record = $this->beforeSerializeRow($record, $session);
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
}
