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
     * @var Exporter|Session
     */
    public $owner;

    /**
     * @var array column configuration. Each array element represents the configuration
     * for one particular column. For example,
     *
     * ```php
     * [
     *     ['name' => 'id', 'align' => Column::ALIGN_RIGHT, 'size' => 10, 'charComplete' => 0],
     *     ['name' => 'name', 'typeName' => 'text']
     *     ['name' => 'birthDate', 'expression' => 'return date('Y-m-d')']
     * ]
     * ```
     */
    public $columns = [];

    /**
     * @var string
     */
    public $providerName;

    /**
     * @var boolean
     */
    public $new;

    /**
     * @var array
     */
    public $sessions = [];

    /**
     * @var boolean whether this session is visible. Defaults to true.
     */
    public $visible = true;

    /**
     * @var boolean whether this session is visible. Defaults to true.
     */
    public $exported = true;

    /**
     * @var integer
     */
    public $rows;


    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate [[columns]] objects.
     */
    public function init()
    {
        parent::init();
        $this->initCollection($this->columns, Column::className());
        $this->initCollection($this->sessions, Session::className());
    }

}