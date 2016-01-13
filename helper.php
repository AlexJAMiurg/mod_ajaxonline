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
		$format= $params->get('format', 'debug');
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName(array('userid','u.name')))
			->from('#__session AS s')
			->where($db->quoteName('s.guest') . ' != 1')
			->join('LEFT', '#__users AS u ON s.userid = u.id');
		$db->setQuery($query);
		try
		{
		 $users = $db->loadObjectList('username');

		$users= $db->loadColumn(1);
		$userlist="<ul class=\"ajx_userlist\">";
//		echo "<li>".$query."</li>";
		foreach ($users as $name){
			$userlist.="<li>".$name."</li>";
		}
		$userlist.="</ul>";
		 return $userlist;
		}
		catch (RuntimeException $e)
		{
//			return "MOD_AJAXONLINE_HELPER_ERROR";
          return "test - ".$e." - ".$users;
		}
	}
}