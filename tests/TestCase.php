<?php

namespace leandrogehlen\exporter\tests;

use Yii;
use yii\base\NotSupportedException;
use yii\base\UnknownPropertyException;
use yii\console\Application;
use yii\di\Container;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

/**
 * This is the base class for all unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    use FixtureTrait;

    /**
     * This method is called before the first test of this test class is run.
     * Attempts to load vendor autoloader.
     * @throws \yii\base\NotSupportedException
     */
    public static function setUpBeforeClass()
    {
        $vendorDir = __DIR__ . '/../vendor';
        $vendorAutoload = $vendorDir . '/autoload.php';
        if (file_exists($vendorAutoload)) {
            require_once($vendorAutoload);
        } else {
            throw new NotSupportedException("Vendor autoload file '{$vendorAutoload}' is missing.");
        }
        require_once($vendorDir . '/yiisoft/yii2/Yii.php');
        Yii::setAlias('@vendor', $vendorDir);
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }

    /**
     * Returns the value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $object->property;`.
     * @param string $name the property name
     * @return mixed the property value
     * @throws UnknownPropertyException if the property is not defined
     */
    public function __get($name)
    {
        $fixture = $this->getFixture($name);
        if ($fixture !== null) {
            return $fixture;
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Load application configuration
     * @return array
     */
    protected function loadConfig()
    {
        $config = $config = require(__DIR__ . '/_config.php');
        $config['basePath'] = __DIR__;
        $config['vendorPath'] = dirname(dirname(__DIR__)) . '/vendor';
        return $config;
    }


    /**
     * Populates Yii::$app with a new console application
     */
    protected function mockApplication()
    {
        new Application($this->loadConfig());
        Yii::$container = new Container();
    }


    /**
     * Sets up before test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->unloadFixtures();
        $this->loadFixtures();
    }

    /**
     * Clean up after test.
     * The application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown()
    {
        Yii::$app = null;
        Yii::$container = new Container();
        parent::tearDown();
    }
}
