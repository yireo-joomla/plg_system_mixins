<?php
/**
 * Joomla! System plugin - Mixins
 *
 * @author     Yireo <info@yireo.com>
 * @copyright  Copyright 2015 Yireo.com. All rights reserved
 * @license    GNU Public License
 * @link       http://www.yireo.com
 */

// Namespace
namespace Yireo\Checks;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Class Ajax
 *
 * @package Yireo\Checks
 */
class Frontend
{
	/**
	 * Method to get the application input
	 */
	protected function getApplication()
	{
		return \JFactory::getApplication();
	}

	/**
	 * Method to get the application input
	 */
	protected function getDocument()
	{
		return \JFactory::getDocument();
	}

	/**
	 * Method to check whether the current request is an AJAX request or not
	 *
	 * @return bool
	 */
	public function isHtmlFrontend()
	{
		if ($this->getApplication()->isSite() == false)
		{
			return false;
		}

		if ($this->getDocument()->getType() != 'html')
		{
			return false;
		}

		return true;
	}
}
