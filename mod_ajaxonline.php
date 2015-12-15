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

$js = <<<JS
(function ($) {
	$(document).on('click', 'input[type=submit]', function () {
		var value   = $('input[name=data]').val(),
			action  = $(this).attr('class'),
			request = {
					'option' : 'com_ajax',
					'module' : 'session',
					'cmd'    : action,
					'data'   : value,
					'format' : '{$format}'
				};
		$.ajax({
			type   : 'POST',
			data   : request,
			success: function (response) {
				console.log(response);
				if(response.data){
					var result = '';
					$.each(response.data, function (index, value) {
						result = result + ' ' + value;
					});

					$('.status').html(result);
				} else {
					$('.status').html(response);
				}
			},
			error: function(response) {
				var data = '',
					obj = $.parseJSON(response.responseText);
				for(key in obj){
					data = data + ' ' + obj[key] + '<br/>';
				}
				$('.status').html(data);
	        }
		});
		return false;
	});
})(jQuery)
JS;

$js = "
(function ($) {
var uptime, col_time, autouptimer;
function check_online() {

	var oldtext=$( \".ajaxonline_status\" ).html(),
	request = {
					'option' : 'com_ajax',
					'module' : 'ajaxonline',
					'format' : '{$format}'
				};
	$.ajax({
	type: \"POST\",
	data:request,
	success:function(ans){
			if ((oldtext==ans)&&({$useincperiod})){
				uptime = uptime + {$refPeriod};
				clearInterval(autouptimer);
				autouptimer=setInterval(function() { check_online();}, uptime);
			} else {
				$( \".ajaxonline_status\" ).html(ans);
				uptime = {$refPeriod};
				$( \".ajaxonline_status\" ).css( \"border-color\",\"#00B300\");
				$(\".ajaxonline_status\").css( \"background-color\",\"#BFFFBF\");
				col_time = setTimeout(function() {
					$( \".ajaxonline_status\" ).css( \"border-color\",\"silver\");
					$(\".ajaxonline_status\").css( \"background-color\",\"white\");
					clearTimeout(col_time);
					}, 3000);
			}
	},
	error: function(response) {
		$( \".ajaxonline_status\" ).html(ans);
	}
	});
	return false;
//};
}
$(document).ready(
	function() {
		setInterval(function() { check_online();}, {$refPeriod});
	});
})(jQuery)";


$doc->addScriptDeclaration($js);

require(JModuleHelper::getLayoutPath('mod_ajaxonline'));