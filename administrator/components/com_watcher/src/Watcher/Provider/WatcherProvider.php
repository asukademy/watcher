<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Watcher\Provider;

use Joomla\DI\Container;
use Windwalker\DI\ServiceProvider;

// No direct access
defined('_JEXEC') or die;

/**
 * Watcher provider.
 *
 * @since 1.0
 */
class WatcherProvider extends ServiceProvider
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 */
	public function register(Container $container)
	{
	}
}
