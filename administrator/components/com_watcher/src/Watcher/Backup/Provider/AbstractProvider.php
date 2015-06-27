<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup\Provider;

use Watcher\Helper\Downloader;
use Windwalker\Data\Data;

/**
 * The AbstractProvider class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class AbstractProvider
{
	/**
	 * Property site.
	 *
	 * @var  Data
	 */
	protected $site;

	/**
	 * Class init.
	 *
	 * @param mixed $site
	 */
	public function __construct($site = null)
	{
		$this->site = new Data($site);
	}

	/**
	 * create
	 *
	 * @param string $type
	 *
	 * @return static
	 */
	public static function create($type)
	{
		$class = __NAMESPACE__ . '\\' . ucfirst($type) . 'Provider';

		if (!class_exists($class))
		{
			$class = __NAMESPACE__ . '\\' . 'EzsetProvider';
		}

		return new $class;
	}

	/**
	 * downloadFile
	 *
	 * @param string $dest
	 *
	 * @return  string
	 */
	public function download($dest)
	{
		$src = $this->getUri();

		if (!is_dir(dirname($dest)))
		{
			\JFolder::create(dirname($dest));
		}

		Downloader::download($src->toString(), $dest);

		return $dest;
	}

	/**
	 * getUri
	 *
	 * @return \JUri
	 * @throws \Exception
	 */
	public function getUri()
	{
		if (!$this->site->url)
		{
			throw new \Exception('Site url is empty');
		}

		return new \JUri($this->site->url);
	}

	/**
	 * Method to get property Site
	 *
	 * @return  Data
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * Method to set property site
	 *
	 * @param   Data $site
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setSite($site)
	{
		$this->site = $site;

		return $this;
	}
}
