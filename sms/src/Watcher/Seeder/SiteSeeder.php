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
 * The SiteSeeder class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class SiteSeeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$faker = \Faker\Factory::create('zh_TW');

		$faker->addProvider(new \Faker\Provider\zh_TW\Address($faker));
		$faker->addProvider(new \Faker\Provider\zh_TW\Company($faker));
		$faker->addProvider(new \Faker\Provider\zh_TW\Person($faker));
		$faker->addProvider(new \Faker\Provider\zh_TW\PhoneNumber($faker));
		$faker->addProvider(new \Faker\Provider\zh_TW\Text($faker));

		$user = (new DataMapper('#__users'))->findOne();

		foreach (range(1, 20) as $i)
		{
			$site = [];
			$site['catid'] = 1;
			$site['title'] = $faker->company;
			$site['alias'] = \JFilterOutput::stringURLUnicodeSlug($site['title']);
			$site['site'] = $faker->url;
			$site['url'] = $faker->url;
			$site['type'] = 'ezset';
			$site['access_token'] = md5(uniqid(rand(1, 5000)));
			$site['created'] = $faker->dateTime->format('Y-m-d H:i:s');
			$site['created_by'] = $user->id;
			$site['state'] = 1;

			$site = (object) $site;

			$this->db->insertObject('#__watcher_sites', $site, 'id');
		}

		$this->command->out(__CLASS__ . ' executed.');
	}

	/**
	 * doClean
	 *
	 * @return  void
	 */
	public function doClean()
	{
		$this->db->truncateTable('#__watcher_sites');
	}
}
