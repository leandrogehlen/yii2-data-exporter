<?php

namespace leandrogehlen\exporter\data;

use leandrogehlen\exporter\data\serializers\Serializer;
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
    use OwnedCollectionTrait;

    /**
     * @var string|Serializer
     */
    public $serializer;

    /**
     * @var string
     */
    public $charDelimiter;

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
        $this->serializer = Yii::createObject([
            'class' => $this->serializer,
            'exporter' => $this
        ]);

        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }

        $this->initCollection($this->sessions, Session::className());
        $this->initCollection($this->dictionaries, Dictionary::className());
        $this->initCollection($this->events, Event::className());
        $this->initCollection($this->providers, Provider::className());
        $this->initCollection($this->parameters, Parameter::className());
    }

    /**
     * Evaluates a PHP expression.
     *
     * The second parameter will be "extracted" into PHP variables
     * that can be directly accessed in the expression. See {@link http://us.php.net/manual/en/function.extract.php PHP extract}
     * for more details.
     *
     * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
     * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
     *
     * @param string $expression a PHP expression or PHP callback to be evaluated.
     * @param array $data additional parameters to be passed to the above expression.
     * @return mixed the expression result
     */
    public function evaluate($expression, $data)
    {
        extract($data);
        return eval($expression);
    }

    /**
     * Finds Provider instance by the given name.
     * @param string $name the provider name
     * @return Provider|null
     */
    public function findProvider($name)
    {
        foreach ($this->providers as $provider) {
            if ($provider->name == $name) {
                return $provider;
            }
        }
        return null;
    }

    /**
     * Finds Dictionary instance by the given name.
     * @param string $name the dictionary name
     * @return Dictionary|null
     */
    public function findDictionary($name)
    {
        foreach ($this->dictionaries as $dictionary) {
            if ($dictionary->name == $name) {
                return $dictionary;
            }
        }
        return null;
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
        $data = [];
        foreach ($this->sessions as $session) {
            $data[] = $this->serializer->serialize($session);
        }
        return implode($this->serializer->separator, $data);
    }
}