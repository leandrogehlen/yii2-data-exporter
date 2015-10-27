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
        $definition = $this->loadDefinition('person-column-size');
        $exporter = new Exporter($definition);

        $content = $exporter->execute();
        $lines = explode("\n", $content);

        $this->assertCount(3, $lines);
        $first = $lines[0];

        $this->assertEquals(39, strlen($first));
        $this->assertEquals("010", substr($first, 0, 3));
        $this->assertEquals("Administra", substr($first, 3, 10));
        $this->assertEquals("Root ", substr($first, 13, 5));
        $this->assertEquals("20/04/1983", substr($first, 18, 10));
        $this->assertEquals("00153000", substr($first, 28, 8));
        $this->assertEquals("Yes", substr($first, 36, 3));
    }


    public function loadDefinition($name)
    {
        return Json::decode(file_get_contents( __DIR__ . "/definitions/$name.json"));
    }

}