<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Windwalker\Model\Model;
use Windwalker\View\AbstractView;

/**
 * The WatcherControllerSiteDisplay class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WatcherControllerSiteDisplay extends \Windwalker\Controller\DisplayController
{
	/**
	 * assignModel
	 *
	 * @param AbstractView $view
	 *
	 * @return  void
	 */
	protected function assignModel($view)
	{
		/** @var Model $model */
		$model = $this->getModel('Backups', null, ['ignore_request' => true]);

		$state = $model->getState();
		$filters = $state->get('filter', []);
		$filters['backup.site_id'] = $this->input->get('id');
		$state->set('filter', $filters);
		$state->set('list.limit', 0);
		$state->set('list.ordering', 'id');
		$state->set('list.direction', 'DESC');

		$view->setModel($model);
	}
}
