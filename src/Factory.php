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

namespace Kit\Translation;

use RuntimeException;
use Kit\Translation\Locale\Message;
use Kit\Translation\Locale\LocaleManager;
use Kit\DependencyInjection\Injector\InjectorBridge;
use Kit\Translation\Exceptions\BadConfigurationSourceException;

class Factory extends InjectorBridge
{

	/**
	* @var 		$locale
	* @access 	public
	*/
	public 		$locale;

	/**
	* @param 	$locale Kit\Translation\Locale\LocaleManager
	* @access 	public
	* @return 	void
	*/
	public function __construct(LocaleManager $locale)
	{
		$this->locale = $locale;
	}

	/**
	* @param 	$message <String>
	* @param 	$parameters <Array>
	* @access 	public
	* @return 	String
	*/
	public function getMessage($message='', array $parameters=[])
	{
		$messageLocale = new Message($message, $this->locale);
		return $messageLocale->getMessage($this, $parameters);
	}

	/**
	* @access 	public
	* @return 	String
	*/
	public function setLocale()
	{
		return $this->locale->setLocale();
	}

	/**
	* @access 	public
	* @return 	String
	*/
	public function getLocale()
	{
		return $this->locale->getLocale();
	}

	/**
	* @param 	$key <String>
	* @access 	public
	* @return 	Array
	*/
	public function getConfig($key='')
	{
		$config = include 'public/config/translation.php';

		if (gettype($config) !== 'array') {
		
			throw new BadConfigurationSourceException("Invalid configuration file");
		
		}

		return $config[$key] ?? $config;
	}

}