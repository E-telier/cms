<?php

	
	global $localized_sql_prefix;
	
	// GET SECTION //
	if ($page_datas['childof']!=$page_datas['reference']) {
		$rq = "SELECT menu_name, childof FROM ".$localized_sql_prefix."_cms_pages WHERE reference='".$page_datas['childof']."' LIMIT 1;";		
		$result_datas = eMain::$sql->sql_to_array($rq);					
		$content = $result_datas['datas'][0];
		
		$parent_name = $content['menu_name'];
		$parent_ref = $page_datas['childof'];
		
		if ($content['childof']!=$page_datas['childof']) {
			$rq = "SELECT reference, menu_name FROM ".$localized_sql_prefix."_cms_pages WHERE reference='".$content['childof']."' AND reference=childof LIMIT 1;";			
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
			$great_parent_name = $content['menu_name'];
			$great_parent_ref = $content['reference'];
		}
		
	}
	
?>
	<div id="ariane">
		<?php if (isset($great_parent_ref)) { ?><a href="<?php echo eMain::get_requested_folder_url().$great_parent_ref; ?>"><?php echo eText::iso_strtoupper($great_parent_name); ?></a> &gt; <?php } // END IF GREAT PARENT ?>
		<?php if (isset($parent_ref)) { ?><a href="<?php echo eMain::get_requested_folder_url().$parent_ref; ?>"><?php echo eText::iso_strtoupper($parent_name); ?></a> &gt; <?php } // END IF PARENT ?>
		<span class="myself"><?php echo eText::iso_strtoupper($page_datas['menu_name']); ?></span>
	</div>