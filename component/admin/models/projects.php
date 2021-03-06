<?php
/**
 * @package     Tracks
 * @subpackage  Admin
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Tracks Component projects Model
 *
 * @package     Tracks
 * @subpackage  Admin
 * @since       3.0
 */
class TracksModelProjects extends TrackslibModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_projects';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'projects_limit';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitstartField = 'auto';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  Configs
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'name', 'obj.name',
				'id', 'obj.id',
				's.name', 'c.name',
				'ordering', 'obj.ordering',
				// for filters
				'published', 'competition', 'season'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string       A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.competition');
		$id .= ':' . $this->getState('filter.season');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  object  Query object
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->_db;
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				array(
					'obj.*',
					'c.name as competition_name',
					's.name as season_name'
				)
			)
		);
		$query->from($db->qn('#__tracks_projects', 'obj'));
		$query->join('left', '#__tracks_competitions AS c on c.id = obj.competition_id');
		$query->join('left', '#__tracks_seasons AS s on s.id = obj.season_id');

		// Filter: like / search
		$search = $this->getState('filter.search', '');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('obj.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');

				// Match on season and competition name too
				$or = array(
					'obj.name LIKE ' . $search,
					'c.name LIKE ' . $search,
					's.name LIKE ' . $search
				);
				$query->where('(' . implode(' OR ', $or) . ')');
			}
		}

		$competitionId = $this->getState('filter.competition');

		if (is_numeric($competitionId))
		{
			$query->where($db->quoteName('c.id') . ' = ' . $competitionId);
		}

		$seasonId = $this->getState('filter.season');

		if (is_numeric($seasonId))
		{
			$query->where($db->quoteName('s.id') . ' = ' . $seasonId);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'obj.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
