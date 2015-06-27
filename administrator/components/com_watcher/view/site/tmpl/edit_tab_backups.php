<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Nomnom\Nomnom;

$tab = $data->tab;
?>

<?php echo JHtmlBootstrap::addTab('siteEditTab', $tab, '備份檔案') ?>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
			<tr>
				<th>ID</th>
				<th>名稱</th>
				<th>備份日期</th>
				<th>檔案大小</th>
				<th>下載</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($data->backups as $k => $backup) : ?>
			<tr>
				<td>
					<?php echo $backup->id; ?>
				</td>
				<td>
					<?php echo $backup->title; ?>
				</td>
				<td>
					<?php echo $backup->created; ?>
				</td>
				<td align="right">
					<?php echo number_format(Nomnom::nom($backup->size)->from(Nomnom::kB)->to(Nomnom::MB), 2); ?> MB
				</td>
				<td>
					<a class="btn btn-default" href="<?php echo $backup->url; ?>">下載</a>
				</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo JHtmlBootstrap::endTab(); ?>