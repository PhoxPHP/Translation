<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\Translation\Locale\Interfaces\ResourceParserInterface
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

namespace Kit\Translation\Locale\Interfaces;

use StdClass;
use Kit\Translation\Locale\ResourceParser;

interface ResourceParserInterface
{

	/**
	* Parses a property file of a given locale.
	*
	* @access 	public
	* @return 	<Object> <Translation\Locale\ResourceParser>
	*/
	public function parseResource() : ResourceParser;

	/**
	* @access 	public
	* @return 	<Array>
	*/
	public function getResource() : Array;

}