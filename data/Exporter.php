<?php

namespace leandrogehlen\exporter\data;

use leandrogehlen\exporter\serializers\Serializer;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\di\Instance;
use yii\i18n\Formatter;

/**
 * Exporter provides data performing DB queries.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Exporter extends Component
{
    use CollectionTrait;

    /**
     * @var string|array|Serializer
     */
    public $serializer;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Session[]
     */
    public $sessions = [];

    /**
     * @var Dictionary[]
     */
    public $dictionaries = [];

    /**
     * @var Event[]
     */
    public $events = [];

    /**
     * @var Provider[]
     */
    public $providers = [];

    /**
     * @var Parameter[]
     */
    public $parameters = [];

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection
     */
    public $db = 'db';

    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;

    /**
     * Initializes the exporter.
     * This method will initialize required property values and instantiate collection objects.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());

        $this->initSerializer();
        $this->initFormatter();


        $this->initElements($this->sessions, Session::className());
        $this->initElements($this->dictionaries, Dictionary::className());
        $this->initElements($this->events, Event::className());
        $this->initElements($this->providers, Provider::className());
        $this->initElements($this->parameters, Parameter::className());
    }


    /**
     * Finds Provider instance by the given name.
     * @param string $name the provider name
     * @return Provider
     * @throws InvalidConfigException if provider not found
     */
    public function findProvider($name)
    {
        foreach ($this->providers as $provider) {
            if ($provider->name == $name) {
                return $provider;
            }
        }
        throw new InvalidConfigException('The provider with name "' . $name .  '" not found');
    }

    /**
     * Finds Dictionary instance by the given name.
     * @param string $name the dictionary name
     * @return Dictionary
     * @throws InvalidConfigException if dictionary not found
     */
    public function findDictionary($name)
    {
        foreach ($this->dictionaries as $dictionary) {
            if ($dictionary->name == $name) {
                return $dictionary;
            }
        }
        throw new InvalidConfigException('The dictionary with name "' . $name .  '" not found');
    }

    /**
     * Finds Parameter instance by the given name.
     * @param string $name the dictionary name
     * @return Parameter|null
     */
    public function findParameter($name)
    {
        foreach ($this->parameters as $param) {
            if ($param->name == $name) {
                return $param;
            }
        }
        return null;
    }

    /**
     * Performs data conversion
     * @return string the formatted data
     */
    public function execute()
    {
        $data = $this->serializer->serialize($this->sessions);
        return $this->serializer->formatData($data);
    }

    /**
     * Initializes the Serializer property
     * @throws InvalidConfigException
     */
    protected function initSerializer()
    {
        if (is_string($this->serializer)) {
            $this->serializer = Yii::createObject([
                'class' => $this->serializer,
                'exporter' => $this
            ]);
        } elseif (is_array($this->serializer)) {
            $this->serializer['exporter'] = $this;
            $this->serializer = Yii::createObject($this->serializer);
        }

        if (!$this->serializer instanceof Serializer) {
            throw new InvalidConfigException('The "serializer" property must be either a Serializer object.');
        }
    }

    /**
     * Initializes the Formatter property
     * @throws InvalidConfigException
     */
    protected function initFormatter()
    {
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
    }
}
