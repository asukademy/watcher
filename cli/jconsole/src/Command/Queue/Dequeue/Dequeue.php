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
use Watcher\Mail\Mailer;
use Windwalker\Data\Data;
use Windwalker\Helper\DateHelper;

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

		try
		{
			$backup = new Backup($site);

			$backup->deleteOldBackups()->backup();
		}
		catch (\Exception $e)
		{
			$this->notice($e, $site);

			throw $e;
		}

		$this->ironmq->deleteMessage("Backup", $message->id);

		$this->out('Dequeue and backup site: ' . $site->site);

		return true;
	}

	/**
	 * notice
	 *
	 * @param \Exception $e
	 * @param Data       $site
	 *
	 * @return  void
	 */
	protected function notice(\Exception $e, Data $site)
	{
		$config = \JFactory::getConfig();

		$subject = "[Watcher] 網站 {$site->title} (ID: {$site->id}) 的自動備份失敗 - " . DateHelper::getDate()->toSql(true);

		$body = <<<BODY
Hi Watcher 管理員

網站 {$site->title} (ID: {$site->id}) 的自動備份失敗

請抽空檢查原因。

錯誤訊息:
> {$e->getMessage()}

Simular Watcher: {$config->get('watcher_url')}/administrator/?option=com_watcher&view=site&layout=edit&id={$site->id}&mgmt
BODY;

		Mailer::sendAdmin($subject, $body);
	}
}
