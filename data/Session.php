<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the Session layout element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Session extends Element
{
    use CollectionTrait;

    /**
     * @var Session the owner session.
     */
    public $owner;

    /**
     * @var string the provider name.
     */
    public $provider;

    /**
     * @var boolean whether this session will be executed. Defaults to true.
     */
    public $visible = true;

    /**
     * @var integer the rows count.
     */
    public $rows;

    /**
     * @var Session[]
     */
    public $sessions = [];

    /**
     * @var array column configuration. Each array element represents the configuration
     * for one particular column. For example,
     *
     * ```php
     * [
     *     ['name' => 'id', 'align' => 'left', 'size' => 10, 'charComplete' => 0],
     *     ['name' => 'firstName', 'dictionary' => 'text']
     *     ['name' => 'birthDate', 'expression' => 'return date('Y-m-d');']
     * ]
     * ```
     */
    public $columns = [];


    /**
     * Initializes the session.
     * This method will instantiate [[columns]] and [[sessions]] objects.
     */
    public function init()
    {
        parent::init();
        $this->initElements($this->columns, Column::className());
        $this->initElements($this->sessions, Session::className(), ['owner' => $this]);
    }
}
