<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup\Storage;

/**
 * The BackupRemoteInterface class.
 * 
 * @since  {DEPLOY_VERSION}
 */
interface BackupStorageInterface
{
	/**
	 * upload
	 *
	 * @param string $src
	 * @param string $dest
	 *
	 * @return  boolean
	 */
	public function upload($src, $dest);

	/**
	 * delete
	 *
	 * @param string $dest
	 *
	 * @return  boolean
	 */
	public function delete($dest);

	/**
	 * getUrl
	 *
	 * @return  string
	 */
	public function getBaseUrl();
}
