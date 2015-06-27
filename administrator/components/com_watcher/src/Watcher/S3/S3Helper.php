<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\S3;

use Watcher\Config\Config;

/**
 * The S3Helper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class S3Helper
{
	/**
	 * Property instance.
	 *
	 * @var  \S3
	 */
	protected static $instance;

	/**
	 * getInstance
	 *
	 * @return  \S3
	 */
	public static function getInstance()
	{
		if (!static::$instance)
		{
			static::$instance = new \S3(
				Config::get('amazon.key'),
				Config::get('amazon.secret')
			);
		}

		return static::$instance;
	}
}
