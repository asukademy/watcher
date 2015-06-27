<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\DI\Container;
use Windwalker\Model\Filter\FilterHelper;
use Windwalker\Model\ListModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Watcher Backups model
 *
 * @since 1.0
 */
class WatcherModelBackups extends ListModel
{
	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'watcher';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_watcher';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_WATCHER';

	/**
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'backups';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'backup';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'backups';

	/**
	 * Configure tables through QueryHelper.
	 *
	 * @return  void
	 */
	protected function configureTables()
	{
		$queryHelper = $this->getContainer()->get('model.backups.helper.query', Container::FORCE_NEW);

		$queryHelper->addTable('backup', '#__watcher_backups')
			->addTable('site', '#__watcher_sites', 'site.id = backup.site_id');

		$this->filterFields = array_merge($this->filterFields, $queryHelper->getFilterFields());
	}

	/**
	 * The post getQuery object.
	 *
	 * @param JDatabaseQuery $query The db query object.
	 *
	 * @return  void
	 */
	protected function postGetQuery(\JDatabaseQuery $query)
	{
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method will only called in constructor. Using `ignore_request` to ignore this method.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'id', $direction = 'ASC')
	{
		parent::populateState($ordering, $direction);
	}

	/**
	 * Process the query filters.
	 *
	 * @param JDatabaseQuery $query   The query object.
	 * @param array          $filters The filters values.
	 *
	 * @return  JDatabaseQuery The db query object.
	 */
	protected function processFilters(\JDatabaseQuery $query, $filters = array())
	{
		// If no state filter, set published >= 0
		if (!isset($filters['backup.state']) && property_exists($this->getTable(), 'state'))
		{
			$query->where($query->quoteName('backup.state') . ' >= 0');
		}

		return parent::processFilters($query, $filters);
	}

	/**
	 * Configure the filter handlers.
	 *
	 * Example:
	 * ``` php
	 * $filterHelper->setHandler(
	 *     'backup.date',
	 *     function($query, $field, $value)
	 *     {
	 *         $query->where($field . ' >= ' . $value);
	 *     }
	 * );
	 * ```
	 *
	 * @param FilterHelper $filterHelper The filter helper object.
	 *
	 * @return  void
	 */
	protected function configureFilters($filterHelper)
	{
	}

	/**
	 * Configure the search handlers.
	 *
	 * Example:
	 * ``` php
	 * $searchHelper->setHandler(
	 *     'backup.title',
	 *     function($query, $field, $value)
	 *     {
	 *         return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
	 *     }
	 * );
	 * ```
	 *
	 * @param SearchHelper $searchHelper The search helper object.
	 *
	 * @return  void
	 */
	protected function configureSearches($searchHelper)
	{
	}
}
