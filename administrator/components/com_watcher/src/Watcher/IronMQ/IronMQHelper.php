<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\IronMQ;

use IronMQ\IronMQ;
use Watcher\Config\Config;

/**
 * The IronMQHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class IronMQHelper
{
	/**
	 * Property instance.
	 *
	 * @var  IronMQ
	 */
	protected static $instance;

	/**
	 * getInstance
	 *
	 * @return  IronMQ
	 */
	public static function getInstance()
	{
		if (!static::$instance)
		{
			$config = (array) Config::get('ironmq');

			static::$instance = new IronMQ($config);

			static::$instance->ssl_verifypeer = false;
		}

		return static::$instance;
	}
}
