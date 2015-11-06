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
class Ajax
{
	/**
	 * Method to get the application input
	 */
	protected function getInput()
	{
		return \JFactory::getApplication()->input;
	}

	/**
	 * Method to check whether the current request is an AJAX request or not
	 *
	 * @return bool
	 */
	public function isAjaxRequest()
	{
		// Check HTTP headers
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}

		// Check input variables
		$input = $this->getInput();
		$format = $input->getCmd('format');
		$tmpl = $input->getCmd('tmpl');
		$type = $input->getCmd('type');

		if (in_array($format, array('raw', 'feed')) || in_array($type, array('rss', 'atom')) || $tmpl == 'component')
		{
			return true;
		}

		return false;
	}
}
