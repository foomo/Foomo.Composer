<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class ComposerTest extends \PHPUnit_Framework_TestCase
{
	public function testGetPackageForModule()
	{
		$package = Composer::getPackageForModule(Composer\Module::NAME);
		$this->assertEquals(Composer\Module::getDescription(), $package->description);
	}
	public function testApplyOverrides()
	{
		$value = array(
			'foo' => 'bar',
			'sepp' => 1,
			'deep' => array(
				'a' => 'a',
				'deeper' => array(
					'bla' => 'bla bla',
					'fooooo' => array(
						'foo' => 'mo'
					)
				)
			),
			'flatten' => array(1,2,3)
		);
		$overrides = array(
			'foo' => 'foo',
			'hansi' => 'hansi',
			'deep' => array(
				'a' => 'b',
				'x' => 100,
				'deeper' => array(
					'end' => 'endend',
					'fooooo' => 123
				)
			),
			'flatten' => 'flat'
		);
		Composer::applyOverrides($value, $overrides);
		$this->assertEquals(
			array(
				'foo' => 'foo',
				'sepp' => 1,
				'deep' => array(
					'a' => 'b',
					'deeper' => array(
						'bla' => 'bla bla',
						'fooooo' => 123,
						'end' => 'endend'
					),
					'x' => 100
				),
				'flatten' => 'flat',
				'hansi' => 'hansi'
			),
			$value
		);
	}
}