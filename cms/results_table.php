<?php
      include('classes/eCMSAutoloader.php');

	// NEW INSTANCE
      $my_data_fetcher = null;
	switch ($page) {
		case "pages":
			$my_data_fetcher = new eDataFetcherPages();
			break;
		case "blocks":
			$my_data_fetcher = new eDataFetcherBlocks();			
			break;				
		case "images":
			$my_data_fetcher = new eDataFetcherImages();			
			break;
		case "users":
			$my_data_fetcher = new eDataFetcherUsers();			
			break;
		case "styles":
			$my_data_fetcher = new eDataFetcherStyles();			
			break;	
		case "params":
			$my_data_fetcher = new eDataFetcherParams();			
			break;
		case "files":
			$my_data_fetcher = new eDataFetcherFiles();			
			break;
		default:
		
			$to_camel_case = preg_replace_callback("/_([a-z]{1})/im", function ($matches) { return strtoupper($matches[1]); }, $page);

			$plugin_class = 'eDataFetcher'.ucfirst($to_camel_case);
			$plugin_path = 'plugins/'.$page.'/'.$plugin_class.'.php';
			if (file_exists($plugin_path)===false) {
				die('<div class="error">ERROR : Plugins '.$page.' does not exist</div>');
			} 

			include($plugin_path);
			$my_data_fetcher = new $plugin_class();	

	}

	// ACTIONS //
	if ($my_data_fetcher->get_actions()!==false) {
		$my_data_fetcher->execute_actions();
	}
	
	// SENT VARIABLES
	if (isset($_GET['orderby'])) {			
		$my_data_fetcher->set_order($_GET);
	}
			
	if (isset($_POST['position'])) {		
		$my_data_fetcher->set_positions($_POST);				
	}

	if (isset($_POST['del'])) {		
		if ($my_data_fetcher->delete_one($_POST['del'])) {
			echo '<div class="success">'.eText::iso_htmlentities(eLang::translate('entry was successfully deleted', 'ucfirst')).'</div>';
		}
	}

	if (isset($_GET['selection'])) {			
		$my_data_fetcher->set_selection($_GET['selection']);
	}
			
	if (isset($_POST['search'])) {		
		$my_data_fetcher->set_search($_POST['search']);				
	}

	// FETCH DATAS
      $result_datas = $my_data_fetcher->get_results();
	
	eMain::show_errors();
	
?>
	<h1>Gestion des <?php echo $my_data_fetcher->titlename; ?></h1>
<?php	
	if ($my_data_fetcher->get_selection_options()!==false || $my_data_fetcher->get_current_search()!==false) {
?>
	<div id="submenu">
		<div id="selection_menu">
<?php	
		if ($my_data_fetcher->get_selection_options()) {
?>
			<a href="<?php echo eMain::get_website_root_url()."?p=".$page; ?>&selection=all" class="button <?php if ($my_data_fetcher->get_current_selection()=='all') { ?>selected<?php } ?>">Tous</a>
<?php 
			for ($a=0;$a<count($my_data_fetcher->get_selection_options());$a++) {
?>
			<a href="<?php echo eMain::get_website_root_url()."?p=".$page; ?>&selection=<?php echo urlencode($my_data_fetcher->get_selection_options()[$a]); ?>" class="button <?php if ($my_data_fetcher->get_current_selection()==$my_data_fetcher->get_selection_options()[$a]) { ?>selected<?php } ?>"><?php echo $my_data_fetcher->get_selection_options()[$a]; ?></a>
<?php 
			}
		}
?>
		</div>
<?php
		if ($my_data_fetcher->get_current_search()!==false) {
?>
		<div id="search_menu">
			<form name="form_search" method="post" action=""><input id="search" name="search" type="text" placeholder="Référence / nom" value="<?php echo $my_data_fetcher->get_current_search(); ?>" /> <input type="submit" value="Recherche" name="search_btn" /></form>
		</div>
<?php 
		}
?>
	</div>
<?php		
	}
?>

	<div class="align_right"><a href="index.php?p=<?php echo $page; ?>&addmod=0" class="add_btn">Ajouter une entrée</a></div>
<?php	

	$nb_cols = count($my_data_fetcher->cols_displayed_titles);

	$nb_lang = count(eParams::$available_languages);
	if (!$my_data_fetcher->is_multilingual()) { $nb_lang=1; }		
	if ($nb_lang>1) { include('_controls_lang.php'); }

	for ($l=0;$l<$nb_lang;$l++) {		
		$temp_lang = eParams::$available_languages[$l];				
		$table_datas = ($my_data_fetcher->is_multilingual()) ? $result_datas[$temp_lang] : $result_datas;

		$current_folder = null;
?>	
	<div class="lang_block" id="block_<?php echo $temp_lang; ?>">	
<?php	
		include('results_table_header.php');
		
		for($i=0;$i<$table_datas['nb'];$i++) {
		
			$row_values = $table_datas['datas'][$i];
			
			if (isset($my_data_fetcher->folder_field_name) && $row_values[$my_data_fetcher->folder_field_name]!=$current_folder) {
				// new folder = new table
				$current_folder = $row_values[$my_data_fetcher->folder_field_name];
				include('results_table_header.php');
			}
		
?>
			<tr>
				<td><?php echo $i+1; ?></td>
<?php	
			for($c=0;$c<$nb_cols;$c++) {	
				$col_displayed_name = $my_data_fetcher->cols_displayed_titles[$c];
				$col_field_name = $my_data_fetcher->get_col_field_name_at($c);
				$col_value = $row_values[$col_field_name];								
?>
				<td><?php echo $my_data_fetcher->get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $temp_lang); ?></td>
<?php
			}

			$addmod = $row_values[$my_data_fetcher->get_identifier()];
					
			if ($my_data_fetcher->is_editable($row_values)===false) {
				$mod_str = "";
			} else {					
				$mod_str = '<div><a href="index.php?p='.$page.'&addmod='.$addmod.'&lang='.$temp_lang.'" class="button">Modifier</a></div>';
			}
					
			if ($my_data_fetcher->is_deletable($row_values)===false) {
				$del_str = "";
			} else {			
				$del_str = '<form id="form_del_'.$addmod.'_'.$temp_lang.'" method="post" style="white-space: nowrap;"><input type="checkbox" name="del" value="'.$addmod.'" />&nbsp;<input type="submit" name="delete" value="Supprimer" /></form>';				
			}		
?>			
				<td>
					<!-- EDIT -->
					<?php echo $mod_str; ?>
					<hr>
					<?php echo $del_str; ?>
				</td>	
<?php
			if ($my_data_fetcher->get_actions()!==false) {			
				$this_action = $my_data_fetcher->get_actions();
				$this_action = str_replace('{ id }', $row_values['id'], $this_action); 
				if (isset($row_values['global_ref'])) { $this_action = str_replace('{ global_ref }', $row_values['global_ref'], $this_action); }
				$this_action = str_replace('{ lang }', $temp_lang, $this_action); 

				preg_match_all('/{ ([^}]+?) }/im', $this_action, $matches, PREG_PATTERN_ORDER); 
				//print_r($matches);die('test');
				for($m=0;$m<count($matches[1]);$m++) {
					$this_action = str_replace('{ '.$matches[1][$m].' }', $row_values[$matches[1][$m]], $this_action); 
				}
?>			
				<td>
					<!-- ACTION -->				
					<?php echo $this_action; ?>
				</td>
<?php
			} // END IF ACTION		
?>   
			</tr>
<?php	
			if (($i+1<$table_datas['nb']) && (isset($my_data_fetcher->folder_field_name) && $table_datas['datas'][$i+1][$my_data_fetcher->folder_field_name]!=$current_folder)) {
?>
		</table>
	</div><!-- END OF TABLE CONTAINER -->
<?php
			}

		} // END FOR LINE
?>
			</table>
	</div><!-- END OF TABLE CONTAINER -->
	</div><!-- END OF LANG BLOCK -->
<?php	
	} // END FOR LANGUAGES
?>	

	<div class="align_right"><a href="index.php?p=<?php echo $page; ?>&addmod=0" class="add_btn">Ajouter une entrée</a></div>

	<script type="text/javascript" src="eCMSResultsTable.js?d=202202231305"></script>