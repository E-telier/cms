	<script type="text/javascript">
	<!--
		$(document).ready(function() {
			//$('#content *').css({'font-family':'Arial, helvetica, sans'});
		});		
	-->
	</script>
<?php

	global $currentDatas;
	global $localized_sql_prefix;
	

	$ref = $currentDatas[1];
	
	$rq_news = "SELECT global_ref, object, text, date FROM ".$localized_sql_prefix."_back_newsletters WHERE selected=1;";
	$result_news = eMain::$sql->sql_to_array($rq_news);
	
	$block_content='';
	for ($i=0;$i<$result_news['nb'];$i++) {
		$content_news = $result_news['datas'][$i];
		
		if (eText::str_to_url($content_news['object'])==$ref) {			
?>
			<h1><?php echo eText::iso_htmlentities($content_news['object']); ?></h1>
			<?php echo eText::style_to_html($content_news['text']); ?>
			<br />
			<br />
			<div class="more">Publié le <?php echo eText::format_date($content_news['date'])?></div>
			<a href="<?php echo eMain::get_website_root_url()."cms/plugins/newsletters/preview.php?newsletter_ref=".$content_news['global_ref']; ?>" target="_blank" class="more">Voir la newsletter</a>
			<br />
<?php
		}
	}
	
	echo eText::style_to_html($block_content);
	
?>