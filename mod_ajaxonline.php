<?php defined('_JEXEC') or die;

/**
 *
 * File       mod_session.php
 * Created    5/22/13 6:43 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

// Include the helper.
require_once __DIR__ . '/helper.php';

// Instantiate global document object
$doc = JFactory::getDocument();

$loadJquery = $params->get('loadJquery', 1);
$format= $params->get('format', 'html');
$refPeriod= $params->get('refPeriod', 5000);
$useincperiod= $params->get('useincperiod', 1);

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
				jQuery( \".ajaxonline_status\" ).css( \"border-color\",\"#00B300\");
				jQuery(\".ajaxonline_status\").css( \"background-color\",\"#BFFFBF\");
				col_time = setTimeout(function() {
					jQuery( \".ajaxonline_status\" ).css( \"border-color\",\"silver\");
					jQuery(\".ajaxonline_status\").css( \"background-color\",\"white\");
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