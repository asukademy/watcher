<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Command\Queue\Clean;

use JConsole\Command\JCommand;
use Watcher\IronMQ\IronMQHelper;

defined('JCONSOLE') or die;

/**
 * Class Clean
 *
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @since       3.2
 */
class Clean extends JCommand
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
	protected $name = 'clean';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Clean all messages';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'clean <cmd><command></cmd> <option>[option]</option>';

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
		$ironmq = IronMQHelper::getInstance();

		$ironmq->clearQueue('Backup');

		$this->out('Clear Backup queue');

		return true;
	}
}
