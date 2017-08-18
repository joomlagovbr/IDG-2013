<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_mailto
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mailer Component Controller.
 *
 * @package     Joomla.Site
 * @subpackage  com_mailto
 * @since       1.5
 */
class MailtoController extends JControllerLegacy
{
	/**
	 * Show the form so that the user can send the link to someone.
	 *
	 * @return  void
	 *
	 * @since 1.5
	 */
	public function mailto()
	{
		$session = JFactory::getSession();
		$session->set('com_mailto.formtime', time());
		$this->input->set('view', 'mailto');
		$this->display();
	}

	/**
	 * Send the message and display a notice
	 *
	 * @return  void
	 *
	 * @since  1.5
	 */
	public function send()
	{
		// Check for request forgeries
		$this->checkToken();

		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$timeout = $session->get('com_mailto.formtime', 0);

		if ($timeout == 0 || time() - $timeout < 20)
		{
			JError::raiseNotice(500, JText::_('COM_MAILTO_EMAIL_NOT_SENT'));

			return $this->mailto();
		}

		$SiteName = $app->get('sitename');
		$link     = MailtoHelper::validateHash($this->input->get('link', '', 'post'));

		// Verify that this is a local link
		if (!$link || !JUri::isInternal($link))
		{
			// Non-local url...
			JError::raiseNotice(500, JText::_('COM_MAILTO_EMAIL_NOT_SENT'));

			return $this->mailto();
		}

		// An array of email headers we do not want to allow as input
		$headers = array (
			'Content-Type:',
			'MIME-Version:',
			'Content-Transfer-Encoding:',
			'bcc:',
			'cc:'
		);

		// An array of the input fields to scan for injected headers
		$fields = array(
			'mailto',
			'sender',
			'from',
			'subject',
		);

		/*
		 * Here is the meat and potatoes of the header injection test.  We
		 * iterate over the array of form input and check for header strings.
		 * If we find one, send an unauthorized header and die.
		 */
		foreach ($fields as $field)
		{
			foreach ($headers as $header)
			{
				if (strpos($_POST[$field], $header) !== false)
				{
					JError::raiseError(403, '');
				}
			}
		}

		/*
		 * Free up memory
		 */
		unset ($headers, $fields);

		$email           = $this->input->post->getString('mailto', '');
		$sender          = $this->input->post->getString('sender', '');
		$from            = $this->input->post->getString('from', '');
		$subject_default = JText::sprintf('COM_MAILTO_SENT_BY', $sender);
		$subject         = $this->input->post->getString('subject', $subject_default);

		// Check for a valid to address
		$error = false;

		if (!$email || !JMailHelper::isEmailAddress($email))
		{
			$error = JText::sprintf('COM_MAILTO_EMAIL_INVALID', $email);
			JError::raiseWarning(0, $error);
		}

		// Check for a valid from address
		if (!$from || !JMailHelper::isEmailAddress($from))
		{
			$error = JText::sprintf('COM_MAILTO_EMAIL_INVALID', $from);
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return $this->mailto();
		}

		// Build the message to send
		$msg  = JText::_('COM_MAILTO_EMAIL_MSG');
		$body = sprintf($msg, $SiteName, $sender, $from, $link);

		// Clean the email data
		$subject = JMailHelper::cleanSubject($subject);
		$body    = JMailHelper::cleanBody($body);

		// To send we need to use punycode.
		$from  = JStringPunycode::emailToPunycode($from);
		$from  = JMailHelper::cleanAddress($from);
		$email = JStringPunycode::emailToPunycode($email);

		// Send the email
		if (JFactory::getMailer()->sendMail($from, $sender, $email, $subject, $body) !== true)
		{
			JError::raiseNotice(500, JText::_('COM_MAILTO_EMAIL_NOT_SENT'));

			return $this->mailto();
		}

		$this->input->set('view', 'sent');
		$this->display();
	}
}
