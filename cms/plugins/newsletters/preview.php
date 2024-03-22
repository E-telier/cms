<?php

	die('lala');

	ini_set('memory_limit', '512M');
	
	include('../../../_eCore/eMain.php');
	eMain::start_app('../../../_eCore/eMain.php');
	
	include('../../../_eCore/addons/eCMS.php');
	eCMS::start_cms();
?>
<?php
	
	$temp_lang = 'fr';
	if (isset($_GET['lang'])) {
		$temp_lang = preg_replace('/[^A-Za-z]*/im', '', $_GET['lang']);				
	}
		
	$content['object'] = '{TITLE}';
	$content['id_attachments']='';
	$content['text'] = '{CONTENT}';
	$content['date'] = '{DATE}';
	if (isset($_GET['newsletter_ref'])) {
		
		$newsletter_ref = preg_replace('/[^0-9A-Za-z]/im', '', $_GET['newsletter_ref']);
		
		$rq = "SELECT * FROM ".eParams::$prefix.'_'.$temp_lang."_back_newsletters WHERE global_ref='".eMain::$sql->protect_sql($newsletter_ref)."'";
		$result_datas = eMain::$sql->sql_to_array($rq);
		if (isset($result_datas['datas'][0])) {
			$content = $result_datas['datas'][0];
		}

		if ($temp_lang == 'fr') {
			$date_array = explode('-', $content['date']);	
			$months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');	
			$content['date'] = $date_array[2]." ".$months[intval($date_array[1])-1]." ".$date_array[0];
		}
	}
		
	$rootURL = eMain::get_website_root_url();
	//eMain::set_root_url($rootURL);	
			
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo eText::style_to_html($content['object']); ?></title>
	</head>

	<body>
		<style type="text/css">	
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
			text-align:left;
			
			background-color:#F1F1F1;
			
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #464646;
		}	
		table {
			font-family: Arial, Helvetica, sans-serif;
			text-align:left;
			font-size: 12px;
			color: #464646;
		}
		a {
			color:#3E7DEB;
		}
		h1 {		
		
			color:#E65214;
			
			font-size:22px;	
			font-weight:normal;	
			
			margin:0px;
			margin-bottom:2px;
			padding:0px;		
					
		}
		h2 {		
			
			font-size:16px;	
			font-weight:bold;	
			color:#3E7DEB;
			
			margin:0px;
			padding:0px;	
			padding-left: 10px;
			padding-bottom: 5px;
			
			border-bottom:2px solid #3E7DEB;
							
		}
		
		#full {
			padding:30px;
		}
		#header {
			font-size:11px;
			vertical-align:bottom;
		}
		#header_info {
			font-size:11px;
		}
		#header, .content, #newsletter_attachments {
		
			background-color:#ffffff;
		
			height:40px;
			padding:10px;
			padding-right:20px;
			padding-left:20px;
			
			border:1px solid #E6E6E6;
		}
		#newsletter_footer {
			margin-top:20px;
			padding-top:10px;
			padding-bottom:10px;
			
			border-top:1px solid #E7E7E7;
			
			font-size: 9px;
		}
		#newsletter_unsubscribe {
			text-align:center;
			font-size:9px;
			
			padding:0px;
			padding-top:10px;
		}
			
		.spacer {
			padding:0px;
			height:10px;
			overflow:hidden;
		}
		.img {	
		
			margin:0px;
			margin-top:0px;
			
			border:0px;
			vertical-align:middle;
		
		}
		.img_left {
			margin-right:16px;
		}
		.left {
			float:left;
			margin-left:0px;
		}	
		.img_right {
			margin-left:16px;
		}	
		.right {
			float:right;
			margin-left:0px;
		}
		.center {
			
			text-align:center;
			
			display:block;
					
			margin-left:auto;
			margin-right:auto;
					
			background-color:transparent;
			padding:0px;
			
			margin:0px;
			clear:both;
		}
		.align_center {
			text-align:center;
		}
		.color_red {
			color: #CC3300;
		}
		.color_blue {
			color: #0033CC;
		}
		.color_green {
			color: #00CC33;
		}
		.color_gray {
			color: #999999;
		}
		.clear {
			clear:both;
		}
		</style>
		<table class="etelier" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" id="full">
					<table width="600" border="0" cellspacing="0" cellpadding="20">
						<tr>
							<td id="header" bgcolor="#ffffff">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="50%">
											<h1><?php echo eParams::$site_name; ?></h1>
										</td>
										<td width="50%" align="right" id="header_info">
											<?php echo eText::style_to_html($content['object']); ?>
											<br />
											<?php echo eText::style_to_html($content['date']); ?>
											<br />
											<?php if (isset($newsletter_ref)) { echo eText::style_to_html("[url='".eMain::get_requested_url()."']Version web[/url]"); } ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="spacer"> </td>
						</tr>
						<tr>
							<td id="content" bgcolor="#ffffff">
								<!-- START OF CONTENT -->
								<?php echo eText::style_to_html($content['text'], $temp_lang); ?>
								<!-- END OF CONTENT -->							
								<table id="newsletter_footer" width="100%" border="0" cellspacing="0" cellpadding="0">							
									<tr>
										<td width="50%">
											<a href="mailto:<?php echo eParams::$contact_email; ?>">Contact</a><?php echo eText::style_to_html(" • [url='https://www.ami-project.be']Website[/url]"); ?>
										</td>
										<td width="50%" align="right">
											
										</td>
									</tr>							
								</table>
							</td>
						</tr>
											
								<?php
									$id_attachments = explode("*", $content['id_attachments']);
									
									$conditions = "";
									
									//print_r($id_attachments);
									
									$nb_attachments_in = count($id_attachments);							
									for ($a=0;$a<$nb_attachments_in;$a++) {
										if (empty($id_attachments[$a])) { 
											array_splice($id_attachments, $a, 1); 
											$nb_attachments_in--; 
											$a--; 
										}
										else { 
											$id_attachments[$a]= intval($id_attachments[$a]); 
											
											if ($conditions!="") { $conditions.=" OR "; }							
											$conditions.= "id=".$id_attachments[$a]; 
											//echo $conditions;
										}
									}
									
									sort($id_attachments);					
									if ($conditions!="") { 
										$conditions = "WHERE ".$conditions; 				
									
										$rq_a = "SELECT * FROM ".eParams::$prefix."_cms_files ".$conditions." ORDER BY folder ASC, filename ASC, extension ASC;";										
										$attachments_datas = eMain::$sql->sql_to_array($rq_a);
										$nb_a = $attachments_datas['nb'];
										
										if ($nb_a>0) {
										
									?>
						<tr>
							<td class="spacer"> </td>
						</tr>
						<tr>
							<td id="newsletter_attachments">
									<?php
										//echo $nb_a;
											for ($a=0;$a<$nb_a;$a++) {
												$content_a = $attachments_datas['datas'][$a];
												if ($a==0) { echo "<b>Pièces jointes :</b><br />\n<br />\n"; }					
													if (in_array($content_a['extension'], array('.jpg', '.png', '.gif'))) { $type = "Image"; }
													else { $type = "Fichier"; }

													if (!empty($content_a['folder'])) { $content_a['folder'].= '/'; }

													echo "
								<a href=\"".eMain::get_website_root_url().'download/'.$content_a['filename'].$content_a['extension']."\" target=\"_blank\">".$content_a['filename']."</a> (".$type." ".intval($content_a['weight']/1024)." ko)<br />
													";
											} // END OF FOR ATTACHMENTS
									?>
							</td>						
						</tr>
									<?php
										} // END IF NB_A > 0
									} // END OF IF ATTACHMENTS
									
								?>
							
						<tr>
							<td id="newsletter_unsubscribe">
								<?php if (isset($newsletter_ref) || isset($_GET['unsubscribe'])) { echo eText::style_to_html("[a href='https://www.ami-project.be/?unsubscribe={USER_EMAIL}']".eLang::translate('I no longer wish to receive e-mail from AMI Project')."[/a]"); } ?>
							</td>						
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
