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
namespace Yireo\Actions;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Class Tag
 *
 * @package Yireo\Actions
 */
class Tag
{
	/**
	 * Method to check whether the current request is an AJAX request or not
	 *
	 * @param string $tag
	 * @throws \Exception
	 *
	 * @return bool
	 */
	public function parseBodyTags($tagString)
	{
		if (is_string($tagString) == false)
		{
			throw new \Exception('Tag is not a string');
		}

		$body = \JResponse::getBody();
		$tags = array();

		// Match "foobar" tag with {foobar var=1 var=2}
		if (preg_match_all('/\{' . $tagString . '([^\}]+)\}/', $body, $matches))
		{
			foreach ($matches[0] as $matchIndex => $match)
			{
				$original = $matches[0][$matchIndex];
				$arguments = $matches[1][$matchIndex];

				$tag = array(
					'original' => $original,
					'arguments' => array(),
				);

				$arguments = explode(' ', $arguments);

				$i = 0;
				foreach ($arguments as $argument)
				{
					$argument = trim($argument);

					if (!empty($argument))
					{
						$tag['arguments'][] = $argument;
						$i++;
					}
				}

				$tags[] = $tag;
			}
		}

		return $tags;
	}

	/**
	 * Replace one string with another in the HTML body
	 *
	 * @param $originalHtml
	 * @param $newHtml
	 */
	public function replaceBodyTags($originalHtml, $newHtml)
	{
		$body = \JResponse::getBody();

		$body = str_replace($originalHtml, $newHtml, $body);

		\JResponse::setBody($body);
	}
}
