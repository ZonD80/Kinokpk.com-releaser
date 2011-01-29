<?php
/**
 * RSSGenerator
 *
 * Generator strategy which creates XML for RSS
 *
 * @author Mateusz 'MatheW' WГіjcik
 * @package    FeedGenerator
 *
 */
class RSSGenerator implements generator{

	private $_dom, $_channel, $_rssNode;
	private $channelRequired=array('title', 'link', 'description'),
	$channelDeleted=array('id', 'channelLink'), $channelChanged= array ('author' => 'managingEditor'),
	$itemRequired=array('title', 'link', 'description'),
	$itemChanged=array('id'=>'guid'),
	$itemDeleted=array('updated');

	public function __construct(){
		$this->_dom=new DOMDocument('1.0', 'utf-8');
		$this->_dom->formatOutput=true;
		$this->_rssNode=$this->_dom->appendChild($this->_dom->createElement('rss'));
		$this->_rssNode->setAttribute('version', '2.0');
		$this->_rssNode->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
	}

	public function generatorName(){
		return 'RSS FeedGenerator 1.1 by Mateusz \'MatheW\' WГіjcik';
	}

	/**
	 * Generates XML code
	 *
	 * @param Channel $channel
	 * @return string
	 */
	public function generate(Channel $channel){
		$this->_channel=$channel;
		$channel=$this->_rssNode->appendChild($this->_dom->createElement('channel'));

		foreach($this->_channel as $nodeName=>$nodeValue){
			if (in_array($nodeName, $this->channelDeleted))
			continue;
			if (in_array($nodeName, array_keys($this->channelChanged)))
			$nodeName= $this->channelChanged[$nodeName];
			if(!empty($nodeValue) or in_array($nodeName, $this->channelRequired))
			$channel->appendChild($this->_dom->createElement($nodeName, $nodeValue));
		}


		foreach($this->_channel->getItems() as $item){
			$i=$channel->appendChild($this->_dom->createElement('item'));
			foreach($item as $nodeName=>$nodeValue) {
				if(in_array($nodeName, $this->itemDeleted)) continue;
				if(in_array($nodeName, array_keys($this->itemChanged))) $nodeName=$this->itemChanged[$nodeName];
				if($nodeName=='description'){
					$tmp=$i->appendChild($this->_dom->createElement($nodeName));
					$tmp->appendChild($this->_dom->createCDATASection($nodeValue));
					continue;
				}
				if(!empty($nodeValue) or in_array($nodeName, $this->itemRequired))
				$i->appendChild($this->_dom->createElement($nodeName, $nodeValue));
			}
		}

		return $this->_dom->saveXML();


	}
}
?>
