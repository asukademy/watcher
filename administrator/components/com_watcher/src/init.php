<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

JLoader::registerPrefix('Watcher', JPATH_BASE . '/components/com_watcher');
JLoader::registerNamespace('Watcher', JPATH_ADMINISTRATOR . '/components/com_watcher/src');
JLoader::registerNamespace('Windwalker', __DIR__);
JLoader::register('WatcherComponent', JPATH_BASE . '/components/com_watcher/component.php');
