<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\DI\Container;
use Windwalker\Html\HtmlElement;
use Windwalker\Model\Model;
use Windwalker\View\Engine\PhpEngine;
use Windwalker\View\Html\EditView;
use Windwalker\Xul\XulEngine;

// No direct access
defined('_JEXEC') or die;

/**
 * Watcher Sites view
 *
 * @since 1.0
 */
class WatcherViewSiteHtml extends EditView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'watcher';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_watcher';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_WATCHER';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'site';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'site';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'sites';

	/**
	 * Method to instantiate the view.
	 *
	 * @param Model            $model     The model object.
	 * @param Container        $container DI Container.
	 * @param array            $config    View config.
	 * @param SplPriorityQueue $paths     Paths queue.
	 */
	public function __construct(Model $model = null, Container $container = null, $config = array(), \SplPriorityQueue $paths = null)
	{
		$this->engine = new PhpEngine;

		parent::__construct($model, $container, $config, $paths);
	}

	/**
	 * Prepare data hook.
	 *
	 * @return  void
	 */
	protected function prepareData()
	{
		parent::prepareData();

		$data = $this->getData();

		if ($data->item->id)
		{
			$data->backups = $this->get('Items', 'backups');
		}

		// JS
		$this->container->get('helper.asset')->addJS('main.js');
	}

	/**
	 * Configure the toolbar button set.
	 *
	 * @param   array   $buttonSet Customize button set.
	 * @param   object  $canDo     Access object.
	 *
	 * @return  array
	 */
	protected function configureToolbar($buttonSet = array(), $canDo = null)
	{
		$buttonSet = parent::configureToolbar($buttonSet, $canDo);

		if ($this->data->item->id)
		{
			$buttonSet['backup'] = array(
				'handler'  => 'custom',
				'args'     => array(new HtmlElement('button', '<i class="icon-box-add"></i> 立即備份', [
					'onclick' => sprintf('Watcher.backup(%s, event)', $this->data->item->id),
					'class' => 'btn btn-small btn-primary'
				])),
				'access'   => true,
				'priority' => 100
			);

			$uri = new JUri($this->data->item->url);
			$uri->setVar('access_token', $this->data->item->access_token);

			$buttonSet['test'] = array(
				'handler'  => 'custom',
				'args'     => array(new HtmlElement('a', '<i class="icon-download"></i> 測試下載', [
					'target' => '_blank',
					'style' => 'text-shadow: none',
					'class' => 'btn btn-small btn-info',
					'href' => $uri->toString()
				])),
				'access'   => true,
				'priority' => 100
			);
		}

		return $buttonSet;
	}
}
