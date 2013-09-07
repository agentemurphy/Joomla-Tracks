<?php
/**
 * @version    2.0
 * @package    JoomlaTracks
 * @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Joomla Tracks is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class TracksToolbar extends FOFToolbar
{
	protected function getMyViews()
	{
		$views = array(
			'projects',
			'competitions',
			'seasons',
			'teams',
			'individuals',
			'rounds',
		);
		return $views;
	}

	public function renderToolbar($view = null, $task = null, $input = null)
	{
		if ($view == 'menu')
		{
			return;
		}

		return parent::renderToolbar($view, $task, $input);
	}

	public function renderSubmenu()
	{
		return;
	}

	public function onProjectindividualsBrowse()
	{
		JToolBarHelper::title('Tracks &ndash; ' . JText::_('COM_TRACKS_Project_Participants' ), 'generic.png');

		JToolBarHelper::back();
		JToolBarHelper::deleteList(JText::_('COM_TRACKS_DELETEPROJECTINDIVIDUALCONFIRM'));
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
	}

	public function onIndividualsBrowse()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS_Individuals'), 'generic.png' );

		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::assign();
	}

	public function onIndividualsAssign()
	{
		JToolBarHelper::title(JText::_('COM_TRACKS_Individuals'), 'generic.png' );

		JToolBarHelper::save('saveassign', 'Save');
		JToolBarHelper::back();
	}
}
