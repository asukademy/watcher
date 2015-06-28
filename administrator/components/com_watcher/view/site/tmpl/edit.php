<?php
/**
 * Part of Component Watcher files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtmlBootstrap::tooltip();
JHtmlFormbehavior::chosen('select');
JHtmlBehavior::formvalidation();
JHtmlBehavior::tabstate();

/**
 * Prepare data for this template.
 *
 * @var $container Windwalker\DI\Container
 * @var $data      Windwalker\Data\Data
 * @var $item      \stdClass
 */
$container = $this->getContainer();
$form      = $data->form;
$item      = $data->item;

// Setting tabset
$tabs = array(
	'tab_basic'
);

if ($data->item->id)
{
	array_unshift($tabs, 'tab_backups');
}
?>
<!-- Validate Script -->
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'site.edit.cancel' || document.formvalidator.isValid(document.id('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>

<div id="watcher" class="windwalker site edit-form row-fluid">
	<form action="<?php echo JURI::getInstance(); ?>"  method="post" name="adminForm" id="adminForm"
		class="form-validate" enctype="multipart/form-data">

		<div class="form-inline form-inline-header">
			<?php
			echo $form->renderField('title');
			echo $form->renderField('alias');
			?>
		</div>

		<?php echo JHtmlBootstrap::startTabSet('siteEditTab', array('active' => 'tab_basic')); ?>

			<?php
			foreach ($tabs as $tab)
			{
				echo $this->loadTemplate($tab, array('tab' => $tab));
			}
			?>

		<?php echo JHtmlBootstrap::endTabSet(); ?>

		<!-- Hidden Inputs -->
		<div id="hidden-inputs">
			<input type="hidden" name="option" value="com_watcher" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

