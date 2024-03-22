<?php 
	ini_set('memory_limit', '512M');
	
	include('../_eCore/eMain.php');
	eMain::start_app('../_eCore/eMain.php');

	include('../_eCore/addons/eCMS.php');
	eCMS::start_cms();
	
	$nb_lang = count(eParams::$available_languages);
	
?><!DOCTYPE html>
<html lang="<?php echo eLang::get_lang(); ?>">

	<head>
	
		<title><?php echo eText::iso_htmlentities(eCMS::$page_datas['title']); ?></title>
				
		<meta charset="utf-8" />
		<meta name="Author" lang="fr" content="Elliot Coene" />
		<meta name="Keywords" lang="fr" content=<?php echo eText::iso_htmlentities(eCMS::$page_datas['keywords']); ?> />
		<meta name="description" content=<?php echo eText::iso_htmlentities(eCMS::$page_datas['description']); ?> />
		<meta name="Identifier-URL" content="http://www.e-telier.be" />
		<meta name="Reply-to" content="contact@e-telier.be" />
		<meta name="robots" content="noindex" />
		<meta name="classification" content="cms" />
							
		<link href="<?php echo eMain::get_requested_folder_url(); ?>jquery-ui-1.12.1.custom/jquery-ui.min.css?date=201704111423" rel="stylesheet" type="text/css" />
		<link href="<?php echo eMain::get_requested_folder_url(); ?>jquery-ui-1.12.1.custom/jquery-ui.structure.min.css?date=201704111423" rel="stylesheet" type="text/css" />
		<link href="<?php echo eMain::get_requested_folder_url(); ?>jquery-ui-1.12.1.custom/jquery-ui.theme.min.css?date=201704111423" rel="stylesheet" type="text/css" />							
				
		<link href="../css/css.css?date=202205040911" rel="stylesheet" type="text/css" />
		<link href="../css/popup.css?date=202205270856" rel="stylesheet" type="text/css" />

		<link href="cms.css?date=202205271532" rel="stylesheet" type="text/css" />
		<link href="custom.css?date=202205031344" rel="stylesheet" type="text/css" />
			
	</head>
			
	<body>
		<script type="text/javascript">
			var gWebsiteRootURL  = '<?php echo eMain::get_website_root_url(); ?>';
			gWebsiteRootURL  = gWebsiteRootURL.replace('/cms', '');
		</script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/jquery/jquery-3.4.1.min.js"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>jquery-ui-1.12.1.custom/jquery-ui.min.js?d=202203211019"></script>
		
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eWysiwyg.js?date=202308251545"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eCustomSelect.js?date=202308251545"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eForm.js?date=202308251601"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/ePopup.js?date=202308251601"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eTools.js?date=202308251601"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eText.js?date=202308251601"></script>

		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eRadio.js?date=202308251545"></script>
		<script type="text/javascript" src="<?php echo eMain::get_website_root_url(); ?>../js/_eCore/eCheckbox.js?date=202308251545"></script>
		
		<div id="site">
			<header id="header">
				<div class="pagewidth">
					<?php echo eText::style_to_html(eCMS::$page_datas['banner_content']); ?>
					
<?php
	// CONNECTION //	
	$access = false;
	if (eUser::getInstance()->checked) {
		$user_datas = eUser::getInstance()->get_datas('domain', true);
		
		if ($user_datas['domain']== '*' || strpos($user_datas['domain'], '*admin*')!==false) {					
			$access = true;

			$page = "";
			if (isset($_GET['p'])) {
				$page = $_GET['p'];
			}
			
			$table = "";
			if (isset($_GET['table'])) {
				$table = $_GET['table'];
			}
			

		} else {
			eMain::add_error("this user is not allowed to access this page");
		}
	}
	if ($access) {
		if (isset($_GET['saveandquit'])) {
			echo '<h2 class="clear">CLOSING...</h2>';
			if (eMain::$sql->backup('prefix')) {
				echo '<div class="success">BACKUP SUCCESSFUL</div>';
			} else {
				echo '<div class="error">BACKUP ERROR</div>';
			}
			session_unset();
			//header('Location: '.eMain::get_requested_url(true).'?disconnect=1');
			die('<script type="text/javascript">setTimeout(function() { window.location.href="'.eMain::get_requested_url(true).'?disconnect=1'.'"; }, 2000);</script>');
		}
?>					

					<div class="header_right">
						<nav id="main_menu">
							<ul>															
								<li <?php if ($page=="pages") { ?>class="selected"<?php } ?>><a href="index.php?p=pages">Pages</a></li>
								<li <?php if ($page=="blocks") { ?>class="selected"<?php } ?>><a href="index.php?p=blocks">Text-blocks</a></li>
								<li <?php if ($page=="images") { ?>class="selected"<?php } ?>><a href="index.php?p=images">Images</a></li>
								<li <?php if ($page=="params") { ?>class="selected"<?php } ?>><a href="index.php?p=params">Params du site</a></li>
								<li <?php if ($page=="files") { ?>class="selected"<?php } ?>><a href="index.php?p=files">Fichiers</a></li>
								<li <?php if ($page=="users") { ?>class="selected"<?php } ?>><a href="index.php?p=users">Utilisateurs</a></li>							
								<li <?php if ($page=="styles") { ?>class="selected"<?php } ?>><a href="index.php?p=styles">Style / CSS</a></li>
								<li id="quit_btn"><a href="index.php?saveandquit=1">Save & QUIT</a></li>
							</ul>
							
<?php

						// PLUGINS BACK OFFICE //
						$modules_back = array();
						for ($m=0;$m<eCMS::$modules['nb'];$m++) {
							if (eCMS::$modules['datas'][$m]['backoffice']!='') {
								$modules_back[eCMS::$modules['datas'][$m]['reference']] = eCMS::$modules['datas'][$m];
							}
						}
										
						if (count($modules_back)>0) {
							$modules_keys = array_keys($modules_back);
?>
							Back Office :<br />
							<ul>
<?php
							for ($m=0;$m<count($modules_back);$m++) {
?>
								<li <?php if ($page==$modules_back[$modules_keys[$m]]['backoffice']) { ?>class="selected"<?php } ?>><a href="index.php?p=<?php echo $modules_back[$modules_keys[$m]]['backoffice']; ?>"><?php echo eText::iso_htmlentities($modules_back[$modules_keys[$m]]['name']); ?></a></li>
<?php
							} // END FOR PLUGINS BACK
?>
							</ul>
<?php						
						} // END IF PLUGINS BACK						

?>							
						</nav><!-- END OF MENU -->
					</div>
<?php					
	} // END OF IF ACCESS
?>
					<div class="clear" style="clear:both;"> </div>
				</div>				
			</header>		
					
			<div class="middle">
				<div class="pagewidth">
					
<?php
	eMain::show_errors();

	if ($access) {
							
		// CMS //
		if (isset($_GET['addmod'])) {
			$addmod = $_GET['addmod'];
			include("addmod.php");
		} else if (!empty($page)) {
			include("results_table.php");
		} else {
			
?>
					<div class="content">
						<h1>Connexion réussie</h1>
						<div class="paragraph">
							Veuillez choisir une action dans le menu...
						</div>
					</div>
					<div class="clear"> </div>
<?php			
		}					
									
	} // END OF IF ACCESS

	eMain::show_errors();
			
	if ($access == false) {			
?>				
					<div class="content">
						<h1>Connexion au système</h1>
						<div class="paragraph">
							Connectez-vous avec votre nom d'utilisateur et votre mot de passe :<br /><br />
							<form method="post" action="index.php" id="connect">
								Username : <input name="connect_login" value="" type="text" />
								<br /><br />
								Password : <input name="connect_password" value="" type="password" />
								<br /><br /><br />
								<input type="submit" name="connect" value="Entrer" />
							</form>
						</div>
					</div>
					<div class="clear"> </div>
<?php								
	} // END IF NO ACCESS			
?>
				</div><!-- END OF PAGEWIDTH -->	
			</div><!-- END OF MIDDLE -->
			<div style="clear:both;"> </div>
			
			<div id="footer">
				<div class="pagewidth">
					<?php echo eText::style_to_html(eCMS::$page_datas['footer_content']); ?>
				</div>
			</div>	
			
		</div><!-- END OF SITE -->		
	</body>	
</html>
<?php
	eMain::end_app();
?>