<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Seeder;

use SMS\Seeder\AbstractSeeder;

/**
 * The WatcherSeeder class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WatcherSeeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

		$this->execute(new SiteSeeder)
			->execute(new BackupSeeder);
	}

	/**
	 * doClean
	 *
	 * @return  void
	 */
	public function doClean()
	{
		$this->clean(new SiteSeeder)
			->clean(new BackupSeeder);
	}
}
