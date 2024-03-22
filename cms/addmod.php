<?php 
	$ajax_items_separator= "{ itemDelimiter }"; 
			
	if ($page=="pages") {	
		include('_form_pages.php');
	} else if ($page=="blocks") {
		include('_form_blocks.php');		
	} else if ($page=="users") {
		include('_form_users.php');
	} else if ($page=="styles") {
		include('_form_styles.php');
	} else if ($page=="params") {
		include('_form_params.php'); 
	} else if ($page=="images") {
		include('_form_images.php'); 
	} else if ($page=='files') {
		include('_form_files.php'); 
	} else {	
		// PLUGINS //
		for ($m=0;$m<eCMS::$modules['nb'];$m++) {
			if (eCMS::$modules['datas'][$m]['backoffice']!='') {
				include('plugins/'.eCMS::$modules['datas'][$m]['backoffice'].'/addmod.php');
			}
		}
	}
	
?>