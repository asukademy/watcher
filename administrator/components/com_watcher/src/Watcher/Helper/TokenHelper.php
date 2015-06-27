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
	 * @return  string
	 */
	public static function genAccessToken($secret)
	{
		return sha1(md5('SimularWatcher' . $secret));
	}

	/**
	 * genSecret
	 *
	 * @return  string
	 */
	public static function genSecret()
	{
		return \JUserHelper::genRandomPassword(24);
	}
}
