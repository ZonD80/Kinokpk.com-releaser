<?php
/**
 * AtomGenerator
 *
 * Generator strategy, which creates XML for Atom
 *
 * @author	Mateusz 'MatheW' WГіjcik
 * @package FeedGenerator
 *
 */
class AtomGenerator implements generator {
	private $_dom, $_channel, $_atomNode;
	private $channelRequired= array (
 	'title',
 	'link',
 	'description'
 	), $itemRequired= array (
 	'title',
 	'link',
 	'description'
 	), $itemChanged= array (
 	'description' => 'summary',
 	'pubDate' => 'published'
 	), $channelChanged= array (
 	'description' => 'subtitle',
 	'copyright' => 'rights',
 	'lastBuildDate' => 'updated'
 	), $channelDeleted= array (
 	'language',
 	'webMaster',
 	'channelLink'
 	);

 	public function __construct() {
 		$this->_dom= new DOMDocument('1.0', 'utf-8');
 		$this->_dom->formatOutput= true;
 		$this->_atomNode= $this->_dom->appendChild($this->_dom->createElement('feed'));
 		$this->_atomNode->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
 	}
 	/**
 	 * Returns generator name
 	 *
 	 * @return string
 	 */
 	public function generatorName() {
 		return 'Atom FeedGenerator 1.1 by Mateusz \'MatheW\' WГіjcik';
 	}

 	/**
 	 * Generates XML code
 	 *
 	 * @param Channel $channel
 	 * @return string
 	 */
 	public function generate(Channel $channel) {
 		$this->_channel= $channel;

 		foreach ($this->_channel as $nodeName => $nodeValue) {
 			if (in_array($nodeName, $this->channelDeleted))
 			continue;
 			if ($nodeName == 'link') {
 				if (empty ($nodeValue))
 				continue;
 				$tmp= $this->_atomNode->appendChild($this->_dom->createElement($nodeName));
 				$tmp->setAttribute('href', $nodeValue);
 				continue;
 			}
 			if ($nodeName == 'author') {
 				$tmp= $this->_atomNode->appendChild($this->_dom->createElement('author'));
 				$tmp->appendChild($this->_dom->createElement('name', $nodeValue));
 				continue;
 			}

 			if (in_array($nodeName, array_keys($this->channelChanged)))
 			$nodeName= $this->channelChanged[$nodeName];
 			if (!empty ($nodeValue) or in_array($nodeName, $this->channelRequired))
 			$this->_atomNode->appendChild($this->_dom->createElement($nodeName, $nodeValue));
 		}

 		$tmp=$this->_atomNode->appendChild($this->_dom->createElement('link'));
 		$tmp->setAttribute('rel','self');
 		$tmp->setAttribute('href', empty($this->_channel->channelLink)?$this->_channel->id:$this->_channel->channelLink);
 		$tmp->setAttribute('type', 'application/atom+xml');

 		if (empty ($this->_channel->lastBuildDate)) {
 			$this->_atomNode->appendChild($this->_dom->createElement('updated', date3339(time())));
 		}

 		foreach ($this->_channel->getItems() as $item) {

 			$i= $this->_atomNode->appendChild($this->_dom->createElement('entry'));

 			foreach ($item as $nodeName => $nodeValue) {
 				if ($nodeName == 'link') {
 					if (empty ($nodeValue))
 					continue;
 					$tmp= $i->appendChild($this->_dom->createElement($nodeName));
 					$tmp->setAttribute('href', $nodeValue);
 					continue;
 				}
 				if ($nodeName=='description') {
 					$tmp= $i->appendChild($this->_dom->createElement('content', htmlspecialchars($nodeValue)));
 					$tmp->setAttribute('type', 'html');
 					continue;
 				}
 				if (in_array($nodeName, array_keys($this->itemChanged)))
 				$nodeName= $this->itemChanged[$nodeName];

 				if (!empty ($nodeValue) or in_array($nodeName, $this->itemRequired))
 				$i->appendChild($this->_dom->createElement($nodeName, $nodeValue));
 			}

 			if (empty ($item->updated)) {
 				$i->appendChild($this->_dom->createElement('updated', date3339(time())));
 			}
 		}

 		return $this->_dom->saveXML();

 	}
}
?>