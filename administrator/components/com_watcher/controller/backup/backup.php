<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */
use Watcher\Table\Table;
use Windwalker\Joomla\DataMapper\DataMapper;

/**
 * The WatcherControllerBackup class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WatcherControllerBackupBackup extends \Windwalker\Controller\Controller
{
	/**
	 * Method to run this controller.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$data = new \Windwalker\Data\Data;

		try
		{
			$id = $this->input->get('id');

			if (!$id)
			{
				throw new Exception('No site id');
			}

			$site = (new DataMapper(Table::SITES))->findOne($id);

			if ($site->isNull())
			{
				throw new Exception('Site: ' . $id . ' not found');
			}

			$backup = new \Watcher\Backup\Backup($site);

			$url = $backup->backup();
		}
		catch (\Exception $e)
		{
			return $this->response($e);
		}

		$data->url = $url;

		return $this->response($data);
	}

	/**
	 * response
	 *
	 * @param   mixed  $response
	 *
	 * @return  boolean
	 */
	protected function response($response)
	{
		$buffer = new \Windwalker\View\Json\JsonBuffer($response);

		header('Content-Type: text/json');

		echo $buffer;

		return exit();
	}
}
