<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Watcher\Config;

use Joomla\Registry\Registry;
use Windwalker\System\Config\AbstractConfig;
use Windwalker\Helper\PathHelper;

// No direct access
defined('_JEXEC') or die;

/**
 * Watcher config.
 *
 * @since 1.0
 */
abstract class Config extends AbstractConfig
{
	/**
	 * Config file type.
	 *
	 * @var  string
	 */
	protected static $type = 'json';

	/**
	 * Get config file path.
	 *
	 * @return  string
	 */
	public static function getPath()
	{
		$type = static::$type;
		$ext  = (static::$type == 'yaml') ? 'yml' : $type;

		return PathHelper::getAdmin('com_watcher') . '/etc/config.' . $ext;
	}

	/**
	 * Get config from file. Will get from cache if has loaded.
	 *
	 * @return  Registry Config object.
	 */
	public static function getConfig()
	{
		if (static::$config instanceof Registry)
		{
			return static::$config;
		}

		$config = parent::getConfig();

		if (is_file(JPATH_ROOT . '/sms/etc/config.yml'))
		{
			$config->loadFile(JPATH_ROOT . '/sms/etc/config.yml', 'yaml');
		}

		return static::$config = $config;
	}
}
