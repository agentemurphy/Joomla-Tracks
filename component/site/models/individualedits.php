<?php
/**
* @version    $Id: roundresult.php 61 2008-04-24 15:20:36Z julienv $
* @package    JoomlaTracks
* @copyright    Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Joomla Tracks Component Front page Model
 *
 * @package     Tracks
 * @since 0.1
 */
class TracksModelIndividualedits extends FOFModel
{
	/**
	 * Public class constructor
	 *
	 * @param   type  $config  The configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->table = 'individual';
	}

	public function findFormFilename($source, $paths = array())
	{
		return JPATH_COMPONENT_ADMINISTRATOR . '/views/individual/tmpl/form.form.xml';
	}

	/**
	 * return individual id for user
	 *
	 * @param   int  $user_id  user id
	 *
	 * @return int
	 */
	public function getUserIndividual($user_id)
	{
		if (!$user_id)
		{
			return false;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id');
		$query->from('#__tracks_individuals');
		$query->where('user_id = ' . (int) $user_id);

		$db->setQuery($query);
		$res = $db->loadResult();

		return $res;
	}

	/**
	 * Checks out the current item
	 *
	 * @return  boolean
	 */
	public function checkout()
	{
		$table = $this->getTable($this->table);
		$status = $table->checkout(FOFPlatform::getInstance()->getUser()->id, $this->id);

		if (!$status)
		{
			$this->setError($table->getError());
		}

		return $status;
	}
}
