<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup\Provider;

/**
 * The EzsetProvider class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class EzsetProvider extends AbstractProvider
{
	/**
	 * getUri
	 *
	 * @return  \JUri
	 */
	public function getUri()
	{
		$uri = parent::getUri();

		$uri->setUser($this->site->username);
		$uri->setPass($this->site->password);
		$uri->setVar('cmd', 'backup');
		$uri->setVar('quite', 1);

		return $uri;
	}
}
