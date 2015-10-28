<?php

namespace leandrogehlen\exporter\data;

/**
 * Represents the Session layout element
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class Session extends Element
{
    use OwnedCollectionTrait;

    /**
     * @var Session the owner session.
     */
    public $owner;

    /**
     * @var string the provider name.
     */
    public $providerName;

    /**
     * @var boolean whether this session generate new content. Defaults to false.
     */
    public $new = false;

    /**
     * @var boolean whether this session name is visible. Defaults to true.
     */
    public $visible = true;

    /**
     * @var boolean whether this session will be executed. Defaults to true.
     */
    public $exported = true;

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
     *     ['name' => 'firstName', 'dictionaryName' => 'text']
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
        $this->initCollection($this->columns, Column::className());
        $this->initCollection($this->sessions, Session::className(), ['owner' => $this]);
    }
}
