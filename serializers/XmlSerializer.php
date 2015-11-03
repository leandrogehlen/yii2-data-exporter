<?php

namespace leandrogehlen\exporter\serializers;

use DOMDocument;
use DOMElement;
use DOMText;

/**
 * Formats the given data into XML content.
 *
 * @author Leandro Guindani Gehlen <leandrogehlen@gmail.com>
 */
class XmlSerializer extends HierarchicalSerializer
{
    /**
     * @var string the XML version
     */
    public $version = '1.0';
    /**
     * @var string the XML encoding.
     */
    public $encoding = 'UTF-8';
    /**
     * @var string the name of the root element.
     */
    public $rootTag = 'items';
    /**
     * @var string the name of the elements that represent the array elements with numeric keys.
     */
    public $itemTag = 'item';

    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        $dom = new DOMDocument($this->version, $this->encoding);

        if (count($this->exporter->sessions) === 1) {
            $root = $dom;
        } else {
            $root = new DOMElement($this->rootTag);
            $dom->appendChild($root);
        }

        $this->buildXml($root, $data);
        return $dom->saveXML();
    }

    /**
     * @param DOMElement $element
     * @param mixed $data
     */
    protected function buildXml($element, $data)
    {
        foreach ($data as $name => $value) {
            if (is_int($name) && is_object($value)) {
                $this->buildXml($element, $value);
            } elseif (is_array($value)) {
                $child = new DOMElement(is_int($name) ? $this->itemTag : $name);
                $element->appendChild($child);
                $this->buildXml($child, $value);
            } else {
                $child = new DOMElement(is_int($name) ? $this->itemTag : $name);
                $element->appendChild($child);
                $child->appendChild(new DOMText((string)$value));
            }
        }
    }
}
