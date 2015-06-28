<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup\Provider;

use Watcher\Helper\TokenHelper;

/**
 * The EzsetProvider class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ScriptProvider extends AbstractProvider
{
	/**
	 * getUri
	 *
	 * @return  \JUri
	 */
	public function getUri()
	{
		$uri = parent::getUri();

		$uri->setVar('cmd', 'backup');
		$uri->setVar('quite', 1);
		$uri->setVar('access_token', TokenHelper::genAccessToken($this->site->secret));

		return $uri;
	}
}
