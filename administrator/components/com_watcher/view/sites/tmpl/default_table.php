<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Data\Data;

// No direct access
defined('_JEXEC') or die;

// Prepare script
JHtmlBehavior::multiselect('adminForm');

/**
 * Prepare data for this template.
 *
 * @var $container Windwalker\DI\Container
 * @var $data      Windwalker\Data\Data
 * @var $asset     Windwalker\Helper\AssetHelper
 * @var $grid      Windwalker\View\Helper\GridHelper
 * @var $date      \JDate
 */
$container = $this->getContainer();
$asset     = $container->get('helper.asset');
$grid      = $data->grid;
$date      = $container->get('date');

// Set order script.
$grid->registerTableSort();
?>

<!-- LIST TABLE -->
<table id="siteList" class="table table-striped adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!--STATE-->
	<th width="5%" class="nowrap center">
		<?php echo $grid->sortTitle('JSTATUS', 'site.state'); ?>
	</th>

	<!--TITLE-->
	<th class="center">
		<?php echo $grid->sortTitle('JGLOBAL_TITLE', 'site.title'); ?>
	</th>

	<!--CATEGORY-->
	<th width="10%" class="center">
		<?php echo $grid->sortTitle('JCATEGORY', 'category.title'); ?>
	</th>

	<!--DOWNLOAD-->
	<th width="10%" class="center">
		下載
	</th>

	<!--LAST-->
	<th width="10%" class="center">
		<?php echo $grid->sortTitle('最後一次備份', 'site.last_backup'); ?>
	</th>

	<!--USER-->
	<th width="10%" class="center">
		<?php echo $grid->sortTitle('JAUTHOR', 'user.name'); ?>
	</th>

	<!--TOKEN-->
	<th width="10%" class="center">
		Token
	</th>

	<!--ID-->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'site.id'); ?>
	</th>
</tr>
</thead>

<!--PAGINATION-->
<tfoot>
<tr>
	<td colspan="15">
		<div class="pull-left">
			<?php echo $data->pagination->getListFooter(); ?>
		</div>
	</td>
</tr>
</tfoot>

<!-- TABLE BODY -->
<tbody>
<?php foreach ($data->items as $i => $item)
	:
	// Prepare data
	$item = new Data($item);

	// Prepare item for GridHelper
	$grid->setItem($item, $i);
	?>
	<tr class="site-row" sortable-group-id="<?php echo $item->catid; ?>">

		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->site_id); ?>
		</td>

		<!--STATE-->
		<td class="center">
			<div class="btn-group">
				<!-- STATE BUTTON -->
				<?php echo $grid->state() ?>
			</div>
		</td>

		<!--TITLE-->
		<td class="has-context quick-edit-wrap">
			<div class="item-title">
				<!-- Checkout -->
				<?php echo $grid->checkoutButton(); ?>

				<!-- Title -->
				<?php echo $grid->editTitle(); ?>
			</div>

			<!-- Sub Title -->
			<div class="small">
				<a target="_blank" class="text-muted" href="<?php echo $item->site; ?>"><?php echo $item->site; ?> <i class="icon-share-alt"></i></a>
			</div>
		</td>

		<!--CATEGORY-->
		<td class="center">
			<?php echo $this->escape($item->category_title); ?>
		</td>

		<!--DOWNLOAD-->
		<td class="center">
			<a class="btn btn-info btn-small" href="<?php echo JRoute::_('index.php?option=com_watcher&task=site.download&id=' . $item->id) ?>">
				<span class="glyphicon glyphicon-download-alt"></span> 下載最後的備份
			</a>
		</td>

		<!--LAST-->
		<td class="center">
			<?php if ($item->last_backup != '0000-00-00 00:00:00'): ?>
				<?php echo JHtml::_('date', $item->last_backup, JText::_('DATE_FORMAT_LC4')); ?>
			<?php endif; ?>
		</td>

		<!--USER-->
		<td class="center">
			<?php echo $this->escape($item->user_name); ?>
		</td>

		<!--TOKEN-->
		<td class="center">
			<input class="span12" type="text" disabled value="<?php echo $item->access_token; ?>" />
		</td>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>

	</tr>
<?php endforeach; ?>
</tbody>
</table>
