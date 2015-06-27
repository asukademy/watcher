<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Command\Queue\Dequeue;

use IronMQ\IronMQ;
use JConsole\Command\JCommand;
use Watcher\Backup\Backup;
use Watcher\IronMQ\IronMQHelper;
use Windwalker\Data\Data;

defined('JCONSOLE') or die;

/**
 * Class Dequeue
 *
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @since       3.2
 */
class Dequeue extends JCommand
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
	protected $name = 'dequeue';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Dequeue a message';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'dequeue <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Property ironmq.
	 *
	 * @var  IronMQ
	 */
	protected $ironmq;

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
		$this->ironmq = IronMQHelper::getInstance();

		$message = $this->ironmq->getMessage('Backup');

		if (!$message)
		{
			throw new \Exception('No queue');
		}

		$message = new Data($message);

		if (!$message->body)
		{
			$this->ironmq->deleteMessage("Backup", $message->id);

			return false;
		}

		$site = new Data(json_decode($message->body));

		$backup = new Backup($site);

		$backup->deleteOldBackups()->backup();

		$this->ironmq->deleteMessage("Backup", $message->id);

		$this->out('Dequeue and backup site: ' . $site->site);

		return true;
	}
}
