/**
 * @package     Joomla.Administrator
 * @subpackage  com_watcher
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

var Watcher = {
	/**
	 * Backup.
	 *
	 * @param site_id
	 * @param event
	 */
	backup: function(site_id, event)
	{
		if (!site_id)
		{
			throw new Error('No site id');
		}

		Joomla.renderMessages([[
			'備份中：' + '<img src="components/com_watcher/images/ajax-loader.gif">'
		]]);

		var button = jQuery(event.target);

		button.attr('disabled', true);

		jQuery.ajax({
			url: 'index.php?option=com_watcher&task=backup.backup&id=' + site_id,
			dataType: 'json',
			error: function (xhr, textStatus, thrownError)
			{
				Joomla.renderMessages([[xhr.status + ' ' + xhr.statusText]]);
			},
			success: function (data, textStatus)
			{
				if (!data.success)
				{
					alert(data.message);
				}

				Joomla.renderMessages([['Backup success']]);
			},
			complete: function()
			{
				button.attr('disabled', false);
			}
		});
	}
};
