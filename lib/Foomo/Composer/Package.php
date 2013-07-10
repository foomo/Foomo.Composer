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

namespace Foomo\Composer;
use Foomo\Modules\MakeResult;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Package
{
	/**
	 * @var string
	 */
	public $name;
	public $type = 'foomo-module';
	// public $stabilty = '';
	public $require = array();
	public $license = 'LGPL-3.0+';
	public $description = 'none given';
	public $extra = array('moduleName' => '');

	public $targetDir = '';
	public function getValue()
	{
		$ret = (array) $this;
		$ret['target-dir'] = $ret['targetDir'];
		unset($ret['targetDir']);
		$ret['require'] = (object) $ret['require'];
		return $ret;
	}

}