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
use Watcher\Backup\Backup;
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

		$backup = new Backup($site);

		$url = $backup->backup();

		$this->out($url);

		return true;
	}
}
