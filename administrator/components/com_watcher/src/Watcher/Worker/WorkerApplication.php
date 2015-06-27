<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Worker;

use IronMQ\IronMQ;
use Watcher\Backup\Backup;
use Watcher\IronMQ\IronMQHelper;
use Windwalker\Data\Data;

/**
 * The WorkerAplication class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WorkerApplication extends \JApplicationDaemon
{
	/**
	 * Property ironmq.
	 *
	 * @var  IronMQ
	 */
	protected $ironmq;

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		include_once JPATH_ADMINISTRATOR . '/components/com_watcher/src/init.php';

		$this->ironmq = IronMQHelper::getInstance();
	}

	/**
	 * doExecute
	 *
	 * @return  void
	 */
	protected function doExecute()
	{
		$child = $this->pcntlFork();

		$message = $this->ironmq->getMessage('Backup');

		$message = new Data($message);

		if (!$message->body)
		{
			$this->ironmq->deleteMessage("Backup", $message->id);

			return;
		}

		$site = json_decode($message->body);

		// Fatal error
		if ($child == -1)
		{
			\JLog::add('Unable to fork process, this is a fatal error, aborting worker', \JLog::INFO);

			$this->shutdown();
		}
		elseif ($child > 0)
		{
			\JLog::add('Forked process to run job on pid: ' . $child, \JLog::INFO);

			$this->pcntlWait($status);
		}
		else
		{
			\JLog::add('Running job: ' . $child, \JLog::INFO);

			$backup = new Backup($site);

			$backup->deleteOldBackups()->backup();
		}
	}
}
