<?php
	global $localized_sql_prefix;
	
	

	$rq_news = "SELECT id, object, date FROM ".$localized_sql_prefix."_back_newsletters WHERE selected=1 ORDER BY date DESC;";
	$result_news = eMain::$sql->sql_to_array($rq_news);
		
	for ($i=0;$i<$result_news['nb'];$i++) {
		$content_news = $result_news['datas'][$i];
		
		$block_content = "[div class='more']News du ".formatDate($content_news['date']).'[/div]';
		$block_title = '[HTML]<a href="'.eMain::get_website_root_url().eLang::get_lang()."/news/".strToURL($content_news['object']).'" title="'.iso_htmlentities($content_news['object']).'">[/HTML]'.$content_news['object']."[/a]";
?>
								
								<?php 
									if (!empty($block_title)) { echo "<h4>".eText::style_to_html($block_title)."</h4>"; } 
									echo eText::style_to_html($block_content); 
								?>
<?php
	}
?>