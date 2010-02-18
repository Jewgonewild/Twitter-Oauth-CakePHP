<?php
/**
 * Abstract base class for OAuth consumers. 
 * 
 * A typical class extending this base class looks like:
 * 
 * class FireEagleConsumer extends AbstractConsumer {
 *     public function __construct() {
 * 	       parent::__construct('key', 'secret');
 *     }
 * }
 * 
 * The following conventions apply for subclasses:
 * - class name has to end with "Consumer"
 * - each class has to be in its own file, the name ending with "_consumer.php"
 * - class name is camel-cased, file name uses underscores, e.g. FireEagleConsumer 
 *   and fire_eagle_consumer.php 
 * 
 * Copyright (c) by Daniel Hofstetter (http://cakebaker.42dh.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version			$Revision: 58 $
 * @modifiedby		$LastChangedBy: dho $
 * @lastmodified	$Date: 2008-09-01 07:33:49 +0200 (Mon, 01 Sep 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
abstract class AbstractConsumer {
	private $consumerKey = null;
	private $consumerSecret = null;
	
	public function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
	}
	
	final public function getConsumer() {
		return new OAuthConsumer($this->consumerKey, $this->consumerSecret);
	}
}
?>