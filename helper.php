<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    6/7/13 1:51 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

class modAjaxOnlineHelper {

	public static function getAjax() {

		// Get module parameters
		jimport('joomla.application.module.helper');
		$input  = JFactory::getApplication()->input;
		$module = JModuleHelper::getModule('ajaxonline');
		$params = new JRegistry();
		$params->loadString($module->params);
		$format= $params->get('format', 'html');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName(array('a.username', 'a.userid', 'a.client_id')))
			->from('#__session AS a')
			->where($db->quoteName('a.userid') . ' != 0')
			->where($db->quoteName('a.client_id') . ' = 0')
			->group($db->quoteName(array('a.username', 'a.userid', 'a.client_id')));

		$user = JFactory::getUser();
				return array();
			$query->join('LEFT', '#__user_usergroup_map AS m ON m.user_id = a.userid')
				->join('LEFT', '#__usergroups AS ug ON ug.id = m.group_id')
				->where('ug.id in (' . implode(',', $groups) . ')')
				->where('ug.id <> 1');
		}
		$db->setQuery($query);
		try
		{
		 $users = $db->loadObjectList('username');
		 return $users;
		}
		catch (RuntimeException $e)
		{
			return "MOD_AJAXONLINE_HELPER_ERROR";
		}
	}
}