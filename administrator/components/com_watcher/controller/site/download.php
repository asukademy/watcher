<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Watcher\Backup\Backup;
use Watcher\Table\Table;
use Windwalker\Joomla\DataMapper\DataMapper;
use Windwalker\Model\Model;
use Windwalker\View\AbstractView;

/**
 * The WatcherControllerSiteDisplay class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WatcherControllerSiteDownload extends \Windwalker\Controller\Admin\AbstractAdminController
{
	/**
	 * Method to run this controller.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$id = $this->input->get('id');

		if (!$id)
		{
			$this->redirectToList('沒有 id', 'error');
		}

		$backup = (new DataMapper(Table::BACKUPS))->findOne([
			'site_id' => $id,
			'state >= ' . Backup::STATE_FINISHED
		], 'id DESC');

		if ($backup->isNull())
		{
			$this->redirectToList('找不到合適的備份擋', 'error');
		}

		$backupHandler = new Backup((new DataMapper(Table::SITES))->findOne($id));

		$this->redirect($backupHandler->getStorage()->getBaseUrl() . '/' . $backup->url);
	}
}
