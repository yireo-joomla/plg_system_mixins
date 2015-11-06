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

/**
 * Mixins System Plugin
 */
class PlgSystemMixinsAbstract extends JPlugin
{
	/**
	 * @var JApplication
	 */
	protected $app;

	/**
	 * @var JDatabase
	 */
	protected $db;

	/**
	 * Mapping of mixin classes and methods
	 */
	protected $mixinMapping = array();

	/**
	 * Array containing the initialized mixin objects
	 */
	protected $mixinContainer = array();

	/**
	 * Constructor
	 *
	 * @param object $subject
	 * @param array  $config
	 */
	public function __construct(&$subject, $config = array())
	{
		if (!empty($this->mixins))
		{
			foreach ($this->mixins as $mixin)
			{
				$this->doMixinMethods($mixin);
			}
		}

		return parent::__construct($subject, $config);
	}

	/**
	 * Magic call method to allow for mixin methods to be called
	 *
	 * @param $methodName
	 * @param $arguments
	 * @throws Exception
	 *
	 * @return mixed
	 */
	public function __call($methodName, $arguments)
	{
		if (array_key_exists($methodName, $this->mixinMapping))
		{
			$mixinClass = $this->mixinMapping[$methodName];

			$mixinObject = new $mixinClass;
			$mixinObject->origin = $this;

			return call_user_func_array(array($mixinObject, $methodName), $arguments);
		}

		throw new Exception('Undefined method ' . $methodName);
	}

	/**
	 * Try to mixin with a mixin identified by a string
	 *
	 * @param string $mixin
	 *
	 * @return bool
	 */
	protected function doMixinMethods($mixin)
	{
		// Include the mixin file
		if ($this->includeMixinFile($mixin) == false)
		{
			return false;
		}

		// Load the mixin class
		if (!$mixinClass = $this->loadMixinClass($mixin))
		{
			return false;
		}

		// Get the public mixin methods
		$mixinPublicMethods = $this->getPublicMixinMethods($mixinClass);

		// Add the mixin methods to the internal mapping for usage with __call()
		if (!empty($mixinPublicMethods))
		{
			foreach ($mixinPublicMethods as $mixinPublicMethod)
			{
				$this->mixinMapping[$mixinPublicMethod] = $mixinClass;
			}
		}

		return true;
	}

	/**
	 * Try to include the mixin file
	 *
	 * @param string $mixin
	 *
	 * @return bool
	 */
	public function includeMixinFile($mixin)
	{
		$mixinFile = dirname(__DIR__) . '/mixins/' . $mixin . '.php';

		if (file_exists($mixinFile) == false)
		{
			return false;
		}

		require_once $mixinFile;

		return true;
	}

	/**
	 * Try to load the mixin class
	 *
	 * @param $mixin
	 *
	 * @return bool|string
	 */
	public function loadMixinClass($mixin)
	{
		$mixinParts = explode('/', $mixin);

		$mixinClass = array('Yireo');

		foreach ($mixinParts as $mixinPart)
		{
			$mixinClass[] = ucfirst($mixinPart);
		}

		$mixinClass = implode('\\', $mixinClass);

		if (class_exists($mixinClass) == false)
		{
			return false;
		}

		return $mixinClass;
	}

	/**
	 * Return all public mixin methods
	 *
	 * @param $mixinClass
	 *
	 * @return array
	 */
	public function getPublicMixinMethods($mixinClass)
	{
		$mixinAllMethods = get_class_methods($mixinClass);
		$mixinPublicMethods = array();

		foreach ($mixinAllMethods as $mixinMethod)
		{
			$reflect = new ReflectionMethod($mixinClass, $mixinMethod);

			if ($reflect->isPublic())
			{
				$mixinPublicMethods[] = $mixinMethod;
			}
		}

		return $mixinPublicMethods;
	}
}
