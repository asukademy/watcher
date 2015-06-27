<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Seeder;

use SMS\Seeder\AbstractSeeder;
use Windwalker\Joomla\DataMapper\DataMapper;

/**
 * The BackupSeeder class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class BackupSeeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$faker = \Faker\Factory::create('zh_TW');

		$sites = (new DataMapper('#__watcher_sites'))->findAll();

		foreach ($sites as $site)
		{
			foreach (range(1, rand(5, 30)) as $i)
			{
				$backup = [];
				$backup['site_id'] = $site->id;
				$backup['title'] = $site->title . '-backup-' . $faker->dateTime->format('Y-m-d');
				$backup['alias'] = \JFilterOutput::stringURLUnicodeSlug($backup['title']);
				$backup['size'] = 1024 * rand(50, 150);
				$backup['url'] = $faker->url;
				$backup['created'] = $faker->dateTime->format('Y-m-d H:i:s');
				$backup['state'] = 1;

				$backup = (object) $backup;

				$this->db->insertObject('#__watcher_backups', $backup, 'id');
			}
		}

		$this->command->out(__CLASS__ . ' Executed.');
	}

	/**
	 * doClean
	 *
	 * @return  void
	 */
	public function doClean()
	{
		$this->db->truncateTable('#__watcher_backups');
	}
}
