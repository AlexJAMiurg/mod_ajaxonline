<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    December 15, 2015
 * Author     Alexandr Yakimov | https://plus.google.com/u/0/+AlexandrYakimov
 * Support    https://github.com/AlexJAMiurg
 * Copyright  Copyright (C) 2015 Alexandr Yakimov. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

class modAjaxOnlineHelper {

	public static function getAjax() {
		// Set UTF-8  multilanguage support
		header('Content-Type: text/html; charset=utf-8');.
		// Get module parameters
		jimport('joomla.application.module.helper');
		$input  = JFactory::getApplication()->input;
		$module = JModuleHelper::getModule('ajaxonline');
		$params = new JRegistry();
		$params->loadString($module->params);
		$format= $params->get('format', 'debug');
		//SQL query
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
		//return UL with 
		 return $userlist;
		}
		catch (RuntimeException $e)
		{
			return "MOD_AJAXONLINE_HELPER_ERROR";
//         return "test - ".$e." - ".$users;
		}
	}
}