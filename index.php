<?php 
	include('_eCore/eMain.php');
	eMain::start_app('_eCore/eMain.php');

	include('_eCore/addons/eCMS.php');
	eCMS::start_cms();
		
?><!DOCTYPE html>
<html lang="<?php echo eLang::get_lang()?>">

	<head>
							
		<meta charset="utf-8" />
		
		<title><?php echo eCMS::$page_datas['title']; ?></title>		
		
		<meta name="Keywords" lang="<?php echo eLang::get_lang()?>" content="<?php echo eText::iso_htmlentities(eCMS::$page_datas['keywords']); ?>" />
		<meta name="description" content="<?php echo eText::iso_htmlentities(eCMS::$page_datas['description']); ?>" />
		
		<meta name="generator" content="CMS E-TELIER <?php echo eCMS::$version; ?>" />
		<meta name="author" content="E-telier.be" />
		
		<meta name="robots" content="index, follow, all" />
		<meta name="revisit-after" content="7 days" />	
		
		<meta name="viewport" id="testViewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- GOOGLE FONTS -->
		<link href="https://fonts.googleapis.com/css?family=News+Cycle:400,700" rel="stylesheet" type="text/css">									
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic,700italic,300,300italic" rel="stylesheet" type="text/css">
				
		<link href="<?php echo eMain::get_website_root_url(); ?>css/css.css?date=202402161339" rel="stylesheet" type="text/css" />
		<link href="<?php echo eMain::get_website_root_url(); ?>css/pages_rules.css?date=202210141047" rel="stylesheet" type="text/css" />	
		
		<link href="<?php echo eMain::get_website_root_url(); ?>css/popup.css?date=202403212155" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo eMain::get_website_root_url(); ?>_office/css/colors.css?date=202204281029" rel="stylesheet" type="text/css" />
		<link href="<?php echo eMain::get_website_root_url(); ?>_office/css/form.css?date=202402161339" rel="stylesheet" type="text/css" />				
		<link href="<?php echo eMain::get_website_root_url(); ?>_office/css/popup.css?date=202403212205" rel="stylesheet" type="text/css" />

		<link href="<?php echo eMain::get_website_root_url(); ?>css/responsive.css?date=202402161339" rel="stylesheet" type="text/css" />
				
		<link rel="icon" type="image/icon" href="<?php echo eMain::get_website_root_url(); ?>favicon.ico?date=202006182111" />

		<!-- ICONS -->
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo eMain::get_website_root_url(); ?>favicon-16x16.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo eMain::get_website_root_url(); ?>favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo eMain::get_website_root_url(); ?>favicon-96x96.png">
				
		<link rel="apple-touch-icon" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon.png?date=201910081145" />
		<link rel="apple-touch-icon-precomposed" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-precomposed.png?date=201910081145" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-120x120-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-152x152-precomposed.png" />
		
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-120x120.png">		
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo eMain::get_website_root_url(); ?>apple-touch-icon-180x180.png">
			
		<link rel="icon" type="image/png" sizes="36x36"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-36x36.png">
		<link rel="icon" type="image/png" sizes="48x48"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-48x48.png">
		<link rel="icon" type="image/png" sizes="72x72"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-72x72.png">
		<link rel="icon" type="image/png" sizes="96x96"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-96x96.png">
		<link rel="icon" type="image/png" sizes="144x144"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-144x144.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo eMain::get_website_root_url(); ?>android-icon-192x192.png">
		
		<!-- SOCIAL MEDIA -->
		<meta property="og:locale" content="<?php echo $_SESSION['lang']; ?>" />
		<meta property="og:title" content="<?php echo eCMS::$page_datas['title']; ?>">
		<meta property="og:url" content="<?php echo eMain::get_website_root_url(); ?>">
		<meta property="og:description" content="<?php echo eText::iso_htmlentities(eCMS::$page_datas['description']); ?>">
		<meta property="og:site_name" content="<?php echo eParams::$site_name; ?>">
		<meta property="og:type" content="service">
		<meta property="og:image" content="<?php echo eMain::get_website_root_url(); ?>android-icon-192x192.png">
		
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?php echo eText::iso_htmlentities(eCMS::$page_datas['description']); ?>" />
		<meta name="twitter:title" content="<?php echo eCMS::$page_datas['title']; ?>" />
		<meta name="twitter:site" content="<?php echo eParams::$site_name; ?>" />
		<meta name="twitter:creator" content="E-telier.be" />
		<meta name="twitter:image" content="<?php echo eMain::get_website_root_url(); ?>android-icon-192x192.png" />
			
		<!-- USER STYLE -->
		<style type="text/css">
<?php
	$rq = "SELECT * FROM ".eParams::$prefix."_cms_styles WHERE activated=1;";	
	$result_datas = eMain::$sql->sql_to_array($rq);
	$nb_styles = $result_datas['nb'];;
	for($s=0;$s<$nb_styles;$s++) {
		$contenu = $result_datas['datas'][0];
		echo "		
		".$contenu['name']." {
			font-family:".$contenu['text_font'].";
			font-size:".$contenu['text_size']."px;
			color:".$contenu['text_color'].";
			
			background-color:".$contenu['background_color'].";			
			border:".$contenu['border_size']."px solid ".$contenu['border_color'].";
			
			".$contenu['other']."						
		}		
		";
	
	}
?>
		</style>
		<!-- END OF USER STYLE -->
		
	</head>
	
	<body class="lightmode">

		<!-- JQUERY NEEDS TO BE LOADED TO HANDLE $(document).ready() -->
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>js/jquery/jquery-3.7.1.min.js?d=202402161339"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>js/jquery/jquery-ui-1.13.1.min.js?d=202402161339"></script>
		<!-- <script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>js/jquery/jquery.ui.touch-punch-0.2.3.min.js"></script> -->

		<script type="text/javascript">			
			const gWebsiteRootURL = '<?php echo eMain::get_website_root_url(); ?>';
			const gWebsiteLanguage = '<?php echo eLang::get_lang(); ?>';
		</script>
		
		<!-- eMain NEEDS TO BE LOADED TO HANDLE pageLoaded EVENT -->
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>js/_eCore/eMain.js?date=202403212123"></script>		
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>js/style.js?date=202402232252"></script>
							
		<!-- START OF SITE -->
		<div id="site" class="<?php echo eCMS::$page_datas['reference']; ?><?php echo str_replace('*', ' access_', substr(eCMS::$page_datas['access'], 0, strlen(eCMS::$page_datas['access'])-1)); ?>">
		
			<!-- START OF HEADER -->
			<header id="header">
				<div class="pagewidth">
				
					<!-- HEADER CONTENT -->
					<?php eCMS::print_indented_html_including_modules(eText::style_to_html(eCMS::$page_datas['banner_content']), 'header'); ?>	
					
<?php 
					// LANGUAGES //						
					$nb_lang = count(eParams::$available_languages);
					if ($nb_lang>1) {
?>
					<!-- START OF LANGUAGES -->
					<div id="languages">
<?php 							
							for ($i=0;$i<$nb_lang;$i++) {
								$lang = eParams::$available_languages[$i];
								$lang_str = strtoupper($lang);
								
								$rq_lang = "SELECT reference FROM ".eParams::$prefix.'_'.$lang."_cms_pages WHERE global_ref='".eCMS::$page_datas['global_ref']."' LIMIT 1;";
								$result_lang = eMain::$sql->sql_to_array($rq_lang);
								
								$lang_page_ref = '';
								if ($result_lang['nb']>0) {
									$content_lang = $result_lang['datas'][0];
									$lang_page_ref = $content_lang['reference'];
								}
								$lang_url = eMain::get_website_root_url().$lang.'/'.$lang_page_ref;							
?>
						<a href="<?php echo $lang_url; ?>" <?php if ($lang==eLang::get_lang()) { ?>class="selected"<?php } ?> id="<?php echo $lang; ?>"><?php echo eText::iso_htmlentities(ucfirst($lang_str)); ?></a>
<?php
							} // END OF FOR LANGUAGES
?>
					</div><!-- END OF LANGUAGES -->
<?php
						} // END OF IF LANGUAGES > 1						
?>

					<!-- MENU -->
					<nav id="main_menu">
						<ul><!--
<?php							
							$accessible_pages_datas = eCMS::get_all_accessible_pages();
							
							$nb_menu = $accessible_pages_datas['nb'];;
							for($m=0;$m<$nb_menu;$m++) {
								$this_menu_element = $accessible_pages_datas['datas'][$m];	
								
								// SECTION
								$current_section = false;
								if (eCMS::$page_datas['childof']==$this_menu_element['reference']) {
									// Main menu element
									$current_section = true;
								} else {
									$rq_grandchild = "SELECT id FROM ".eCMS::$localized_sql_prefix."_cms_pages WHERE reference='".eCMS::$page_datas['childof']."' AND childof='".$this_menu_element['reference']."';";
									
									if (eMain::$sql->sql_to_num($rq_grandchild)>0) {
										// Submenu element
										$current_section = true;
									}
								}
								
								// COMBINAISON
								$combined = false;
								if ($this_menu_element['combined']==1 && $this_menu_element['childof']==eCMS::$page_datas['reference']) {
									// Main combinaison
									$combined = true;
								} else if ($this_menu_element['reference']==eCMS::$page_datas['reference']) {									
									$rq_combinator = "SELECT id FROM ".eCMS::$localized_sql_prefix."_cms_pages WHERE combined='1' AND childof='".$this_menu_element['reference']."';";
									if (eMain::$sql->sql_to_num($rq_combinator)>0) {
										// Sub combinaison										
										$combined = true;
									}
								}
								
								// INTERACTION
								$url = eMain::get_website_root_url().eLang::get_lang().'/'.$this_menu_element['reference'];
								$onclick = '';								
								if ($combined) {
									$url = eMain::get_website_root_url().eLang::get_lang().'/'.$this_menu_element['childof'].'/#'.$this_menu_element['reference'];
									$onclick = "eCMS.manageAnchorScroll('".$url."'); event.stopPropagation(); event.preventDefault(); return false;";
								}
								
?>
							--><li class="<?php echo $this_menu_element['reference']; ?><?php if ($current_section == true) { ?> selected<?php } ?>">
								<a href="<?php echo $url; ?>" onclick="<?php echo $onclick; ?>">
									<?php echo eText::style_to_html(ucfirst($this_menu_element['menu_name'])); ?>
								</a>
							</li><!--
<?php
							} // END OF FOR MENU
?>
						--></ul>
						<div class="clear"> </div>
					</nav><!-- END OF MENU -->


					<div class="clear"> </div>							
				</div><!-- END OF PAGEWIDTH -->
			</header><!-- END OF HEADER -->	

			<!-- START OF PAGES -->
			<div id="pages">
<?php

			$combined_pages_datas = eCMS::get_combined_pages();
									
			for ($p=0;$p<$combined_pages_datas['nb'];$p++) {
				$this_combined_page = $combined_pages_datas['datas'][$p];
?>							
				<section class="page" id="<?php echo $this_combined_page['reference']; ?>">	
					<!-- START OF MIDDLE -->
					<div class="middle">
						<div class="pagewidth">		

							<!-- TEXT BLOCKS -->
<?php 					
						$rq = "SELECT * FROM ".eCMS::$localized_sql_prefix."_cms_blocks WHERE content_block=0 AND ((pages_ref='*' OR sections_ref='*') OR (pages_ref LIKE '%*".$this_combined_page['reference']."*%' OR sections_ref LIKE '%*".$this_combined_page['childof']."*%')) ORDER BY position ASC;";
						
						$result_datas = eMain::$sql->sql_to_array($rq);
						$nb_block = $result_datas['nb'];;				
						for($b=0;$b<$nb_block;$b++) {
							$block_datas = $result_datas['datas'][$b];
							
							if ($b==0) {
?>						
							<aside class="blocks"><!--
<?php
							} // END IF b==0					
						
							$block_title = eText::style_to_html($block_datas['title']);
							$block_content = eText::style_to_html($block_datas['content']);
							$style = '';
							$class=$block_datas['reference'];
							if (!empty($block_datas['bgcolor'])) { 
								$style.='background-color:#'.$block_datas['bgcolor'].';';
								$rgb = eTools::hex_to_rgb($block_datas['bgcolor']);
								if ($rgb[0]+$rgb[1]+$rgb[2]>=600) { $class.=' darktext'; } else { $class.=' lighttext'; }
							}
							if ($block_datas['textalign']!='initial') { 
								$class.=' align_'.$block_datas['textalign'];
							}
							if (!empty($style)) { $style = 'style="'.$style.'"'; }
?>
								--><div id="block_<?php echo $block_datas['id']; ?>" class="block <?php echo $class; ?>" <?php echo $style; ?>>
									<?php if (!empty($block_title)) { echo "<h3>".$block_title."</h3>"; } ?> 
									<?php eCMS::print_indented_html_including_modules($block_content, 'block'); ?> 
								</div><!-- END OF BLOCK --><!--
<?php
							
							if ($b==$nb_block-1) {
?>
							--></aside><!-- END OF BLOCKS -->
<?php
							} // END IF LAST b						
							
						} // END FOR BLOCKS
?>				
							<!-- END OF TEXT BLOCKS -->
						
							<!-- CONTENT -->
<?php							
						if (!empty($this_combined_page['content'])) {
?>					
							<div class="content">
								<?php eCMS::print_indented_html_including_modules(eText::style_to_html($this_combined_page['content']), 'content'); ?>						
							</div>
							<!-- END OF CONTENT -->																						
<?php		
						} // END OF IF CONTENT
?>									
							<div class="clear"> </div>
						</div><!-- END OF PAGEWIDTH -->	
					</div><!-- END OF MIDDLE -->
				</section><!-- END OF PAGE -->			
<?php
			} // END FOR COMBINED PAGES			
?>
			</div><!-- END OF PAGES -->
			
			<!-- START OF FOOTER -->
			<footer id="footer">
				<div class="pagewidth">
					<?php eCMS::print_indented_html_including_modules(eText::style_to_html(eCMS::$page_datas['footer_content']), 'footer'); ?>
					<div class="clear"> </div>
				</div><!-- END OF PAGEWIDTH -->	
			</footer>
			<!-- END OF FOOTER -->
						
		</div>	
		<!-- END OF SITE -->

		
<?php 
		// GOOGLE ANALYTICS & CONSENT 
		include('_ga.php'); 
?>
		
	</body>	
</html>
<?php
	eMain::end_app();
?>
