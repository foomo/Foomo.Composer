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
class Composer
{
	public static function generatePackages()
	{
		$ret = array();
		foreach(\Foomo\Modules\Manager::getEnabledModules() as $enabledModule) {
			$filename = \Foomo\Config::getModuleDir($enabledModule) . DIRECTORY_SEPARATOR . 'composer.json';
			$package = self::getPackageForModule($enabledModule)->getValue();
			$overrideFilename = \Foomo\Config::getModuleDir($enabledModule) . DIRECTORY_SEPARATOR . 'composer-override.json';
			if(file_exists($overrideFilename)) {
				$overrides = json_decode(file_get_contents($overrideFilename));
				self::applyOverrides($package, $overrides);
			}
			if(empty($package['require'])) {
				$package['require'] = (object) array();
			}
			file_put_contents($filename, json_encode($package, JSON_PRETTY_PRINT));
			$ret[$filename] = $package;
		}
		return $ret;
	}
	public static function getPackageForModule($module)
	{
		$package = new Composer\Package();
		$package->name = self::getModulePackageName($module);
		$moduleClass = \Foomo\Modules\Manager::getModuleClassByName($module);
		$package->description = call_user_func_array(array($moduleClass, 'getDescription'), array());
		$package->targetDir = $module;
		$package->extra['moduleName'] = $module;
		$resources = \Foomo\Modules\Manager::getRequiredModuleResources($module);
		foreach($resources as $resource) {
			switch(true) {
				case $resource instanceof \Foomo\Modules\Resource\Module:
					/* @var $resource \Foomo\Modules\Resource\Module */
					$depName = self::getModulePackageName($resource->name);
					if(!empty($depName)) {
						$package->require[$depName] = $resource->version;
					}
					break;
				// add others like php modules
			}
		}
		return $package;
	}
	public static function applyOverrides(&$package, $overrides)
	{
		foreach($overrides as $key => $value) {
			if(
				!isset($package[$key]) ||
				is_scalar($value) ||
				(isset($package[$key]) && is_scalar($package[$key]))
			) {
				$package[$key] = $value;
			} else if(
				isset($package[$key]) &&
				!is_scalar($package[$key]) &&
				!is_scalar($value)
			) {
				self::applyOverrides($package[$key], $value);
			}
		}
	}
	public static function getModulePackageName($module)
	{
		if($module == \Foomo\Module::NAME) {
			return 'foomo/foomo';
		} else {
			$name = '';
			$nameParts = explode('.', $module);
			if(count($nameParts) > 1) {
				array_walk(
					$nameParts,
					function(&$value, $index) {
						$value = strtolower($value);
					}
				);
				$name = array_shift($nameParts) . '/';
				$name .= implode('-', $nameParts);
			}
			return $name;
		}
	}
}