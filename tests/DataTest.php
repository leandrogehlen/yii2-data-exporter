<?php

namespace leandrogehlen\exporter\tests;

use leandrogehlen\exporter\data\Exporter;
use leandrogehlen\exporter\serializers\JsonSerializer;
use leandrogehlen\exporter\serializers\XmlSerializer;
use leandrogehlen\exporter\tests\fixtures\InvoiceFixture;
use leandrogehlen\exporter\tests\fixtures\InvoiceDetailsFixture;
use leandrogehlen\exporter\tests\fixtures\PersonFixture;
use leandrogehlen\exporter\tests\fixtures\ProductFixture;
use yii\base\Object;
use yii\helpers\Json;
use Yii;

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
        $exporter = $this->createExporter('column-size');
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
        $exporter = $this->createExporter('column-delimiter');
        $content = $exporter->execute();

        $lines = explode("\n", $content);
        $this->assertCount(5, $lines);

        $first = explode("|", $lines[0]);
        $first = array_slice($first, 1, 5);
        $this->assertCount(5, $first); // the event added additional `|`

        $this->assertEquals("010", $first[0]);
        $this->assertEquals("001", $first[1]);
        $this->assertEquals(date('Y-m-d'), $first[2]);
        $this->assertEquals("Administrator", $first[3]);
        $this->assertEquals("The first order - 1530.00", $first[4]);

        $second = explode("|", $lines[1]);
        $second = array_slice($second, 1, 5);  // the event added additional `|`

        $this->assertCount(5, $second);
        $this->assertEquals("020", $second[0]);
        $this->assertEquals(1, $second[1]);
        $this->assertEquals(2, $second[2]);
        $this->assertEquals(10.00, $second[3]);
        $this->assertEquals(20.00, $second[4]);

        $exporter->parameters[0]->value = date('Y-m-d', strtotime("+1 day"));
        $content = $exporter->execute();

        $this->assertEmpty($content);
    }


    public function testJson()
    {
        $exporter = $this->createExporter('hierarchical-data', ['serializer' => JsonSerializer::className()]);
        $content = $exporter->execute();

        $json = Json::decode($content);
        $this->assertCount(2, $json);
        $this->assertArrayHasKey('invoices', $json);
        $this->assertArrayHasKey('persons', $json);

        $root = $json['invoices'];
        $this->assertCount(2, $root);

        $first = $root[0];
        $this->assertEquals('100', $first["type"]);
        $this->assertEquals('001', $first["number"]);
        $this->assertEquals(date('Y-m-d'), $first["created_at"]);
        $this->assertEquals('The first order', $first["description"]);
        $this->assertEquals('event', $first["eventColumn"]); // added from event

        $person = $first['person'];
        $this->assertEquals('Administrator', $person["firstName"]);
        $this->assertEquals('Root', $person["lastName"]);

        $details = $first["details"];
        $this->assertCount(2, $details);

        $first = $details[0];
        $this->assertEquals('020', $first["type"]);
        $this->assertEquals('1', $first["product_id"]);
        $this->assertEquals('2', $first["quantity"]);
        $this->assertEquals('10.00', $first["price"]);
        $this->assertEquals('20.00', $first["total"]);

        $second = $details[1];
        $this->assertEquals('020', $second["type"]);
        $this->assertEquals('2', $second["product_id"]);
        $this->assertEquals('5', $second["quantity"]);
        $this->assertEquals('20.00', $second["price"]);
        $this->assertEquals('100.00', $second["total"]);
    }

    public function testXml()
    {
        $exporter = $this->createExporter('hierarchical-data', ['serializer' => XmlSerializer::className()]);
        $content = $exporter->execute();

        $xml = simplexml_load_string($content);
        $this->assertEquals('items', $xml->getName());
        $this->assertNotNull($xml->invoices);
        $this->assertNotNull($xml->persons);

        $first = $xml->invoices->item[0];
        $this->assertEquals('100', (string) $first->type);
        $this->assertEquals('001', (string) $first->number);
        $this->assertEquals(date('Y-m-d'), (string) $first->created_at);
        $this->assertEquals('The first order', (string) $first->description);

        $person = $first->person;
        $this->assertEquals('Administrator', (string) $person->firstName);
        $this->assertEquals('Root', (string) $person->lastName);

        $details = $first->details;
        $this->assertNotNull($details);

        $first = $details->item[0];
        $this->assertEquals('020', (string) $first->type);
        $this->assertEquals('1', (string) $first->product_id);
        $this->assertEquals('2', (string) $first->quantity);
        $this->assertEquals('10.00', (string) $first->price);
        $this->assertEquals('20.00', (string) $first->total);

        $second = $details->item[1];
        $this->assertEquals('020', (string) $second->type);
        $this->assertEquals('2', (string) $second->product_id);
        $this->assertEquals('5', (string) $second->quantity);
        $this->assertEquals('20.00', (string) $second->price);
        $this->assertEquals('100.00', (string) $second->total);

        // root xml element test
        unset($exporter->sessions[1]);
        $content = $exporter->execute();

        $xml = simplexml_load_string($content);
        $this->assertEquals('invoices', $xml->getName());
    }

    public function testInvalidProviderConfig()
    {
        $this->setExpectedExceptionRegExp('yii\base\InvalidConfigException', '/provider(.*)not found/');
        $exporter = $this->createExporter('invalid-config');
        $exporter->execute();
    }

    public function testInvalidDictionaryConfig()
    {
        $this->setExpectedExceptionRegExp('yii\base\InvalidConfigException', '/dictionary(.*)not found/');
        $exporter = $this->createExporter('invalid-config');
        $exporter->sessions[0]->provider = 'person-provider';
        $exporter->execute();
    }

    public function testInvalidFormatterConfig()
    {
        $this->setExpectedException('yii\base\InvalidConfigException');
        $this->createExporter('invalid-config', ['formatter' => new Object()]);
    }

    public function testInvalidSerializerConfig()
    {
        $this->setExpectedException('yii\base\InvalidConfigException');
        $this->createExporter('invalid-config', ['serializer' => new Object()]);
    }

    public function testInvalidEventConfig()
    {
        $this->setExpectedExceptionRegExp('yii\base\InvalidConfigException', '/The expression of event "(.*)" must be callable/');
        $exporter = $this->createExporter('invalid-config');

        $session = $exporter->sessions[0];
        $session->provider = 'person-provider';
        unset($session->columns[2]);

        $exporter->execute();
    }

    public function testInvalidQueryConfig()
    {
        $this->setExpectedExceptionRegExp('yii\base\InvalidConfigException', '/The query of provider "(.*)" must be string or Query object/');
        $exporter = $this->createExporter('invalid-config');

        $provider = $exporter->providers[0];
        $provider->name = 'invalid-provider';
        $provider->query = new Object();

        $exporter->execute();
    }

    /**
     * @param string $name
     * @param array $extra
     * @return Exporter
     */
    protected function createExporter($name, $extra = [])
    {
        $config = include __DIR__ . "/definitions/$name.php";
        $config = array_merge($config, $extra);
        return new Exporter($config);
    }
}
