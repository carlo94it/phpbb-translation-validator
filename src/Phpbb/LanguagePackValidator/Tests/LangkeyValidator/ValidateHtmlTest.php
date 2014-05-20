<?php
/**
 *
 * @package LanguagePackValidator
 * @copyright (c) 2014 phpBB Ltd.
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace Phpbb\LanguagePackValidator\Tests\LangKeyValidator;

use Phpbb\LanguagePackValidator\Output\Output;

class ValidateHtmlTest extends TestBase
{
	public function validateHtmlData()
	{
		return array(
			array('None', 'foobar', 'foobar', array()),
			array('Same', '<em>foobar</em>', '<em>foobar</em>', array()),
			array('Different html', '<em>foobar</em>', '<strong>foobar</strong>', array(
				Output::NOTICE . '-String is using additional html: &lt;strong&gt;--Different html',
				Output::NOTICE . '-String is using additional html: &lt;/strong&gt;--Different html',
			)),
			array('Additional html', '<em>foobar</em>', '<em>foobar</em> <strong>foobar</strong> <invalid>foobar</invalid>', array(
				Output::NOTICE . '-String is using additional html: &lt;strong&gt;--Additional html',
				Output::NOTICE . '-String is using additional html: &lt;/strong&gt;--Additional html',
				Output::FATAL . '-String is using additional html: &lt;invalid&gt;--Additional html',
				Output::FATAL . '-String is using additional html: &lt;/invalid&gt;--Additional html',
			)),
			array('Additional unclosed html', '<em>foobar</em>', '<em>foobar</em> <strong>foobar', array(
				Output::NOTICE . '-String is using additional html: &lt;strong&gt;--Additional unclosed html',
				Output::FATAL . '-String is missing closing tag for html: strong--Additional unclosed html',
			)),
			array('Invalid html', '<em>foobar</em>', '<em>foobar</em><em>foobar</e m>', array(
				Output::FATAL . '-String is using invalid html: &lt;/e m&gt;--Invalid html',
				Output::FATAL . '-String is missing closing tag for html: em--Invalid html',
			)),
			array('Unclosed html', '<em>foobar</em>', '<em>foo<em>foobar</em>bar', array(
				Output::FATAL . '-String is missing closing tag for html: em--Unclosed html',
			)),
		);
	}

	/**
	* @dataProvider validateHtmlData
	*/
	public function testValidateHtml($key, $against_language, $validate_language, $expected)
	{
		$this->validator->validateHtml('', $key, $against_language, $validate_language);
		$this->assertOutputMessages($expected);
	}
}
