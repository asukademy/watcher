<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Backup;

use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Nomnom\Nomnom;
use Watcher\Backup\Provider\EzsetProvider;
use Watcher\Backup\Storage\S3Storage;
use Watcher\Config\Config;
use Watcher\Table\Table;
use Windwalker\Data\Data;
use Windwalker\Helper\DateHelper;
use Windwalker\Joomla\DataMapper\DataMapper;

/**
 * The AbstractBackup class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Backup
{
	const STATE_UNPUBLISHED = 0;
	const STATE_PROCESS = 1;
	const STATE_FINISHED = 2;
	const STATE_FILE_DELETED = -1;

	/**
	 * Property site.
	 *
	 * @var  Data
	 */
	protected $site;

	/**
	 * Property backup.
	 *
	 * @var  Data
	 */
	protected $backup;

	/**
	 * Property tmpFile.
	 *
	 * @var \SplFileInfo
	 */
	protected $tmpFile;

	/**
	 * Property remoteUrl.
	 *
	 * @var string
	 */
	protected $remoteUrl;

	/**
	 * Property provider.
	 *
	 * @var  Provider\AbstractProvider
	 */
	protected $provider;

	/**
	 * Property storage.
	 *
	 * @var  Storage\BackupStorageInterface
	 */
	protected $storage;

	/**
	 * Class init.
	 *
	 * @param Data                           $site
	 * @param Provider\AbstractProvider      $provider
	 * @param Storage\BackupStorageInterface $storage
	 */
	public function __construct(Data $site = null, $provider = null, $storage = null)
	{
		$this->site = $site ? : new Data;

		$provider ? $this->setProvider($provider) : null;
		$storage ? $this->setStorage($storage) : null;
	}

	/**
	 * backup
	 *
	 * @return  string
	 */
	public function backup()
	{
		$this->createBackupProfile();

		$file = $this->downloadBackupFile();

		$url = $this->store($file);

		$this->backup->url = $url;
		$this->backup->size = Nomnom::file($file->getPathname())->to(Nomnom::kB);
		$this->backup->state = static::STATE_FINISHED;

		(new DataMapper(Table::BACKUPS))->updateOne($this->backup, 'id');

		$this->site->last_backup = $this->backup->created;

		(new DataMapper(Table::SITES))->updateOne($this->site, 'id');

		File::delete($file->getPathname());

		return $this->getStorage()->getBaseUrl() . '/' . $url;
	}

	/**
	 * createBackupProfile
	 *
	 * @return  Data
	 */
	public function createBackupProfile()
	{
		$date = DateHelper::getDate('now');

		$backup = [];
		$backup['site_id'] = $this->site->id;
		$backup['title'] = $this->site->title . ' 備份: ' . $date;
		$backup['alias'] = \JFilterOutput::stringURLUnicodeSlug($backup['title']);
		$backup['created'] = $date->toSql();
		$backup['state'] = static::STATE_PROCESS;

		$mapper = new DataMapper(Table::BACKUPS);

		$this->backup = $mapper->createOne(new Data($backup));

		return $this->backup;
	}

	/**
	 * store
	 *
	 * @param \SplFileInfo $file
	 *
	 * @return  string
	 */
	protected function store(\SplFileInfo $file)
	{
		$dest = 'backup/watcher/' . $this->site->id . '/backup-' . $this->getBackupIdentify() . '.zip';

		$this->getStorage()->upload($file->getPathname(), $dest);

		return $dest;
	}

	/**
	 * getBackupIdentify
	 *
	 * @return  string
	 */
	public function getBackupIdentify()
	{
		$base = new \JUri($this->site->site);
		$base->setScheme(null);

		return \JFilterOutput::stringURLSafe($base . '-' . $this->backup->created);
	}

	/**
	 * downloadBackupFile
	 *
	 * @return  \SplFileInfo
	 */
	protected function downloadBackupFile()
	{
		$backupTmpFile = $this->getTmpFile();

		if (is_dir($backupTmpFile->getPath()))
		{
			Folder::delete($backupTmpFile->getPath());
		}

		$this->getProvider()->download($backupTmpFile->getPathname());

		return $backupTmpFile;
	}

	/**
	 * getTmpFile
	 *
	 * @return  \SplFileInfo
	 */
	public function getTmpFile()
	{
		if (empty($this->tmpFile))
		{
			$this->tmpFile = new \SplFileInfo(JPATH_ROOT . '/tmp/backups/' . $this->site->id . '/' . DateHelper::getDate()->format('Y-m-d-H-i-s') . '.zip');
		}

		return $this->tmpFile;
	}

	/**
	 * deleteOldBackups
	 *
	 * @return  static
	 */
	public function deleteOldBackups()
	{
		$now = DateHelper::getDate();

		$last = $now->modify('-' . Config::get('backup.remain_days', 30) . ' days');

		$db = \JFactory::getDbo();

		$backups = (new DataMapper('#__watcher_backups'))
			->find(['created < ' . $db->q($last), 'site_id' => $this->site->id]);

		foreach ($backups as $backup)
		{
			$this->deleteBackup($backup);
		}

		return $this;
	}

	/**
	 * deleteBackup
	 *
	 * @param int|Data $backup
	 *
	 * @return  void
	 */
	public function deleteBackup($backup)
	{
		if (!$backup)
		{
			return;
		}

		if (!($backup instanceof Data))
		{
			$backup = $this->getDataMapper()->findOne($backup);
		}

		if (!$backup->id)
		{
			return;
		}

		$this->getStorage()->delete($backup->url);

		$this->getDataMapper()->delete($backup->id);
	}

	/**
	 * getDataMapper
	 *
	 * @return  DataMapper
	 */
	public function getDataMapper()
	{
		static $mapper;

		if (!$mapper)
		{
			$mapper = new DataMapper(Table::BACKUPS);
		}

		return $mapper;
	}

	/**
	 * Method to get property Backup
	 *
	 * @return  Data
	 */
	public function getBackup()
	{
		return $this->backup;
	}

	/**
	 * Method to set property backup
	 *
	 * @param   Data $backup
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setBackup($backup)
	{
		$this->backup = $backup;

		return $this;
	}

	/**
	 * Method to get property Storage
	 *
	 * @return  Storage\BackupStorageInterface
	 */
	public function getStorage()
	{
		if (!$this->storage)
		{
			$this->storage = new S3Storage;
		}

		return $this->storage;
	}

	/**
	 * Method to set property storage
	 *
	 * @param   Storage\BackupStorageInterface $storage
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setStorage(Storage\BackupStorageInterface $storage)
	{
		$this->storage = $storage;

		return $this;
	}

	/**
	 * Method to get property Provider
	 *
	 * @return  Provider\AbstractProvider
	 */
	public function getProvider()
	{
		if (!$this->provider)
		{
			$this->provider = new EzsetProvider($this->site);
		}

		return $this->provider;
	}

	/**
	 * Method to set property provider
	 *
	 * @param   Provider\AbstractProvider $provider
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setProvider(Provider\AbstractProvider $provider)
	{
		$provider->setSite($this->site);

		$this->provider = $provider;

		return $this;
	}
}
