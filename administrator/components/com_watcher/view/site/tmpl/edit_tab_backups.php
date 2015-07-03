<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Nomnom\Nomnom;
use Watcher\Backup\Backup;
use Watcher\Backup\Provider\AbstractProvider;
use Windwalker\Data\Data;
use Windwalker\Helper\DateHelper;

$tab = $data->tab;
?>

<?php echo JHtmlBootstrap::addTab('siteEditTab', $tab, '備份檔案') ?>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
			<tr>
				<th>ID</th>
				<td>時間</td>
				<th>名稱</th>
				<th>備份日期</th>
				<th>檔案大小</th>
				<th>狀態</th>
				<th>下載</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ((array) $data->backups as $k => $backup) : ?>

			<?php
				$backup = new Data($backup);
				$backupHandler = new Backup(new Data($data->item), AbstractProvider::create($backup->type));
				$backupHandler->setBackup($backup);

				// Diff
				$now = DateHelper::getDate('now', 'UTC');
				$date = DateHelper::getDate($backup->created, 'UTC');

				$diff = $now->diff($date);
			?>
			<tr>
				<td>
					<?php echo $backup->id; ?>
				</td>
				<td>
					<?php if ($diff->days == 0): ?>
						今天
					<?php else: ?>
						<?php echo $diff->days; ?> 天前
					<?php endif; ?>
				</td>
				<td>
					<?php echo $backup->title; ?>
				</td>
				<td>
					<?php echo JHtml::date($backup->created, JDate::$format); ?>
				</td>
				<td align="right">
					<?php echo number_format(Nomnom::nom($backup->size)->from(Nomnom::kB)->to(Nomnom::MB), 2); ?> MB
				</td>
				<td>
					<?php if ($backup->state == Backup::STATE_PROCESS): ?>
					<span class="label label-warning">
						備份中
					</span>
					<?php elseif ($backup->state == Backup::STATE_FINISHED): ?>
					<span class="label label-success">
						備份完成
					</span>
					<?php elseif ($backup->state == Backup::STATE_FAILURE): ?>
						<span class="label label-danger">
						備份失敗
					</span>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($backup->state == Backup::STATE_FINISHED): ?>
					<a class="btn btn-info" href="<?php echo $backupHandler->getStorage()->getBaseUrl() . '/' . $backup->url; ?>">
						<span class="glyphicon glyphicon-download-alt"></span> 下載
					</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo JHtmlBootstrap::endTab(); ?>