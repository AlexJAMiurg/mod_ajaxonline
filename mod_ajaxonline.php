<?php defined('_JEXEC') or die;

/**
 *
 * File       mod_ajaxonline.php
 * Created    December 15, 2015
 * Author     Alexandr Yakimov | https://plus.google.com/u/0/+AlexandrYakimov
 * Support    https://github.com/AlexJAMiurg
 * Copyright  Copyright (C) 2015 Alexandr Yakimov. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

// Include the helper.
require_once __DIR__ . '/helper.php';

// Instantiate global document object
$doc = JFactory::getDocument();
// Get module parameters
$loadJquery = $params->get('loadJquery', 1);
$format = $params->get('format', 'html');
$refPeriod = $params->get('refPeriod', 5000);
$useincperiod = $params->get('useincperiod', 1);
$basebackcolour = $params->get('basebackcolour', 'transparent');
$basebordercolour = $params->get('basebordercolour', 'transparent');
$indbackcolour = $params->get('indbackcolour', '#BFFFBF');
$indbordercolour = $params->get('indbordercolour', '#00B300');
// Load jQuery
if ($loadJquery == '1') {
	$doc->addScript('//code.jquery.com/jquery-latest.min.js');
}

$js = "
var uptime, col_time, autouptimer;
function check_online(first_load) {

	var oldtext=jQuery( \".ajaxonline_status\" ).html(),
	request = {
					'option' : 'com_ajax',
					'module' : 'ajaxonline',
					'format' : '{$format}'
				};
	jQuery.ajax({
	type: \"POST\",
	data:request,
	success:function(ans){
			if ((oldtext==ans)&&({$useincperiod})){
				uptime = uptime + {$refPeriod};
   			} else {
				jQuery( \".ajaxonline_status\" ).html(ans);
				uptime = {$refPeriod};
                if(first_load != 1){
				jQuery( \".ajaxonline_status\" ).css( \"border-color\",\"{$indbordercolour}\");
				jQuery(\".ajaxonline_status\").css( \"background-color\",\"{$indbackcolour}\");
				col_time = setTimeout(function() {
					jQuery( \".ajaxonline_status\" ).css( \"border-color\",\"{$basebordercolour}\");
					jQuery(\".ajaxonline_status\").css( \"background-color\",\"{$basebordercolour}\");
					clearTimeout(col_time);
					}, 3000);
                    }
			}
            clearTimeout(autouptimer);
			autouptimer=setTimeout(function() { check_online();}, uptime);
	},
	error: function(response) {
		jQuery( \".ajaxonline_status\" ).html(\"error: \"+ans);
	}
	});
	return false;
};
jQuery(document).ready( function(){
check_online(1);
});";


$doc->addScriptDeclaration($js);

require(JModuleHelper::getLayoutPath('mod_ajaxonline'));