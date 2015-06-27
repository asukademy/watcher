<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Command\Queue\Worker;

use JConsole\Command\JCommand;
use Joomla\Http\HttpFactory;
use Windwalker\Helper\CurlHelper;
use Windwalker\Joomla\DataMapper\DataMapper;

defined('JCONSOLE') or die;

/**
 * Class Worker
 *
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @since       3.2
 */
class Worker extends JCommand
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'worker';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Create worker';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'worker <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Configure command information.
	 *
	 * @return void
	 */
	public function configure()
	{
		// $this->addCommand();

		parent::configure();
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		include_once JPATH_ADMINISTRATOR . '/components/com_watcher/src/init.php';

		$id = $this->getArgument(0);

		$site = (new DataMapper('#__watcher_sites'))->findOne($id);

		$this->deleteOldBackups($site->id);

		if ($site->type == 'ezset')
		{
			$backupTmpFile = new \SplFileInfo(JPATH_ROOT . '/tmp/backups/' . $site->id . '/' . (new \JDate)->format('Y-m-d-H-i-s') . '.zip');

			if (!is_dir($backupTmpFile->getPath()))
			{
				\JFolder::create($backupTmpFile->getPath());
			}

			$uri = new \JUri($site->url);
			$uri->setUser($site->username);
			$uri->setPass($site->password);
			$uri->setVar('cmd', 'backup');
			$uri->setVar('quite', 1);

			$fp = fopen($backupTmpFile->getPathname(), 'w+');

			$options = array(
				'transport.curl' => [
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FILE => $fp,
					CURLOPT_SSL_VERIFYPEER => false
				]
			);

			$http = HttpFactory::getHttp($options, 'curl');

			try
			{
				$http->get($uri->toString());
			}
			catch (\RuntimeException $e)
			{
			}

			fclose($fp);
		}

		return true;
	}

	/**
	 * deleteOldBackups
	 *
	 * @param int $id
	 *
	 * @return  void
	 */
	protected function deleteOldBackups($id)
	{
		$now = new \JDate;

		$last = $now->modify('-5 days');

		$db = \JFactory::getDbo();

		$backups = (new DataMapper('#__watcher_backups'))
			->find(['created < ' . $db->q($last), 'site_id' => $id]);

		$ids = $backups->id;

		(new DataMapper('#__watcher_backups'))->delete(['id' => $ids]);
	}
}
