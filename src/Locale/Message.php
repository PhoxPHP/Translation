<?php
/**
* @author 	Peter Taiwo
* @version 	1.0.0
*
* MIT License
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace Kit\Translation\Locale;

use Translation\Exceptions\ResourceNotFoundException;
use Translation\Locale\Interfaces\MessageInterface;
use Translation\Locale\ResourceParser;
use Translation\Locale\Locale;
use Translation\Factory;
use RuntimeException;
use StdClass;

class Message implements MessageInterface {

	/**
	* @var 		$message
	* @access 	private
	*/
	private 	$message;

	/**
	* @var 		$locale
	* @access 	private
	*/
	private 	$locale;

	/**
	* @var 		$resourcePath
	* @access 	private
	*/
	private 	$resourcePath;

	/**
	* @var 		$resourceObject
	* @access 	private
	*/
	private 	$resourceObject;

	/**
	* Constructor
	*
	* @param 	$message <String>
	* @param 	$locale <Object> Translation\Locale
	* @access 	public
	* @return 	void
	*/
	public function __construct($message, Locale $locale) {
		$this->message = $message;
		$this->locale = $locale;
	}

	/**
	* @param 	$factory <Object> Translation\Factory
	* @param 	$parameters <Array>
	* @access 	private
	* @return 	void
	*/
	private function beforeMessageGet(Factory $factory, $parameters) {
		$locale = $this->locale->getLocale();
		$property = $locale.'.properties';

		$this->resourceObject = $this->resolveResourceFile($factory, $property, $parameters);
	}

	/**
	* @param 	$factory <Object> Translation\Factory
	* @param 	$resourceName <String>
	* @param 	$parameters <Array>
	* @access 	private
	* @return 	Boolean
	*/
	private function resolveResourceFile(Factory $factory, $resourceName, $parameters=[]) : StdClass {
		$propertyPath = $factory->getConfig('resources_path');
		if (!file_exists($propertyPath.$resourceName)) {
			throw new ResourceNotFoundException(sprintf("Resource file for %s not found", $resourceName));
		}

		$resource = new StdClass;
		$resource->region = str_replace('.properties', '', $resourceName);
		$resource->path = $propertyPath.$resourceName;
		$resource->data = htmlentities(file_get_contents($resource->path));
		$resource->tags = $parameters;

		return $resource;
	}

	/**
	* {@inheritDoc}
	*
	* @param 	$factory <Object> Translation\Factory
	* @param 	$parameters <Array>
	* @access 	public
	* @return 	String
	*/
	public function getMessage(Factory $factory, array $parameters=[]) {
		$this->beforeMessageGet($factory, $parameters);
		$parser = new ResourceParser($this->resourceObject);
		$resource = $parser->parseResource()->getResource();

		// If a message key does not exist, we either throw an error only if this is set in
		// the configuration file or just return the message key itself.

		if (!isset($resource[$this->message])) {
			if ($factory->getConfig('throw_missing_errors') == true) {
				throw new RuntimeException(sprintf("Unable to get message %s", $this->message));
			}
			return $this->message;
		}

		$message = (Object) $resource[$this->message];
		$messageTags = array();
		if (count($message->tags) > 0) {
			foreach($message->tags as $tag) {
				if (!array_key_exists($tag, $parameters)) {
					if ($factory->getConfig('throw_missing_errors') == true) {
						throw new RuntimeException(sprintf("Unable to get parameter %s", $tag));
					}
					break;
				}

				$messageTags['['.$tag.']'] = $parameters[$tag];
			}
		}

		return str_replace(array_keys($messageTags), array_values($messageTags), $message->data);
	}

}