<?php
	header('Content-Type: text/html; charset=utf-8');
			
	include('../_eCore/eMain.php');
	eMain::start_app();
	
	include('../_eCore/addons/eCMS.php');
	
	$temp_lang = '';
	if (isset($_GET['lang'])) { $temp_lang = $_GET['lang']; }
	
	if (isset($_POST['convertTo']) && $_POST['convertTo']=='HTML') {
				
		$toHTML = $_POST['content'];
		
		//$toHTML = stripslashes($toHTML);
		$toHTML = eText::no_script(eText::style_to_html($toHTML, $temp_lang));
		//$toHTML = stripslashes($toHTML);
		
		echo $toHTML;
		
		
	} else if (isset($_POST['convertTo']) && $_POST['convertTo']=='code') {
		
		$fromHTML = $_POST['content'];
		
		$fromHTML = eText::html_to_style($fromHTML, $temp_lang);
		//$fromHTML = stripslashes($fromHTML);		
		echo $fromHTML;	
		
	} else {
		echo eText::html_to_style("Valeur POST inexistante");		
	}
		
	eMain::end_app(false);
	
?>