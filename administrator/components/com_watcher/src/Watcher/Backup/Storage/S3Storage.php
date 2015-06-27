<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup\Storage;

use Watcher\Config\Config;
use Watcher\S3\S3Helper;

/**
 * The S3Remote class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class S3Storage implements BackupStorageInterface
{
	/**
	 * Property bucket.
	 *
	 * @var   string
	 */
	protected $bucket;

	/**
	 * upload
	 *
	 * @param string $src
	 * @param string $dest
	 *
	 * @return  string
	 */
	public function upload($src, $dest)
	{
		$this->getHandler()->putObject(\S3::inputFile($src, false), $this->getBucket(), $dest, \S3::ACL_PUBLIC_READ);

		return $dest;
	}

	/**
	 * getHandler
	 *
	 * @return  \S3
	 */
	public function getHandler()
	{
		return S3Helper::getInstance();
	}

	/**
	 * getBucket
	 *
	 * @return  string
	 */
	public function getBucket()
	{
		if (!$this->bucket)
		{
			$this->bucket = Config::get('amazon.bucket', 'simular');
		}

		return $this->bucket;
	}

	/**
	 * getUrl
	 *
	 * @return  string
	 */
	public function getBaseUrl()
	{
		return 'https://' . $this->getBucket() . '.s3.amazonaws.com';
	}

	/**
	 * delete
	 *
	 * @param string $dest
	 *
	 * @return  boolean
	 */
	public function delete($dest)
	{
		$this->getHandler()->deleteObject($this->getBucket(), $dest);
	}
}
