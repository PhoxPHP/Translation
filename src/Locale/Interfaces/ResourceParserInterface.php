<?php
namespace Kit\Translation\Locale\Interfaces;

use StdClass;
use Kit\Translation\Locale\ResourceParser;

interface ResourceParserInterface
{

	/**
	* Parses a property file of a given locale.
	*
	* @access 	public
	* @return 	Object Translation\Locale\ResourceParser
	*/
	public function parseResource() : ResourceParser;

	/**
	* @access 	public
	* @return 	Array
	*/
	public function getResource() : Array;

}