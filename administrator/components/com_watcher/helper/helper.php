<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Watcher helper.
 *
 * @since 1.0
 */
abstract class WatcherHelper
{
	/**
	 * Configure the Link bar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
	public static function addSubmenu($vName)
	{
		$app       = \JFactory::getApplication();
		$inflector = \JStringInflector::getInstance(true);

		// Add Category Menu Item
		if ($app->isAdmin())
		{
			JHtmlSidebar::addEntry(
				JText::_('JCATEGORY'),
				'index.php?option=com_categories&extension=com_watcher',
				($vName == 'categories')
			);
		}

		JHtmlSidebar::addEntry(
			'網站管理',
			'index.php?option=com_watcher&view=sites',
			($vName == 'sites')
		);

		$dispatcher = \JEventDispatcher::getInstance();
		$dispatcher->trigger('onAfterAddSubmenu', array('com_watcher', $vName));
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string  $option  Action option.
	 *
	 * @return  JObject
	 */
	public static function getActions($option = 'com_watcher')
	{
		$user   = JFactory::getUser();
		$result = new \JObject;

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.own',
			'core.edit.state',
			'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $option));
		}

		return $result;
	}
}
