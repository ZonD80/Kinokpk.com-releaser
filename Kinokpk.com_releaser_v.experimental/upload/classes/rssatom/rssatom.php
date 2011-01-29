<?php

/**
 * Feed Generator
 *
 * Generates RSS/Atom feeds
 *
 * @package	FeedGenerator
 * @author	Mateusz 'MatheW' WГіjcik
 * @copyright 	2007 Mateusz 'MatheW' WГіjcik
 * @link	http://mwojcik.pl
 * @license	GPL
 * @version	v1.1
 * @link	http://mwojcik.pl/FeedGenerator/
 */


include('classes/rssatom/generator.class.php');
include('classes/rssatom/rssgenerator.class.php');
include('classes/rssatom/atomgenerator.class.php');
/**
 * Klasa FeedGenerator
 *
 * Creates feed
 *
 *
 * Through magic method __call all methods of Channel Object are available in FeedGenerator Object
 *
 * Example:
 * <pre>
 * try {
 *		include('FeedGenerator.php');
 *		$feeds=new FeedGenerator;
 *		$feeds->setGenerator(new RSSGenerator); # or AtomGenerator
 *		$feeds->setAuthor('mat.wojcik@gmail.com (MatheW Wojcik)');
 *		$feeds->setTitle('Example Site');
 *		$feeds->setChannelLink('http://example.com/rss/');
 *		$feeds->setLink('http://example.com');
 *		$feeds->setDescription('Description of channel');
 *		$feeds->setID('http://example.com/rss/');
 *
 *		$feeds->addItem(new FeedItem('http://example.com/1', 'Example news', 'http://example.com/1', '<p>Description of news</p>'));
 *		$feeds->addItem(new FeedItem('http://example.com/2', 'Example news', 'http://example.com/2', '<p>Description of news</p>'));
 *
 *		$feeds->display();
 *	}
 *	catch(FeedGeneratorException $e){
 *		echo 'Error: '.$e->getMessage();
 *	}
 * </pre>
 *
 * @package	FeedGenerator
 * @author	Mateusz 'MatheW' WГіjcik
 * @version	1.1
 *
 */
class FeedGenerator {
	/**
	 * Generator
	 *
	 * @var Generator
	 */
	private $_generator;
	/**
	 * Channel
	 *
	 * @var Channel
	 */
	private $_channel;
	/**
	 * Generated content
	 *
	 * @var string
	 */
	private $generated;


	public function __construct() {
		$this->_channel=new Channel();
	}
	/**
	 * @param Generator $generator Generator strategy (RSS/Atom)
	 */
	public function setGenerator(Generator $generator){
		$this->_generator=$generator;
		$this->_channel->setGeneratorName($this->_generator->generatorName());
	}
	/**
	 * Generates XML code
	 * @throws FeedGeneratorException
	 */
	public function generate(){
		if(!$this->_generator instanceof Generator) throw new FeedGeneratorException('There has been no generator strategy set.');
		$this->generated=$this->_generator->generate($this->_channel);
	}

	/**
	 * Shows content
	 *
	 * @throws FeedGeneratorException
	 */
	public function show(){
		if(empty($this->generated)) throw new FeedGeneratorException('Channel has not been generated yet.');
		echo $this->generated;
	}

	/**
	 * Generates and shows channel
	 *
	 * @param bool $headers If true it sends xml headers
	 * @throws FeedGeneratorException
	 */
	public function display($headers=true){
		$this->generate();
		if($headers) header('Content-type: application/xml; charset=utf-8');
		$this->show();
	}

	/**
	 * Returns generated XML code
	 * @return string XML code
	 */
	public function getGenerated(){
		return $this->generated;
	}
	public function __call($funcName, $params){
		if(method_exists($this->_channel, $funcName)) call_user_func_array(array($this->_channel, $funcName), $params);
	}


}

/**
 *
 * RSS/Atom Channel
 *
 */
class Channel{
	public
	$id, $title, $link, $description,
	$copyright, $author, $language,
	$webmaster, $lastBuildDate,  $generator, $pubDate, $channelLink;

	private $_items;

	/**
	 * Returns feed items
	 *
	 * @return array Array containing FeedItem objects
	 */
	public function getItems(){
		return $this->_items;
	}
	/**
	 * Sets id of channel
	 *
	 * @param string $id
	 */
	public function setID($id){
		$this->id=$id;
	}

	/**
	 * Adds new FeedItem
	 *
	 * Example:
	 * <pre>
	 * $channel->addItem(new FeedItem('http://example.com/news/1', 'Example news', 'http://example.com/news/1', '<p>Description of news</p>'));
	 * </pre>
	 *
	 * @param FeedItem $item FeedItem
	 */
	public function addItem(FeedItem $item){
		$this->_items[]=$item;
	}
	/**
	 * Sets channel's title
	 *
	 * @param string $title
	 */
	public function setTitle($title){
		$this->title=$title;
	}
	/**
	 * Sets link to site
	 *
	 * @param string $link
	 */
	public function setLink($link){
		$this->link=$link;
	}
	/**
	 * Sets description of channel
	 *
	 * @param string $description
	 */
	public function setDescription($description){
		$this->description=$description;
	}
	/**
	 * Sets copyright
	 *
	 * @param string $copyright
	 */
	public function setCopyright($copyright){
		$this->copyright=$copyright;
	}

	/**
	 * Sets link to channel
	 *
	 * @param string $channelLink
	 */
	public function setChannelLink($channelLink){
		$this->channelLink=$channelLink;
	}

	/**
	 * Sets author
	 *
	 * @param string $Author
	 */
	public function setAuthor($author){
		$this->author=$author;
	}
	/**
	 * Sets language
	 *
	 * @param string $Language
	 */
	public function setLanguage($language){
		$this->language=$language;
	}
	/**
	 * Sets Webmaster
	 *
	 * @param string $Webmaster
	 */
	public function setWebmaster($webmaster){
		$this->webmaster=$webmaster;
	}
	/**
	 * Sets date of last build of channel
	 *
	 * @param string $LastBulidDate
	 */
	public function setLastBulidDate($lastBulidDate){
		$this->lastBulidDate=$lastBulidDate;
	}
	/**
	 * Sets generator's name
	 *
	 * @param string $Generator
	 */
	public function setGeneratorName($generator){
		$this->generator=$generator;
	}
	/**
	 * Sets publication date
	 *
	 * @param string $PubDate
	 */
	public function setPubDate($PubDate){
		$this->pubDate=$PubDate;
	}
}

/**
 * Get date in RFC3339
 * For example used in XML/Atom
 *
 * @param integer $timestamp
 * @return string date in RFC3339
 * @author Boris Korobkov
 * @see http://tools.ietf.org/html/rfc3339
 */
function date3339($timestamp=0) {

	if (!$timestamp) {
		$timestamp = time();
	}
	$date = date('Y-m-d\TH:i:s', $timestamp);

	$matches = array();
	if (preg_match('/^([\-+])(\d{2})(\d{2})$/', date('O', $timestamp), $matches)) {
		$date .= $matches[1].$matches[2].':'.$matches[3];
	} else {
		$date .= 'Z';
	}
	return $date;

}

/**
 *
 * Represents each news, entry etc.
 *
 *
 * @package FeedGenerator
 * @author 	Mateusz 'MatheW' WГіjcik
 */

class FeedItem {
	/**
	 * Title of item
	 * @var string
	 */
	public $title;
	/**
	 * Link to item
	 *
	 * @var string
	 */
	public $link;
	/**
	 * Description of item
	 *
	 * @var string
	 */
	public $description;
	/**
	 * Author of item
	 *
	 * @var string
	 */
	public $author;
	/**
	 * Date of publication
	 *
	 * @var string
	 */
	public $pubDate;
	/**
	 * Id of item
	 *
	 * @var string
	 */
	public $id;
	/**
	 * Date of last update
	 *
	 * @var string
	 */
	public $updated;

	/**
	 *
	 * @param string $id ID
	 * @param string $title Title of item
	 * @param string $link Link to item
	 * @param string $description Description of item
	 * @param string $updated Date of last update
	 */
	public function __construct($id, $title, $link, $description, $updated=''){
		$this->id=$id;
		$this->title=$title;
		$this->link=$link;
		$this->description=$description;
		$this->updated=$updated;
	}
}

class FeedGeneratorException extends Exception {

}
?>