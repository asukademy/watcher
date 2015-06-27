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
use Watcher\IronMQ\IronMQHelper;
use Watcher\Worker\WorkerApplication;
use Windwalker\Data\Data;

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
		$app = new WorkerApplication;

		$app->execute();

		return true;
	}
}
