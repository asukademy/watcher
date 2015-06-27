<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Helper;

/**
 * The TokenHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class TokenHelper
{
	/**
	 * genAccessToken
	 *
	 * @param string $id
	 *
	 * @return  string
	 */
	public static function genAccessToken($id)
	{
		return md5('AccessToken' . $id . sha1('Watcher-site-' . $id));
	}
}
