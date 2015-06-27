<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Command\Queue\Backup;

use JConsole\Command\JCommand;
use Windwalker\Joomla\DataMapper\DataMapper;

defined('JCONSOLE') or die;

/**
 * Class Backup
 *
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @since       3.2
 */
class Backup extends JCommand
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
	protected $name = 'backup';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Backup a site';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'backup <cmd><command></cmd> <option>[option]</option>';

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
		$id = $this->getArgument(0);

		$site = (new DataMapper('#__watcher_sites'))->findOne($id);

		$backup = new \Watcher\Backup\Backup($site);

		$url = $backup->deleteOldBackups()->backup();

		$this->out('Backup to: ' . $url);

		return true;
	}
}
