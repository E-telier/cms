<?php
	if (isset($currentDatas[1])) {
		
		$rq_plugin = "SELECT * FROM ".eParams::$prefix.eLang::get_lang()."_back_".$modules[$m]['backoffice']." WHERE title='".addslashes(urldecode($currentDatas[1]))."';";
		$result_plugin = mysqli_query($mysqli, $rq_plugin);
		$content_plugin = mysqli_fetch_array($result_plugin);
	
		$title = noReturns(eText::no_style($content_plugin['title']), ' - ');
					
		$text_only = noReturns(eText::no_style($content_plugin['content']), ' - ');									
		if (strlen($text_only)>255) {
			$next_space = strpos($text_only, ' ', 255);
			$short_text = substr($text_only, 0, $next_space); 
			if (strlen($text_only)>$next_space) { $short_text .= ' (...)'; }				
		} else {
			$short_text = $text_only;
		}
		$pageDescription = $short_text;
	}
?>