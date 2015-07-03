<?php
/**
 * Part of Watcher project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Watcher\Mail;

use Windwalker\DataMapper\DataMapper;

/**
 * The Mailer class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Mailer
{
	/**
	 * sendAdmin
	 *
	 * @param string $subject
	 * @param string $body
	 * @param bool   $mode
	 *
	 * @return  void
	 */
	public static function sendAdmin($subject, $body, $mode = false)
	{
		$config = \JFactory::getConfig();

		$users = (new DataMapper('#__users'))->find(['block' => 0, 'sendEmail' => 1]);

		foreach ($users as $user)
		{
			\JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), $user->email, $subject, $body, $mode);
		}
	}
}
