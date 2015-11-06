<?php
/**
 * Joomla! System plugin - Mixins
 *
 * @author     Yireo <info@yireo.com>
 * @copyright  Copyright 2015 Yireo.com. All rights reserved
 * @license    GNU Public License
 * @link       http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Require the parent
require_once __DIR__ . '/mixins/abstract.php';

/**
 * Mixins System Plugin
 */
class PlgSystemMixins extends PlgSystemMixinsAbstract
{
	/**
	 * Collection of mixins used in this plugin
	 */
	protected $mixins = array(
		'checks/ajax',
		'checks/frontend',
		'actions/tag',
	);

	/**
	 * Catch the event onAfterInitialise
	 *
	 * @return bool
	 */
	public function onAfterRender()
	{
		if ($this->isAjaxRequest() || $this->isHtmlFrontend() == false)
		{
			return false;
		}

		// {foobar some example}
		if ($tags = $this->parseBodyTags('foobar'))
		{
			foreach ($tags as $tag)
			{
				// var_dump($tag);
				$tagHtml = '<strong>Mixins Plugin: ' . var_export($tag['arguments'], true) . '</strong>';

				$this->replaceBodyTags($tag['original'], $tagHtml);
			}
		}

		return true;
	}
}
