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

use RuntimeException;
use Kit\Translation\Locale\Interfaces\LocaleInterface;
use Kit\Translation\Locale\Interfaces\MessageInterface;

class LocaleManager implements LocaleInterface
{

	/**
	* English language code
	*/
	const 		ENGLISH = 'en';

	/**
	* French language code
	*/
	const 		FRANCE 	= 'fr';

	/**
	* Spanish language code
	*/
	const 		SPAIN 	= 'es';

	/**
	* Chinese language code
	*/
	const 		CHINESE = 'zh';

	/**
	* German language code
	*/
	const 		GERMAN 	= 'de';

	/**
	* @var 		$localeDefault
	* @access 	private
	*/
	private 	$localeDefault;

	/**
	* @var 		$languageCode
	* @access 	private
	*/
	private static $languageCode;

	/**
	* @var 		$country
	* @access 	private
	*/
	private static $country;

	/**
	* Constructor
	*
	* @param 	$languageCode <String>
	* @param 	$country <String>
	* @access 	public
	* @return 	void
	*/
	public function __construct($languageCode='', $country='')
	{
		if (!function_exists('setlocale')) {
		
			throw new RuntimeException("setlocale function does not exist");
		
		}

		LocaleManager::$languageCode = $languageCode;
		LocaleManager::$country = $country;
	}

	/**
	* @param 	$method <String>
	* @param 	$arguments <Array>
	* @access 	public
	* @return 	Mixed
	*/
	public function __call($method, $arguments)
	{
		$criteriaMatch = preg_match('/(get)(.*[a-zA-Z0-9])/', $method, $match);
		$localeconv = localeconv();
	
		if (!method_exists($this, $method) && $criteriaMatch) {
	
			$method = $match[2];
	
			if (!isset($localeconv[$method])) {
	
				return;
	
			}
	
			return $localeconv[$method];
		}
	}

	/**
	* {@inheritDoc}
	*
	* @access 	public
	* @return 	void
	*/
	public function setLocale()
	{
		setlocale(LC_ALL, $this->getLocale());
	}

	/**
	* {@inheritDoc}
	*
	* @access 	public
	* @return 	String
	*/
	public function getLocale()
	{
		$locale = LocaleManager::$languageCode;

		if (LocaleManager::$country !== '') {
		
			$locale = $locale.'_'.LocaleManager::$country;
		
		}

		return $locale;
	}

	/**
	* Sets the locale language code.
	*
	* @param 	$languageCode <String>
	* @access 	public
	* @return 	void
	*/
	public function setLanguageCode($languageCode='')
	{
		LocaleManager::$languageCode = $languageCode;
	}

	/**
	* Sets the locale country.
	*
	* @param 	$country <String>
	* @access 	public
	* @return 	void
	*/
	public function setCountry($country='')
	{
		LocaleManager::$country = $country;
	}

	/**
	* Returns the language code set by Translation\LocaleManager::setLanguageCode
	*
	* @access 	public
	* @return 	String
	*/
	public function getLanguageCode()
	{
		return LocaleManager::$languageCode;
	}

	/**
	* Returns the country set by Translation\LocaleManager::getCountry
	*
	* @access 	public
	* @return 	String
	*/
	public function getCountry()
	{
		return LocaleManager::$country;
	}
}