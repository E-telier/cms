	<?php
	if ($page=='faq_categories') {
	
		$table = "_back_faq_categories";
		
		// Traitement form //
		if (isset($_POST['submit_check'])) {
					
			// FILL EVERY LANGUAGES WITH POSTED IF EMPTY //
			$exceptions = array();
			$post_keys = array_keys($_POST);
			for ($i=0;$i<count($_POST);$i++) {
				$key = $post_keys[$i];
				if (empty($_POST[$key])) {					
					$type_key = substr($key, 3);
					if (array_search($type_key, $exceptions)==false) {
						if (isset($_POST[$_POST['post_lang']."_".$type_key])) {
							$_POST[$key] = $_POST[$_POST['post_lang']."_".$type_key];
						}
					}
				}
			}
						
			$new_global_ref = date('YmdHis');
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
									
				if ($addmod==0) {
					$rq = "INSERT INTO ".eParams::$prefix."_".$temp_lang.$table." (global_ref, category, position, creation_date, visible) VALUES (
						'".eMain::$sql->protect_sql($new_global_ref)."', 
						'".eMain::$sql->protect_sql($_POST[$temp_lang.'_category'])."', 
						'".eMain::$sql->protect_sql($_POST[$temp_lang.'_position'])."', 
						'".date('Y-m-d')."', 
						'".eMain::$sql->protect_sql($_POST[$temp_lang.'_visible'])."'
					);";
				} else {
					$rq = "UPDATE ".eParams::$prefix."_".$temp_lang.$table." SET 
						category='".eMain::$sql->protect_sql($_POST[$temp_lang.'_category'])."', 
						position='".eMain::$sql->protect_sql($_POST[$temp_lang.'_position'])."', 
						visible='".eMain::$sql->protect_sql($_POST[$temp_lang.'_visible'])."' 
					WHERE global_ref='".eMain::$sql->protect_sql($addmod)."';";
				}
				if (!eMain::$sql->sql_query($rq)) {
					eMain::add_error('SQL request failed');
				} else {
					echo '<div class="success">'.eText::iso_htmlentities(eLang::translate('data successfully saved!', 'ucfirst')).'</div>';
				}
			} // END FOR LANGUAGES
		}
		
		// Affichage datas //
		$values = array();
		if ($addmod==0) {
		
			$content = array();		
			$content['category']="Questions générales";			
			$content['position']="0";
			$content['visible']='1';
						
			for ($l=0;$l<$nb_lang;$l++) {
				$values[eParams::$available_languages[$l]] = $content;
			}
			
			$title = "Ajout d'une catégorie";
		
		} else {
						
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
								
				$rq = "SELECT * FROM ".eParams::$prefix."_".$temp_lang.$table." WHERE global_ref=".eMain::$sql->protect_sql($addmod)." LIMIT 1;";
				
				$result_datas = eMain::$sql->sql_to_array($rq);
				$content = $result_datas['datas'][0];
												
				//if (!get_magic_quotes_gpc()) {				
					$content['title'] = eText::html_quotes($content['title']);
					$content['content'] = eText::html_quotes($content['content']);
					$content['keywords'] = eText::html_quotes($content['keywords']);
				//}
				$values[$temp_lang] = $content;				
			}
						
			$title = "Modification de l'article \"".$values[eLang::get_lang()]['category']."\"";
			
			
						
		}
					
?>
	<h1><?php echo eText::style_to_html($title); ?></h1>
	<br />
<?php include('_controls_lang.php'); ?>
	<div class="addmod">
	<form method="post" id="form_addmod" name="add" enctype="multipart/form-data">
<?php
	for ($l=0;$l<$nb_lang;$l++) {
		$temp_lang = eParams::$available_languages[$l];
?>		
	<div id="form_<?php echo $temp_lang; ?>" class="form_lang">
		<table cellspacing="0">
			<tr>
				<td width="30%">Catégorie : <br /><small></small></td>
				<td>
					<input type="text" value=<?php echo "\"".$values[$temp_lang]['category']."\""; ?> name="<?php echo $temp_lang; ?>_category" size="64" />
				</td>
			</tr>			
			<tr>
				<td width="30%">Position : <br /><small></small></td>
				<td>
					<input type="text" value=<?php echo "\"".$values[$temp_lang]['position']."\""; ?> name="<?php echo $temp_lang; ?>_position" size="3" />
				</td>
			</tr>
			
			<tr>
				<td>Visible : </td>
				<td>
					<select name="<?php echo $temp_lang; ?>_visible">
						<option value="1" <?php if ($values[$temp_lang]['visible']=='1') { ?>selected="selected"<?php } ?>>Oui</option>
						<option value="0" <?php if ($values[$temp_lang]['visible']=='0') { ?>selected="selected"<?php } ?>>Non</option>
					</select>
				</td>
			</tr>
						
		</table>
				
		<div class="float-right">
			<div class="submit" onclick="$('#post_lang').val('<?php echo $temp_lang; ?>'); eForm.submit(this);">ENREGISTRER</div>
		</div>
		<div class="clear"> </div>
		
		</div><!-- END OF LANG CONTAINER -->
<?php
	} // END FOR LANGUAGES
?>	
		<input type="hidden" name="submit_check" value="1" />
		<input type="hidden" name="post_lang" id="post_lang" value="default" />
	</form>
	</div> <!--END OF ADDMOD -->
<?php
	} // END IF ARTICLES
?>		