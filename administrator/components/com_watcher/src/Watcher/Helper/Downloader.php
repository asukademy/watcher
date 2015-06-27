<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Helper;

use Joomla\Http\Http;
use Watcher\Http\DownloadTransport;

/**
 * The Downloader class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Downloader
{
	/**
	 * download
	 *
	 * @param string $src
	 * @param string $dest
	 *
	 * @return \Joomla\Http\Response
	 */
	public static function download($src, $dest)
	{
		$options = array(
			'transport.curl' => [
				CURLOPT_SSL_VERIFYPEER => false
			]
		);

		$http = new Http($options, $driver = new DownloadTransport);

		$driver->setTarget($dest);

		return $http->get((string) $src);
	}
}
