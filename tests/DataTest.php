<?php

namespace leandrogehlen\exporter\tests;

use leandrogehlen\exporter\data\Exporter;
use leandrogehlen\exporter\tests\fixtures\InvoiceFixture;
use leandrogehlen\exporter\tests\fixtures\InvoiceDetailsFixture;
use leandrogehlen\exporter\tests\fixtures\PersonFixture;
use leandrogehlen\exporter\tests\fixtures\ProductFixture;
use yii\helpers\Json;

/**
 * @method array persons(string $key)
 * @method array products(string $key)
 * @method array orders(string $key)
 * @method array items(string $key)
 */
class DataTest extends TestCase
{

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'persons' => PersonFixture::className(),
            'products' => ProductFixture::className(),
            'orders' => InvoiceFixture::className(),
            'items' => InvoiceDetailsFixture::className(),
        ];
    }


    public function testColumnSize()
    {
        $exporter = $this->createExporter('person-column-size');
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

    public function testColumnDelimiter()
    {
        $exporter = $this->createExporter('order-column-delimiter');
        $content = $exporter->execute();

        $lines = explode("\n", $content);
        $this->assertCount(5, $lines);

        $first = explode("|", $lines[0]);
        $this->assertCount(5, $first);
        $this->assertEquals("010", $first[0]);
        $this->assertEquals("001", $first[1]);
        $this->assertEquals(date('Y-m-d'), $first[2]);
        $this->assertEquals("Administrator", $first[3]);
        $this->assertEquals("The first order", $first[4]);

        $second = explode("|", $lines[1]);
        $this->assertCount(5, $second);
        $this->assertEquals("020", $second[0]);
        $this->assertEquals(1, $second[1]);
        $this->assertEquals(2, $second[2]);
        $this->assertEquals(10.00, $second[3]);
        $this->assertEquals(20.00, $second[4]);
    }


    protected function createExporter($name)
    {
        $config = Json::decode(file_get_contents( __DIR__ . "/definitions/$name.json"));
        return new Exporter($config);
    }
}