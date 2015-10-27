<?php

namespace leandrogehlen\exporter\tests;

use leandrogehlen\exporter\data\Exporter;
use leandrogehlen\exporter\tests\fixtures\PersonFixture;
use yii\helpers\Json;

class DataTest extends TestCase
{

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'persons' => PersonFixture::className()
        ];
    }


    public function testColumnSize()
    {
        $definition = $this->loadDefinition('column-size');
        $exporter = new Exporter($definition);

        $content = $exporter->execute();
        $lines = explode("\n", $content);

        $this->assertCount(3, $lines);
        $first = $lines[0];

        $this->assertEquals(33, strlen($first));
        $this->assertEquals("Administra", substr($first, 0, 10));
        $this->assertEquals("Root ", substr($first, 10, 5));
        $this->assertEquals("20/04/1983", substr($first, 15, 10));
        $this->assertEquals("01000.00", substr($first, 25, 8));
    }


    public function loadDefinition($name)
    {
        return Json::decode(file_get_contents( __DIR__ . "/definitions/$name.json"));
    }

}