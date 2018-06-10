<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\Translation\Locale\ResourceParser
* @license 		MIT License
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace Kit\Translation\Locale;

use StdClass;
use Kit\Translation\Locale\Interfaces\ResourceParserInterface;
use Kit\Translation\Exceptions\BadConfigurationSourceException;

class ResourceParser implements ResourceParserInterface
{

	/**
	* @var 		$resource
	* @access 	private
	*/
	private 	$resource;

	/**
	* @var 		$parsedResource
	* @access 	private
	*/
	private 	$parsedResource;

	/**
	* @param 	$resource <Object>
	* @access 	public
	* @return 	void
	*/
	public function __construct(StdClass $resource)
	{
		$this->resource = $resource;
		$this->parsedResource = new StdClass;
	}

	/**
	* {@inheritDoc}
	*/
	public function parseResource() : ResourceParser
	{
		$data = $this->resource->data;
		$dataLayer = explode("\n", $data);
		$parsedResource = array();

		foreach($dataLayer as $key) {

			$key = explode(':', $key);
			
			if (sizeof($key) < 2) {
			
				throw new BadConfigurationSourceException(sprintf('Error getting message from %s. Invalid message formatting.', $this->resource->path));

			}

			$parsedResource[$key[0]] = array('data' => $this->stripIndexSpace($key[1]), 'tags' => $this->getParameters($key[1]));
		}

		$this->parsedResource = $parsedResource;
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function getResource() : Array
	{
		return $this->parsedResource;
	}

	/**
	* Checks if string starts with space and removes it if it does.
	*
	* @param 	$string <String>
	* @access 	private
	* @return 	<String>
	*/
	private function stripIndexSpace(String $string='')
	{
		$string = explode(' ', $string);
		$stringArray = array();

		foreach($string as $str) {
			if ($str !== '') {
				$stringArray[] = $str;
			}
		}

		return implode(' ', $stringArray);
	}

	/**
	* @param 	$string <String>
	* @access 	private
	* @return 	<Mixed>
	*/
	private function getParameters(String $string='')
	{
		if(preg_match_all("/\[(.*?)\]/", $string, $match)) {
			return $match[1];
		}
	}

}