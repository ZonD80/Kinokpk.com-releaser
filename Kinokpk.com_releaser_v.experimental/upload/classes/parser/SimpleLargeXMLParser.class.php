<?php

/**
 * Project:		Large XML Parser
 * File:		LargeXMLParser.php
 * Purpose		Parse large XML files and get the result as an array
 *
 * For questions, help, comments, discussion, etc., please send
 * e-mail to dragos@protung.ro
 *
 * @link http://www.protung.ro/
 * @copyright 2009 Dragos Protung
 * @version 1.5
 */
final class SimpleLargeXMLParser {

	/**
	 * DOMDocument
	 *
	 * @var DOMDocument
	 */
	protected $XMLDoc;

	/**
	 * DOMXPath
	 *
	 * @var DOMXPath
	 */
	protected $XPath;

	/**
	 * Namespaces to register for XPath
	 *
	 * @var array
	 */
	protected $namespaces = array();


	public function __construct() {

		$this->XMLDoc = new DOMDocument();
		$this->XMLDoc->xmlStandalone = true;
		$this->XMLDoc->preserveWhiteSpace = false;
	}

	public function registerNamespace ($prefix, $namspaceURI) {

		$this->XPath->registerNamespace($prefix, $namspaceURI);
	}

	public function loadXML ($source) {

		$load = $this->XMLDoc->Load($source);
		$this->XPath = new DOMXPath($this->XMLDoc);

		return $load;
	}

	/**
	 * Parse an XML
	 *
	 * @param string $source
	 * @param string $query
	 * @param bool $getAttributes
	 * @return array
	 */
	public function parseXML ($query = false, $getAttributes = false) {

		$return = array();

		if ($query == false || $query == "//") { // no query defined - get the root
			$query = "*";
		}
		$components = $this->XPath->query($query);
		if ($components instanceof DOMNodeList) {
			foreach ($components as $component) {
				if ($component instanceof DOMElement) {
					$return[] = self::getChildern($component, $getAttributes);
				}
			}
		}

		return $return;
	}

	/**
	 * Get the childrens of a DOM node as array or as a string if the node does not have childrens
	 *
	 * @param DOMElement $node
	 * @param bool $getAttributes
	 * @return array / string
	 */
	protected static function getChildern (DOMElement $node, $getAttributes) {

		$getAttributes = (bool)$getAttributes;

		if ($node->hasChildNodes()) {
			$return = array();
			foreach ($node->childNodes as $n) {
				if ($n instanceof DOMText) {
						
					if ($getAttributes === true) {
						$value = $node->nodeValue;
						$attributes = array();
						foreach ($node->attributes as $attrNode) {
							$attributes[$attrNode->name] = $attrNode->value;
						}
						$return[$node->nodeName] = array('value'=>$value, 'attributes'=>$attributes);
					} else {
						$return = $node->nodeValue;
					}
						
				} elseif ($n instanceof DOMElement) {
					if ($getAttributes === true) {
						$value = self::getChildern($n, $getAttributes);
						$attributes = array();
						foreach ($n->attributes as $attrNode) {
							$attributes[$attrNode->name] = $attrNode->value;
						}
						$return[$n->nodeName][] = array('value'=>$value, 'attributes'=>$attributes);
					} else {
						$return[$n->nodeName][] = self::getChildern($n, false);
					}
				}
			}
			return $return;
		} else {
			return $node->nodeValue;
		}

	}

}

?>